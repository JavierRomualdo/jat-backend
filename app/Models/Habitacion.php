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
        'id', 'persona_id', 'precio', 'largo', 'ancho', 'ubicacion', 'direccion', 'camas',
        'tbaño', 'descripcion', 'foto', 'estado'
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
