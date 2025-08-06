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

    // Registrar pago completo de la semana (restante queda en 0)
    $week->update([
        'estado' => 'pagado',
        'restante' => 0,  // Ya no queda nada por pagar en esta semana
    ]);

    // Restar el monto restante de esta semana al restante total del préstamo
    $nuevoRestante = max(0, $loan->restante - $week->restante);
    $loan->restante = $nuevoRestante;

    // Verificar si todas las semanas están pagadas
    $semanasPendientes = $loan->weeks()->where('estado', '!=', 'pagado')->count();

    if ($semanasPendientes === 0) {
        $loan->estado = 'pagado';
    }

    $loan->save();

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

    // Mostrar formulario para ingresar abono
public function showAbonoForm(Week $week)
{
    return view('weeks.abono', compact('week'));
}

// Procesar el abono
public function procesarAbono(Request $request, Week $week)
{
    $loan = $week->loan;

    // Validar que no se abone más del total restante del préstamo
    $request->validate([
        'abono' => ['required', 'numeric', 'min:0.01', 'max:' . $loan->restante],
    ]);

    $abono = $request->abono;

    // Obtener semanas pendientes del préstamo ordenadas
    $semanasPendientes = $loan->weeks()
        ->where('estado', '!=', 'pagado')
        ->orderBy('numero_semana')
        ->get();

    foreach ($semanasPendientes as $semana) {
        if ($abono <= 0) break;

        $montoAbonado = $semana->monto_pago - $semana->restante;
        $restanteSemana = $semana->monto_pago - $montoAbonado;

        if ($restanteSemana <= 0) continue;

        $aplicar = min($abono, $restanteSemana);
        $nuevoRestante = $restanteSemana - $aplicar;

        // Actualizar semana
        $semana->restante = $nuevoRestante;

        if ($nuevoRestante <= 0) {
            $semana->estado = 'pagado';
        }

        $semana->save();

        // Descontar del total
        $abono -= $aplicar;
    }

    // Actualizar préstamo
    $loan->restante -= $request->abono;
    if ($loan->restante <= 0) {
        $loan->restante = 0;
        $loan->estado = 'pagado';
    }
    $loan->save();

    return redirect()->route('loans.show', $loan->id)->with('success', 'Abono registrado exitosamente.');
}



}
