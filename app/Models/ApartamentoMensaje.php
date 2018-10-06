<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApartamentoMensaje extends Model
{
    //
    protected $table = 'apartamentomensaje';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'apartamento_id', 'nombres','telefono', 'email', 'titulo', 'mensaje', 'estado'
    ];
}
