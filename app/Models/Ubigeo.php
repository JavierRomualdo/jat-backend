<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ubigeo extends Model
{
    //
    protected $table = 'ubigeo';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id','tipoubigeo_id', 'habilitacionurbana_id', 'ubigeo', 'codigo', 'estado'
    ];
}
