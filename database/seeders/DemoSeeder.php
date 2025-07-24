<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Models\Loan;
use App\Models\Week;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuarios
        $admin = User::create([
            'name' => 'Admin General',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'level' => 'admin',
        ]);

        $user = User::create([
            'name' => 'Usuario Prueba',
            'email' => 'usuario@demo.com',
            'password' => Hash::make('password'),
            'level' => 'user',
        ]);

        $usuarios = [$admin, $user];

        foreach ($usuarios as $u) {
            // Crear 5 clientes por usuario
            for ($i = 1; $i <= 5; $i++) {
                $client = Client::create([
                    'user_id' => $u->id,
                    'nombre' => "Cliente {$i} de {$u->name}",
                    'email' => "cliente{$i}_{$u->id}@mail.com",
                    'telefono' => '555-123-456' . $i,
                    'monto' => 10000 + ($i * 500),
                ]);

                // Crear 1 préstamo por cliente con diferentes estados
                $estado = match ($i % 4) {
                    0 => 'pagado',
                    1 => 'activo',
                    2 => 'vencido',
                    3 => 'retraso'
                };

                $fecha_inicio = Carbon::now()->subWeeks(14);

                $loan = Loan::create([
                    'client_id' => $client->id,
                    'monto' => 2000 + ($i * 100),
                    'interes' => 10,
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_inicio->copy()->addWeeks(14),
                    'estado' => $estado,
                ]);

                // Crear 14 semanas de pagos
                for ($w = 1; $w <= 14; $w++) {
                    $fecha_pago = $fecha_inicio->copy()->addWeeks($w);
                    $estado_pago = 'pendiente';

                    if ($estado === 'pagado') {
                        $estado_pago = 'pagado';
                    } elseif ($estado === 'vencido' && $w < 10) {
                        $estado_pago = 'pagado';
                    } elseif ($estado === 'retraso' && $w == 13) {
                        $estado_pago = 'retraso';
                    } elseif ($fecha_pago->isPast() && $estado !== 'pagado') {
                        $estado_pago = 'vencido';
                    }

                    Week::create([
                        'loan_id' => $loan->id,
                        'numero_semana' => $w,
                        'fecha_pago' => $fecha_pago,
                        'monto_pago' => round($loan->monto / 14, 2),
                        'estado' => $estado_pago,
                    ]);
                }
            }
        }
    }
}
