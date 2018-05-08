<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicios extends Model
{
    //
    protected $table = 'servicios';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'servicio', 'detalle'
    ];

    public function CasaServicio()
    {
        # code...
        return $this->hasmany(CasaServicio::class);
    }

    public function HabitacionServicio()
    {
        # code...
        return $this->hasmany(HabitacionServicio::class);
    }

    public function LocalServicio()
    {
        # code...
        return $this->hasmany(LocalServicio::class);
    }
}
