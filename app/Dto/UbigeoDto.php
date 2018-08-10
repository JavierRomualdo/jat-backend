<?php 
namespace App\Dto;
class UbigeoDto
{
    public $id;
    public $tipoubigeo_id;
    public $ubigeo;
    public $codigo;
    public $estado;

    function __construct() {
        $estado = true;
    }

    public function setUbigeo($ubigeo) {
        $this->id = $ubigeo->id;
        // $this->rol_id = $persona->rol_id;
        $this->ubigeo = $ubigeo->ubigeo;
        $this->codigo = $ubigeo->codigo;
        $this->estado = $ubigeo->estado;
    }

    public function setTipoUbigeo($tipoubigeo) {
        $this->tipoubigeo_id = $tipoubigeo;
    }
}
?>