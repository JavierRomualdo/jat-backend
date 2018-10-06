<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CocheraMensaje extends Model
{
    //
    protected $table = 'cocheramensaje';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'cochera_id', 'nombres','telefono', 'email', 'titulo', 'mensaje', 'estado'
    ];
}
