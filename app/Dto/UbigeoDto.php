<?php 
namespace App\Dto;
class UbigeoDto
{
    public $id;
    public $tipoubigeo_id;
    public $habilitacionurbana_id;
    public $ubigeo;
    public $rutaubigeo;
    public $codigo;
    public $siglas;
    public $estado;

    function __construct() {
        $estado = true;
    }

    public function setUbigeo($ubigeo) {
        $this->id = $ubigeo->id;
        // $this->rol_id = $persona->rol_id;
        $this->ubigeo = $ubigeo->ubigeo;
        $this->rutaubigeo = $ubigeo->rutaubigeo;
        $this->codigo = $ubigeo->codigo;
        $this->codigo = $ubigeo->siglas;
        $this->estado = $ubigeo->estado;
    }

    public function setTipoUbigeo($tipoubigeo) {
        $this->tipoubigeo_id = $tipoubigeo;
    }

    public function setHabilitacionUrbana($habilitacionurbana) {
        $this->habilitacionurbana_id = $habilitacionurbana;
    }
}
?>