<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
     protected $fillable = [
        'user_id', 'nombre', 'email', 'telefono', 'monto', 'address', 'aval', 'curp'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function latestLoan()
{
    return $this->hasOne(Loan::class)->latestOfMany();
}

}
