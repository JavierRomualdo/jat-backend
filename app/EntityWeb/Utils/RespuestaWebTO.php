<?php 
namespace App\EntityWeb\Utils;

class RespuestaWebTO
{
    public $estadoOperacion; // EXITO ERROR ADVERTENCIA SIN_ACCESO
    public $extraInfo;
    public $operacionMensaje;

    function __construct() {
        $this->estadoOperacion = null;
        $this->extraInfo = null;
        $this->operacionMensaje = null;
    }

    public function setEstadoOperacion($estadoOperacion)
    {
        # code...
        $this->estadoOperacion = $estadoOperacion;
    }

    public function setExtraInfo($data)
    {
        # code...
        $this->extraInfo = $data;
    }

    public function setOperacionMensaje($operacionMensaje)
    {
        # code...
        $this->operacionMensaje = $operacionMensaje;
    }
}