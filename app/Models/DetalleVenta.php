<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $fillable=[
        'venta_id',
        'producto_id',
        'cantidad',
        'precio',
        'descuento',
        'extra',
        'comentario',
        'estado_id',
    ];

    public function producto(){
        return $this->belongsTo(Producto::class);
    }

    public function venta(){
        return $this->belongsTo(Venta::class);
    }
    
    public function estado(){
        return $this->belongsTo(Estado::class);
    }
}
