<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\User;
use App\Models\Week;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function index()
    {
        if(auth()->user()->level === 'admin') {
            // Admin ve todos los usuarios
            $usuarios = User::where('level', 'user')->get();
        } else {
            // Usuario normal solo ve su propio usuario
            $usuarios = collect([auth()->user()]);
        }

        return view('report.index', compact('usuarios'));
    }


    public function buscar(Request $request)
    {
        $request->validate([
            'usuario_id'   => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'estado'       => 'nullable|in:pagado,retraso,vencido',
            'tipo_reporte' => 'required|in:general,fecha',
        ]);

        $usuarioId   = $request->usuario_id;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin    = $request->fecha_fin;
        $estado      = $request->estado;
        $tipoReporte = $request->tipo_reporte;

        $usuarios = User::all();

        if ($tipoReporte === 'general') {
            // Reporte General (igual que antes)
            $prestamosQuery = Loan::with(['client', 'weeks' => function($q) use ($estado, $fechaInicio, $fechaFin) {
                $q->whereBetween('fecha_pago', [$fechaInicio, $fechaFin]);
                if ($estado) $q->where('estado', $estado);
            }]);

            if ($usuarioId != 'todos') {
                $prestamosQuery->whereHas('client', function($q) use ($usuarioId) {
                    $q->where('user_id', $usuarioId);
                });
            }

            $prestamos = $prestamosQuery->get();

            $totalPagado    = 0;
            $totalPendiente = 0;
            $totalPrestamos = $prestamos->count();
            $semanasPagadas = 0;

            foreach ($prestamos as $loan) {
                foreach ($loan->weeks as $week) {
                    if ($week->estado === 'pagado') {
                        $totalPagado += $week->monto_pago;
                        $semanasPagadas++;
                    } else {
                        $totalPendiente += $week->restante;
                    }
                }
            }

            return view('report.index', compact(
                'prestamos','usuarios','usuarioId','fechaInicio','fechaFin','estado','tipoReporte',
                'totalPagado','totalPendiente','totalPrestamos','semanasPagadas'
            ));

        } else {
            // Reporte por Fecha Real — SOLO pagos realmente realizados
            $weeks = Week::with('loan.client')
                ->where('estado', 'pagado') // Solo los pagos realizados
                ->whereBetween('updated_at', [$fechaInicio.' 00:00:00', $fechaFin.' 23:59:59']);

            if ($usuarioId != 'todos') {
                $weeks->whereHas('loan.client', function($q) use ($usuarioId){
                    $q->where('user_id', $usuarioId);
                });
            }

            $weeks = $weeks->get();

            // Agrupar por semana ISO con rango de fechas
            $pagosPorSemana = $weeks->groupBy(function($week){
                $carbon = Carbon::parse($week->updated_at);
                $startOfWeek = $carbon->startOfWeek(Carbon::MONDAY)->format('d/m/Y');
                $endOfWeek   = $carbon->endOfWeek(Carbon::SUNDAY)->format('d/m/Y');
                return "Semana {$carbon->isoWeek} ({$startOfWeek} - {$endOfWeek})";
            });

            // Agrupar por cliente y préstamo dentro de cada semana
            $detallePorSemana = [];
            foreach ($pagosPorSemana as $semana => $weeksEnSemana) {
                $detallePorSemana[$semana] = $weeksEnSemana->groupBy(function($week){
                    return $week->loan->client->nombre . " (Préstamo #{$week->loan->id})";
                });
            }

            $totalRecibido = $weeks->sum('monto_pago');

            return view('report.index', compact(
                'pagosPorSemana','detallePorSemana','usuarios','usuarioId','fechaInicio','fechaFin','tipoReporte','totalRecibido'
            ));
        }
    }
}
