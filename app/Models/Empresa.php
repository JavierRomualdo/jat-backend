<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    //
    protected $table = 'empresa';
    protected $primarykey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id', 'nombre', 'ruc', 'ubicacion', 'direccion', 
        'telefono', 'correo', 'nombrefoto', 'foto', 'estado',
    ];
}
