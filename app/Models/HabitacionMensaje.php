<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HabitacionMensaje extends Model
{
    //
    protected $table = 'habitacionmensaje';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'habitacion_id', 'nombres','telefono', 'email', 'titulo', 'mensaje', 'estado'
    ];
}
