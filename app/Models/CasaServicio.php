<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CasaServicio extends Model
{
    //
    protected $table = 'casaservicio';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'casa_id', 'servicio_id'
    ];

    public function Casa()
    {
        # code...
        return $this->belongsto(Casa::class);
    }

    public function Servicios()
    {
        # code...
        return $this->belongsto(Servicios::class);
    }
}
