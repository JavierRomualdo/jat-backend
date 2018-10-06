<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApartamentoFoto extends Model
{
    //
    protected $table = 'apartamentofoto';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'apartamento_id', 'foto_id', 'estado'
    ];
}
