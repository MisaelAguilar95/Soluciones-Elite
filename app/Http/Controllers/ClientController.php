<?php

namespace App\Http\Controllers;

use App\Models\Client;
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
        $clients = Client::with(['loans.weeks', 'user'])->paginate(10);
    } else {
        $clients = $user->clients()->with(['loans.weeks', 'user'])->paginate(10);
    }

    // Actualizar estado de préstamos según semanas
    foreach ($clients as $client) {
        foreach ($client->loans as $loan) {
            $loan->actualizarEstado();
        }
    }

    return view('clients.index', compact('clients'));
}




    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'monto' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
        ]);

        $client = auth()->user()->clients()->create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'monto' => $request->monto,
        ]);
        $interes = $request->monto * .4;
        
        $loan = $client->loans()->create([
            'monto' => $request->monto,
            'interes' => $interes, // o de donde venga
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => null,
            'estado' => 'activo',
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
            ]);
        }

        return redirect()->route('clients.index')->with('success', 'Cliente, préstamo y semanas creados correctamente.');
    }


    public function edit(Client $client)
    {
         if (auth()->id() !== $client->user_id) {
        abort(403, 'No tienes permiso para editar este cliente.');
    }

    return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $this->authorize('update', $client);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'monto' => 'required|numeric|min:0',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Cliente actualizado correctamente');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente eliminado');
    }
}