<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HabitacionFoto extends Model
{
    //
    protected $table = 'habitacionfoto';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'habitacion_id', 'foto_id', 'estado'
    ];
}
