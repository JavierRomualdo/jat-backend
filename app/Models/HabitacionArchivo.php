<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HabitacionArchivo extends Model
{
    //
    protected $table = 'habitacionarchivo';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'habitacion_id', 'nombre', 'archivo', 'tipoarchivo', 'estado'
    ];
}
