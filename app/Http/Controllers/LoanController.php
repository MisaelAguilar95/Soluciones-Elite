<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Controllers\Client;
use App\Models\Loan;
use App\Models\Client;
use App\Exports\LoansExport;
use Maatwebsite\Excel\Facades\Excel;



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
        'fecha_inicio' => 'required|date',
    ]);

    $client = Client::findOrFail($request->client_id);

    // Puedes descomentar si quieres impedir préstamos activos múltiples
    // $existePrestamoPendiente = $client->loans()->where('estado', 'activo')->exists();
    // if ($existePrestamoPendiente) {
    //     return redirect()->back()->with('error', 'Este cliente ya tiene un préstamo activo.');
    // }

    // Calcular interés y monto total
    $interes = $request->monto * 0.40;
    $restante = $request->monto + $interes;

    // Crear préstamo
    $loan = $client->loans()->create([
        'monto' => $request->monto,
        'interes' => $interes,
        'fecha_inicio' => $request->fecha_inicio,
        'fecha_fin' => null,
        'estado' => 'activo', // se actualizará más abajo
        'restante' => $restante,
        'folio_pagare' => $request->folio_pagare ?? null,
    ]);

    // Crear semanas
    $fechaInicio = \Carbon\Carbon::parse($request->fecha_inicio);
    $montoPorSemana = round($restante / 14, 2);
    $now = now();

    // Semana 0 (inicio del préstamo, sin pago)
    $loan->weeks()->create([
        'numero_semana' => 0,
        'fecha_pago' => $fechaInicio,
        'monto_pago' => 0,
        'estado' => 'pendiente',
        'restante' => 0
    ]);

    // Semanas 1 a 14 (pagos semanales)
    for ($i = 1; $i <= 14; $i++) {
        $fechaSemana = $fechaInicio->copy()->addWeeks($i);
        $estadoSemana = $fechaSemana < $now ? 'retraso' : 'pendiente';

        $loan->weeks()->create([
            'numero_semana' => $i,
            'fecha_pago' => $fechaSemana,
            'monto_pago' => $montoPorSemana,
            'estado' => $estadoSemana,
            'restante' => $montoPorSemana
        ]);
    }

    // Actualizar estado del préstamo según semanas
    $loan->actualizarEstado();

    return redirect()->route('clients.loans', $client)
                     ->with('success', 'Préstamo y semanas creados con éxito.');
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
        $tienePrestamoActivo = $client->loans()
        ->whereIn('estado', ['activo', 'retraso'])
        ->exists();


        return view('loans.index_by_client', compact('client', 'loans', 'tienePrestamoActivo'));
    }

    public function reporte()
    {
        // Obtener préstamos con sus clientes, semanas y usuarios (creadores)
        $loans = Loan::with(['client', 'weeks', 'client.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('report.loans', compact('loans'));
    }
    public function export()
{
    return Excel::download(new LoansExport, 'reporte_prestamos.xlsx');
}

}
