<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocalArchivo extends Model
{
    //
    protected $table = 'localarchivo';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'local_id', 'nombre', 'archivo', 'tipoarchivo', 'estado'
    ];
}
