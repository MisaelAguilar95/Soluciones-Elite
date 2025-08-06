<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ClientController extends Controller
{
    use AuthorizesRequests;

    // tus métodos...


   public function index()
    {
        $user = auth()->user();
    
        if ($user->level === 'admin') {
            $clients = Client::with(['loans.weeks', 'user'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        } else {
            $clients = $user->clients()->with(['loans.weeks', 'user'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        }
    
        // Actualizar estado de préstamos según semanas
        foreach ($clients as $client) {
            foreach ($client->loans as $loan) {
                $loan->actualizarEstado();
            }
        }
        $users = User::all();
    
        return view('clients.index', compact('clients','users'));
    }




    public function create()
    {
         $users = User::all(); // Obtener todos los usuarios para el select
        return view('clients.create', compact('users'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'monto' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'address' => 'required|string|max:455',
            'curp' => [
                          'required',
                          'string',
                          'size:18',
                          'regex:/^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z0-9]\d$/',
                          'unique:clients,curp'
            ],
             'user_id' => 'required|exists:users,id',
        ]);

        $client = Client::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'monto' => $request->monto,
            'address' => $request->address,
            'curp' => $request->curp,
            'aval' => $request->aval,
            'user_id' => $request->user_id,
        ]);
        $interes = $request->monto * .4;
        $restante = $interes + $request->monto;
        
        $loan = $client->loans()->create([
            'monto' => $request->monto,
            'interes' => $interes, // o de donde venga
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => null,
            'estado' => 'activo',
            'folio_pagare' => $request->folio_pagare,
            'restante' => $restante
        ]);

        // Crear 14 semanas con fechas semanales desde fecha_inicio
        $fechaInicio = \Carbon\Carbon::parse($request->fecha_inicio);

        // Calcular monto total con interés del 40%
        $montoTotal = $loan->monto + ($loan->monto * 0.40);
        $montoPorSemana = round($montoTotal / 14, 2);
            
        // Semana 0 (inicio del préstamo, sin pago)
        $loan->weeks()->create([
            'numero_semana' => 0,
            'fecha_pago' => $fechaInicio,
            'monto_pago' => 0,
            'estado' => 'pendiente',
        ]);
        
        // Semanas 1 a 14 (pagos semanales)
        for ($i = 1; $i <= 14; $i++) {
            $loan->weeks()->create([
                'numero_semana' => $i,
                'fecha_pago' => $fechaInicio->copy()->addWeeks($i),
                'monto_pago' => $montoPorSemana,
                'estado' => 'pendiente',
                'restante' => $montoPorSemana
            ]);
        }

        return redirect()->route('clients.index')->with('success', 'Cliente, préstamo y semanas creados correctamente.');
    }


    public function edit(Client $client)
    {
         $user = auth()->user();
         if ($user->level != 'admin') {
        abort(403, 'No tienes permiso para editar este cliente.');
        }
        $loan = $client->loans()->latest()->first(); // prestamo mas reciente
        $users = User::all();

        return view('clients.edit', compact('client','loan','users'));
    }

   public function update(Request $request, Client $client)
    {
         $user = auth()->user();

        if ($user->level === 'admin') {

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'address' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'loan_id' => 'required|exists:loans,id',
            'user_id' => 'required|exists:users,id',
        ]);

        // Actualizar cliente
        $client->update($validated);

        // Obtener préstamo del cliente
        $loan = Loan::where('id', $validated['loan_id'])
                    ->where('client_id', $client->id)
                    ->firstOrFail();

        // Actualizar el préstamo
        $loan->update([
            'fecha_inicio' => $validated['fecha_inicio'],
            'monto' => $validated['monto'],
        ]);

        // Recalcular fechas de semanas
        $fechaInicio = \Carbon\Carbon::parse($validated['fecha_inicio']);
        $montoTotal = $loan->monto + ($loan->monto * 0.40);
        $montoPorSemana = round($montoTotal / 14, 2);

        $semanas = $loan->weeks()->orderBy('numero_semana')->get();

        foreach ($semanas as $semana) {
            if ($semana->numero_semana === 0) {
                // Semana 0: sin pago
                $semana->update([
                    'fecha_pago' => $fechaInicio,
                    'monto_pago' => 0,
                ]);
            } else {
                // Semanas 1 a 14
                $semana->update([
                    'fecha_pago' => $fechaInicio->copy()->addWeeks($semana->numero_semana),
                    'monto_pago' => $montoPorSemana,
                ]);
            }
        }
        $loan->actualizarEstado();
        return redirect()->route('clients.index')->with('success', 'Cliente y préstamo actualizados correctamente.');
    }
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente eliminado');
    }
}