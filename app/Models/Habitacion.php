<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Habitacion extends Model
{
    //
    protected $table = 'habitacion';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'persona_id', 'ubigeo_id', 'codigo', 'precioadquisicion', 'preciocontrato', 'ganancia',
        'largo', 'ancho', 'direccion', 'latitud', 'longitud', 'ncamas', 'tbanio', 'descripcion', 
        'path', 'foto', 'nmensajes', 'contrato', 'estadocontrato', 'estado'
    ];

    public function Persona()
    {
        # code...
        return $this->belongsto(Persona::class);
    }

    public function HabitacionServicio()
    {
        # code...
        return $this->hasmany(HabitacionServicio::class);
    }
}
