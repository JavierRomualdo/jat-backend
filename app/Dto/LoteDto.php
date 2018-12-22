<?php 
namespace App\Dto;
use App\Dto\UbigeoDetalleDto;

class LoteDto
{
    public $id;
    public $persona_id;
    public $ubigeo_id;
    public $codigo;
    public $preciocompra;
    public $preciocontrato;
    public $ganancia;
    public $largo;
    public $ancho;
    public $ubicacion;
    public $direccion;
    public $descripcion;
    public $foto;
    public $path;
    public $contrato;
    public $estadocontrato;
    public $estado;
    // public $nmensajes;
    public $lotepersonaList = [];
    public $ubigeo;
    public $fotosList;

    function __construct() {
        $descripcion = null;
        $path = null;
        $foto = null;
        $estado = true;
        $ubigeo = new UbigeoDetalleDto();
    }

    public function setLote($lote) {
        $this->id = $lote->id;
        // $this->rol_id = $persona->rol_id;
        $this->codigo = $lote->codigo;
        $this->preciocompra = $lote->preciocompra;
        $this->preciocontrato = $lote->preciocontrato;
        $this->ganancia = $lote->ganancia;
        $this->largo = $lote->largo;
        $this->ancho = $lote->ancho;
        $this->ubicacion = $lote->ubicacion;
        $this->direccion = $lote->direccion;
        $this->descripcion = $lote->descripcion;
        $this->foto = $lote->foto;
        $this->path = $lote->path;
        $this->contrato = $lote->contrato;
        $this->estadocontrato = $lote->estadocontrato;
        $this->estado = $lote->estado;
    }

    public function setPersona($persona) {
        $this->persona_id = $persona;
        // $idrol = $rol;
        // $personarolList.push($rol);
        $this->lotepersonaList[] = $persona;
        //array_push($personarolList, $rol);
    }

    public function setUbigeo($ubigeodetalledto) {
        $this->ubigeo = $ubigeodetalledto;
        $this->ubigeo_id = $ubigeodetalledto->ubigeo;
    }

    public function setFotos($fotos) {
        $this->fotosList = $fotos;
        // $this->fotosList[] = $fotos;
    }

    /*public function setnMensajes($nmensajes) {
        $this->nmensajes = $nmensajes;
    }*/
}
?>