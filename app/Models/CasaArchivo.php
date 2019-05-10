<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CasaArchivo extends Model
{
    //
    protected $table = 'casaarchivo';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'casa_id', 'nombre', 'archivo', 'tipoarchivo', 'estado'
    ];
}
