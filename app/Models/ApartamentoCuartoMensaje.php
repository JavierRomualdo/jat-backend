<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApartamentoCuartoMensaje extends Model
{
    //
    protected $table = 'apartamentocuartomensaje';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'apartamentocuarto_id', 'nombres','telefono', 'email', 'titulo', 
        'mensaje', 'estado'
    ];
}
