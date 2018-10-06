<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteMensaje extends Model
{
    //
    protected $table = 'lotemensaje';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'lote_id', 'nombres','telefono', 'email', 'titulo', 'mensaje', 'estado'
    ];
}
