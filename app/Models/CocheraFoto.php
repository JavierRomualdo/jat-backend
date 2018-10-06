<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CocheraFoto extends Model
{
    //
    protected $table = 'cocherafoto';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'cochera_id', 'foto_id', 'estado'
    ];
}
