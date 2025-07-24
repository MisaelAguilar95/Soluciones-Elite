<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Controllers\Client;
use App\Models\Loan;
use App\Models\Client;



class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'monto' => 'required|numeric|min:1',
        ]);

        $client = Client::findOrFail($request->client_id);

        $existePrestamoPendiente = $client->loans()->where('estado', 'activo')->exists();
        if ($existePrestamoPendiente) {
            return redirect()->back()->with('error', 'Este cliente ya tiene un préstamo activo.');
        }
        
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

        return redirect()->route('clients.loans', $client)->with('success', 'Préstamo y semanas creados con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan->load('weeks', 'client'); // Carga las semanas y el cliente relacionado
        return view('loans.show', compact('loan'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

   

    public function weeks(Loan $loan)
    {
        $weeks = $loan->weeks()->orderBy('numero_semana')->get();

        return view('loans.weeks', compact('loan', 'weeks'));
    }

    public function indexByClient(Client $client)
    {
         // Por paginación, por ejemplo 10 por página:
        $loans = $client->loans()->latest()->paginate(10);

        // Verificar si hay un préstamo activo
        $tienePrestamoActivo = $client->loans()->where('estado', 'activo')->exists();

        return view('loans.index_by_client', compact('client', 'loans', 'tienePrestamoActivo'));
    }



}
