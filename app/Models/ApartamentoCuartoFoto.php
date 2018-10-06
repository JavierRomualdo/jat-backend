<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApartamentoCuartoFoto extends Model
{
    //
    protected $table = 'apartamentocuartofoto';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'apartamentocuarto_id', 'foto_id', 'estado'
    ];
}
