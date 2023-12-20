<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'proveedor_id',
        'fecha',
        'tax',
        'total',
        'estado_id',
    ];
    public function estado(){
        return $this->belongsTo(Estado::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }

    public function detalleCompras(){
        return $this->hasMany(DetalleCompra::class);
    }
}
