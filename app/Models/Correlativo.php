<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correlativo extends Model
{
    use HasFactory;
    protected $fillable=[
        'serie',
        'rango_inicial',
        'rango_final',
        'tipo_documento',
        'ultimo_documento',
        'estado_id',
    ];
    
    public function estado(){
        return $this->belongsTo(Estado::class);
    }
}
