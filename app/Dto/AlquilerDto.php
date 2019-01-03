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
    public $fechadesde = "";
    public $fechahasta = "";
    public $estado;

    function __construct() {
        $estado = true;
    }

    public function setAlquilerDto($id, $fechadesde, $fechahasta, $estado)
    {
        # code...
        $this->id = $id;
        $this->fechadesde = $fechadesde;
        $this->fechahasta = $fechahasta;
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

    public function setHabitacion($habitacion)
    {
        # code...
        $this->habitacion_id = $habitacion;
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