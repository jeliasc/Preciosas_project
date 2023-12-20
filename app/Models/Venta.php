<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'cliente_id',
        'mesa_id',
        'correlativo_id',
        'numero_factura',
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

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }

    public function mesa(){
        return $this->belongsTo(Mesa::class);
    }

    public function detalleVentas(){
        return $this->hasMany(DetalleVenta::class);
    }

    public function correlativo(){
        return $this->belongsTo(Correlativo::class);
    }
}
