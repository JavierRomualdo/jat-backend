<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocalServicio extends Model
{
    //
    protected $table = 'localservicio';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deteled_atc'];

    protected $fillable = [
        'id', 'local_id', 'servicio_id'
    ];

    public function Local()
    {
        # code...
        return $this->belongsto(Local::class);
    }

    public function Servicios()
    {
        # code...
        return $this->belongsto(Servicios::class);
    }
}
