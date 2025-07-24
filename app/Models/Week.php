<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
     protected $fillable = [
        'loan_id',
        'numero_semana',
        'fecha_pago',
        'monto_pago',
        'estado',
    ];
    public function loan()
{
    return $this->belongsTo(Loan::class);
}

}
