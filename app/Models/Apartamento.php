<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apartamento extends Model
{
    //
    protected $table = 'apartamento';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'ubigeo_id', 'codigo', 'preciocompra', 'preciocontrato', 'ganancia',
        'largo', 'ancho', 'npisos', 'direccion', 'tcochera', 'descripcion', 'path', 
        'foto', 'nmensajes', 'contrato', 'estadocontrato', 'estado'
    ];
}
