<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HabilitacionUrbana extends Model
{
    //
    protected $table = 'habilitacionurbana';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'nombre', 'siglas', 'estado'
    ];
}
