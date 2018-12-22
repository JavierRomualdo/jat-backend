<?php 
namespace App\Dto;
class AlquilerDto
{
    public $id;
    public $apartamento_id = null;
    public $casa_id = null;
    public $cochera_id = null;
    public $local_id = null;
    public $lote_id = null;
    public $cliente = null;
    public $fecha = "";
    public $fechacontrato = "";
    public $estado;

    function __construct() {
        $estado = true;
    }

    public function setAlquilerDto($id, $fecha, $fechacontrato, $estado)
    {
        # code...
        $this->id = $id;
        $this->fecha = $fecha;
        $this->fechacontrato = $fechacontrato;
        $this->estado = $estado;
    }

    public function setApartamento($apartamento)
    {
        # code...
       $this->apartamento_id = $apartamento; 
    }

    public function setCasa($casa)
    {
        # code...
       $this->casa_id = $casa; 
    }

    public function setCochera($cochera)
    {
        # code...
        $this->cochera_id = $cochera;
    }

    public function setLocal($local)
    {
        # code...
        $this->local_id = $local;
    }

    public function setLote($lote)
    {
        # code...
        $this->lote_id = $lote;
    }

    public function setCliente($cliente)
    {
        # code...
        $this->cliente = $cliente;
    }
}
?>