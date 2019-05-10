<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CocheraArchivo extends Model
{
    //
    protected $table = 'cocheraarchivo';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'cochera_id', 'nombre', 'archivo', 'tipoarchivo', 'estado'
    ];
}
