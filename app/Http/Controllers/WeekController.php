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

    // Registrar pago completo de la semana
    $week->update([
        'restante' => 0,
        'estado'  => 'pagado', // ✅ usar la columna correcta
    ]);

    // Recalcular restante total del préstamo
    $loan->restante = $loan->weeks()->sum('restante');

    // Actualizar estado del préstamo
    $semanasPendientes = $loan->weeks()->where('restante', '>', 0)->count();
    $loan->estado = $semanasPendientes > 0 ? 'activo' : 'pagado';
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
    $request->validate([
        'fecha_pago' => 'required|date',
        // El monto a pagar no se puede cambiar
        // 'monto_pago' => 'required|numeric|min:0',
        'restante' => 'required|numeric|min:0|max:' . $week->monto_pago,
    ]);

    $week->fecha_pago = $request->fecha_pago;
    // No modificamos monto_pago, queda igual
    $week->restante = $request->restante;

    // Actualizar estado según restante
    if ($week->restante <= 0) {
        $week->estado = 'pagado';
        $week->restante = 0;
    } else {
        $week->estado = 'pendiente';
    }

    $week->save();

    // Recalcular restante total del préstamo
    $loan = $week->loan;
    $loan->restante = $loan->weeks()->sum('restante');

    // Actualizar estado del préstamo
    $semanasPendientes = $loan->weeks()->where('restante', '>', 0)->count();
    $loan->estado = $semanasPendientes > 0 ? 'activo' : 'pagado';
    $loan->save();

    return redirect()
        ->route('loans.show', $week->loan_id)
        ->with('success', 'Pago actualizado correctamente');
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
