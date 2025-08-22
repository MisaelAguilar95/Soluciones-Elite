<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use App\Models\User;

class ReporteController extends Controller
{
    // Página inicial del módulo de reportes
    public function index()
    {
        $usuarios = User::all(); // para poblar el select
        return view('report.index', compact('usuarios'));
    }

    public function buscar(Request $request)
    {
        $request->validate([
            'usuario_id'   => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'estado'       => 'nullable|in:pagado,retraso,vencido',
        ]);

        $usuarioId   = $request->usuario_id;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin    = $request->fecha_fin;
        $estado      = $request->estado;

        // Query base con relaciones y semanas filtradas por fecha y estado
        $prestamosQuery = Loan::with(['client', 'weeks' => function($q) use ($estado, $fechaInicio, $fechaFin) {
            $q->whereBetween('fecha_pago', [$fechaInicio, $fechaFin]);
            if ($estado) $q->where('estado', $estado);
        }]);

        // Filtrar por usuario solo si no se selecciona "Todos"
        if ($usuarioId != 'todos') {
            $prestamosQuery->whereHas('client', function($q) use ($usuarioId) {
                $q->where('user_id', $usuarioId);
            });
        }

        $prestamos = $prestamosQuery->get();

        // Inicializar totales
        $totalPagado      = 0;
        $totalPendiente   = 0;
        $totalPrestamos   = $prestamos->count();
        $semanasPagadas   = 0;

        foreach ($prestamos as $loan) {
            foreach ($loan->weeks as $week) {
                if ($week->estado === 'pagado') {
                    $totalPagado += $week->monto_pago;
                    $semanasPagadas++;
                } else {
                    $totalPendiente += $week->restante; // monto pendiente exacto
                }
            }
        }

        $usuarios = User::all();

        return view('report.index', compact(
            'prestamos', 'usuarios', 'usuarioId', 'fechaInicio', 'fechaFin', 'estado',
            'totalPagado', 'totalPendiente', 'totalPrestamos', 'semanasPagadas'
        ));
    }


}
