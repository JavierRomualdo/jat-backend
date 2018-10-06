<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApartamentoCuarto extends Model
{
    //
    protected $table = 'apartamentocuarto';
    protected $primarykey = 'id';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'apartamento_id', 'persona_id', 'precio', 'largo', 'ancho', 
        'piso', 'nbanios', 'descripcion', 'path', 'foto', 'nmensajes', 'estado'
    ];
}
