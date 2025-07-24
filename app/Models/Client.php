<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
     protected $fillable = [
        'user_id', 'nombre', 'email', 'telefono', 'monto'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

}
