<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApartamentoServicio extends Model
{
    //
    protected $table = 'apartamentoservicio';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'apartamento_id', 'servicio_id', 'estado'
    ];
}
