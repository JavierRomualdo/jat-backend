<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cochera extends Model
{
    //
    protected $table = 'cochera';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'persona_id', 'ubigeo_id','precio', 'largo', 'ancho', 'direccion', 
        'descripcion', 'path', 'foto', 'nmensajes', 'tiposervicio', 'estado'
    ];
}
