<?php 
namespace App\Dto;
class VentaDto
{
    public $id;
    public $apartamento_id = null;
    public $casa_id = null;
    public $cochera_id = null;
    public $local_id = null;
    public $lote_id = null;
    public $cliente = null;
    public $fecha = "";
    public $estado;

    function __construct() {
        $estado = true;
    }

    public function setVentaDto($id, $fecha, $estado)
    {
        # code...
        $this->id = $id;
        $this->fecha = $fecha;
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