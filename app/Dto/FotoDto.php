<?php 
namespace App\Dto;
use App\Dto\UbigeoDetalleDto;

class FotoDto
{
    public $fotocasa;
    public $fotolote;
    public $fotohabitacion;
    public $fotolocal;

    function __construct() {
        $fotocasa = null;
        $fotolote = null;
        $fotohabitacion = null;
        $fotolocal = null;
    }

    public function setFotoCasa($fotocasa) {
        $this->fotocasa = $fotocasa;
    }

    public function setFotoLote($fotolote) {
        $this->fotolote = $fotolote;
    }

    public function setFotoHabitacion($fotohabitacion) {
        $this->fotohabitacion = $fotohabitacion;
    }

    public function setFotoLocal($fotolocal) {
        $this->fotolocal = $fotolocal;
    }
}