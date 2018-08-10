<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HabitacionServicio extends Model
{
    //
    protected $table = 'habitacionservicio';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'habitacion_id', 'servicio_id', 'estado'
    ];

    public function Habitacion()
    {
        # code...
        return $this->belongsto(Habitacion::class);
    }

    public function Servicios()
    {
        # code...
        return $this->belongsto(Servicios::class);
    }
}
