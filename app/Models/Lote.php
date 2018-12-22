<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lote extends Model
{
    //
    protected $table = 'lote';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'persona_id', 'ubigeo_id', 'codigo', 'preciocompra', 'preciocontrato', 'ganancia',
        'largo', 'ancho', 'direccion', 'descripcion', 'path', 'foto', 'nmensajes', 'contrato', 
        'estadocontrato', 'estado'
    ];

    public function Persona()
    {
        # code...
        return $this->belongsto(Persona::class);
    }
}
