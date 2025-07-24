<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Week;
use Carbon\Carbon;

class RevisarSemanasVencidas extends Command
{
    protected $signature = 'semanas:revisar-vencidas';
    protected $description = 'Actualiza las semanas pendientes cuya fecha ya pasó a estado "vencido"';

    public function handle()
    {
        $hoy = Carbon::today();

        $afectadas = Week::where('estado', 'pendiente')
            ->whereDate('fecha_pago', '<', $hoy)
            ->update(['estado' => 'vencido']);

        $this->info("Semanas vencidas actualizadas: $afectadas");
    }
}
