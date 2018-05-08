<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Local extends Model
{
    //
    protected $table = 'local';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'persona_id', 'largo', 'ancho', 'direccion', 'ubicacion', 'foto', 'descripcion'
    ];

    public function LocalServicio()
    {
        # code...
        return $this->hasmany(LocalServicio::class);
    }

    public function Persona()
    {
        # code...
        return $this->belongsto(Persona::class);
    }
}
