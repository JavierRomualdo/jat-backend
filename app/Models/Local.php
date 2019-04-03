<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Local extends Model
{
    //
    protected $table = 'local';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'persona_id', 'ubigeo_id', 'habilitacionurbana_id', 'codigo', 'precioadquisicion',
        'preciocontrato', 'ganancia', 'largo', 'ancho', 'nombrehabilitacionurbana', 'direccion',
        'latitud', 'longitud', 'tbanio', 'referencia', 'descripcion', 'path', 'foto', 'nmensajes',
        'contrato', 'estadocontrato', 'estado'
    ];

    public function LocalServicio()
    {
        # code...
        return $this->hasmany(LocalServicio::class);
    }

    public function Persona()
    {
        # code...
        return $this->belongsto(Persona::class);
    }
}
