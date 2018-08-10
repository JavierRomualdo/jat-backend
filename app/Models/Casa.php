<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Casa extends Model
{
    //
    protected $table = 'casa';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'persona_id', 'precio', 'largo', 'ancho', 'ubicacion', 'direccion', 'npisos', 
        'ncuartos', 'nbanios', 'tjardin', 'tcochera', 'descripcion', 'path', 'foto', 'estado'
    ];

    public function casaservicios()
    {
        # code...
        return $this->hasmany(CasaServicio::class);
    }

    public function Persona()
    {
        # code...
        return $this->belongsto(Persona::class);
    }

}
