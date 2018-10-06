<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CocheraServicio extends Model
{
    //
    protected $table = 'cocheraservicio';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'cochera_id', 'servicio_id', 'estado'
    ];
}
