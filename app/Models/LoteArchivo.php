<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteArchivo extends Model
{
    //
    protected $table = 'lotearchivo';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'lote_id', 'nombre', 'archivo', 'tipoarchivo', 'estado'
    ];
}
