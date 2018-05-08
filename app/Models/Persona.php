<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    //
    protected $table = 'persona';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'nombres', 'telefono', 'correo', 'direccion', 'ubicacion', 'rol_id'
    ];

    public function Casa()
    {
        # code...
        return $this->hasmany(Casa::class);
    }

    public function Habitacion()
    {
        # code...
        return $this->hasmany(Habitacion::class);
    }

    public function Local()
    {
        # code...
        return $this->hasmany(Local::class);
    }

    public function Lote()
    {
        # code...
        return $this->hasmany(Lote::class);
    }

    public function Rol()
    {
        # code...
        return $this->belongsto(Rol::class);
    }
}
