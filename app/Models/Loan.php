<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
     use HasFactory;

    protected $fillable = [
        'client_id',  // si usas llave foránea explícita
        'monto',
        'semanas',
        'fecha_inicio',    // si tienes este campo
        'estado',
        'interes',
        'folio_pagare',
        'restante'// otros campos que quieras permitir llenar masivamente
    ];
    // app/Models/Loan.php
    public function montoRestante()
    {
        $pagado = $this->weeks()->where('estado', 'pagado')->sum('monto_pago');
        $restante = ($this->monto + $this->interes) - $pagado;
        return $restante > 0 ? $restante : 0;
    }
    
   public function actualizarEstado()
{
    $now = now();
    $pagos = $this->weeks;

    $pagados = 0;
    $retrasos = 0;

    // Primero actualizar semanas según fecha y pagos
    foreach ($pagos as $pago) {
        if ($pago->estado === 'pagado') {
            $pagados++;
            continue;
        }

        if ($pago->fecha_pago < $now) {
            $pago->estado = 'retraso';
            $retrasos++;
        } else {
            $pago->estado = 'pendiente';
        }

        $pago->save();
    }

    $total = $pagos->count();

    // Determinar estado final del préstamo
    if ($retrasos >= 10) {
        $this->estado = 'vencido';
    } elseif ($pagados === $total) {
        $this->estado = 'pagado';
    } elseif ($retrasos > 0) {
        $this->estado = 'retraso';
    } else {
        $this->estado = 'activo';
    }

    $this->save();

    // 🔹 Actualizar semanas para reflejar "vencido" si el préstamo está vencido
    if ($this->estado === 'vencido') {
        $this->weeks()->where('estado', '!=', 'pagado')
                     ->update(['estado' => 'vencido']);
    }
}




    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
    public function weeks()
    {
        return $this->hasMany(Week::class);
    }

}
