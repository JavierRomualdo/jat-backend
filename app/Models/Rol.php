<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rol extends Model
{
    //
    protected $table = 'rol';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'rol', 'permiso'
    ];

    public function Persona()
    {
        # code...
        return $this->hasmany(Persona::class);
    }
}
