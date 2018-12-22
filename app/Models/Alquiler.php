<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alquiler extends Model
{
    //
    protected $table = 'alquiler';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'apartamento_id', 'casa_id', 'cochera_id', 'local_id', 'lote_id', 'persona_id', 
        'ubigeo_id', 'fecha', 'fechacontrato', 'estado'
    ];

    public function Persona()
    {
        # code...
        return $this->belongsto(Persona::class);
    }
}
