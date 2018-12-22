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
        'id', 'persona_id', 'ubigeo_id', 'codigo', 'preciocompra', 'preciocontrato', 'ganancia',
        'largo', 'ancho', 'direccion', 'npisos', 'ncuartos', 'nbanios', 'tjardin', 'tcochera', 
        'descripcion', 'path', 'foto', 'nmensajes', 'contrato', 'estadocontrato', 'estado'
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
