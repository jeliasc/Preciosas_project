<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'nombre',
        'precio',
        'descripcion',
        'categoria_id',
        'proveedor_id',
        'foto_id',
        'estado_id',
    ];

    public function estado(){
        return $this->belongsTo(Estado::class);
    }

    public function foto(){
        return $this->belongsTo(Foto::class);
    }

    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }

    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }
}

