<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nit',
        'nombre',
        'direccion',
        'telefono',
        'email',
        'estado_id',
    ];

    public function estado(){
        return $this->belongsTo(Estado::class);
    }
}
