<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CasaMensaje extends Model
{
    //
    protected $table = 'casamensaje';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'casa_id', 'nombres','telefono', 'email', 'titulo', 'mensaje', 'estado'
    ];
}
