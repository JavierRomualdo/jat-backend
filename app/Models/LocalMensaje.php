<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocalMensaje extends Model
{
    //
    protected $table = 'localmensaje';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'local_id', 'nombres','telefono', 'email', 'titulo', 'mensaje', 'estado'
    ];
}
