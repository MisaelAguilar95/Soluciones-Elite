<?php

namespace App\Http\Controllers;
use App\Models\Week;
use Illuminate\Http\Request;

class WeekController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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

    public function pagar(Week $week)
    {
        $loan = $week->loan;

        // Actualizar la semana a pagado
        $week->update(['estado' => 'pagado']);

        // Verificar si todas las semanas del préstamo están pagadas
        $semanasPendientes = $loan->weeks()->where('estado', '!=', 'pagado')->count();

        if ($semanasPendientes === 0) {
            // Cambiar estado del préstamo a pagado
            $loan->update(['estado' => 'pagado']);
        }

        return back()->with('success', 'Semana marcada como pagada.');
    }

    public function edit(Week $week)
    {
        if (auth()->user()->level !== 'admin') {
            abort(403);
        }
        return view('weeks.edit', compact('week'));
    }

    public function update(Request $request, Week $week)
    {
        if (auth()->user()->level !== 'admin') {
            abort(403);
        }

        $request->validate([
            'fecha_pago' => 'required|date',
            'monto_pago' => 'required|numeric|min:0',
        ]);

        $week->update($request->only('fecha_pago', 'monto_pago'));

        return redirect()->route('loans.weeks', $week->loan_id)->with('success', 'Semana actualizada correctamente.');
    }
}
