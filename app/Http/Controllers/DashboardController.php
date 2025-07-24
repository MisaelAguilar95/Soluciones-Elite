<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Loan;
use App\Models\Week;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->level === 'admin') {
            // Admin ve todo
            $clientsQuery = Client::query();
            $loansQuery = Loan::query();
            $weeksQuery = Week::query();
        } else {
            // Usuario normal ve solo sus clientes y datos relacionados
            $clientsQuery = Client::where('user_id', $user->id);
            $loansQuery = Loan::whereHas('client', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
            $weeksQuery = Week::whereHas('loan.client', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        $totalClients = $clientsQuery->count();

        $totalLoansActive = (clone $loansQuery)->where('estado', 'activo')->count();
        $totalLoansPaid = (clone $loansQuery)->where('estado', 'pagado')->count();
        $totalAmountLoaned = (clone $loansQuery)->sum('monto');


        $loansCountByStatus = (clone $loansQuery)
            ->selectRaw('estado, COUNT(*) as count')
            ->groupBy('estado')
            ->pluck('count', 'estado')
            ->toArray();
            
        $paymentsCountByStatus = (clone $weeksQuery)
            ->selectRaw('estado, COUNT(*) as count')
            ->groupBy('estado')
            ->pluck('count', 'estado')
            ->toArray();

        return view('dashboard', compact(
            'totalClients',
            'totalLoansActive',
            'totalLoansPaid',
            'totalAmountLoaned',
            'loansCountByStatus',
            'paymentsCountByStatus'
        ));
    }
}
