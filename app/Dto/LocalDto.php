<?php 
namespace App\Dto;
use App\Dto\UbigeoDetalleDto;

class LocalDto
{
    public $id;
    public $persona_id;
    public $ubigeo_id;
    public $codigo;
    public $precioadquisicion;
    public $preciocontrato;
    public $ganancia;
    public $largo;
    public $ancho;
    public $ubicacion;
    public $direccion;
    public $latitud;
    public $longitud;
    public $nmensajes;
    public $tbanio;
    public $referencia;
    public $descripcion;
    public $foto;
    public $path;
    public $contrato;
    public $estadocontrato;
    public $estado;
    public $localpersonaList = [];
    public $ubigeo;
    public $serviciosList;
    public $localservicioList;
    public $fotosList;

    function __construct() {
        $referencia = null;
        $descripcion = null;
        $path = null;
        $foto = null;
        $estado = true;
        $ubigeo = new UbigeoDetalleDto();
    }

    public function setLocal($local) {
        $this->id = $local->id;
        // $this->rol_id = $persona->rol_id;
        $this->codigo = $local->codigo;
        $this->precioadquisicion = $local->precioadquisicion;
        $this->preciocontrato = $local->preciocontrato;
        $this->ganancia = $local->ganancia;
        $this->largo = $local->largo;
        $this->ancho = $local->ancho;
        $this->ubicacion = $local->ubicacion;
        $this->direccion = $local->direccion;
        $this->latitud = $local->latitud;
        $this->longitud = $local->longitud;
        $this->tbanio = $local->tbanio;
        $this->referencia = $local->referencia;
        $this->descripcion = $local->descripcion;
        $this->foto = $local->foto;
        $this->path = $local->path;
        $this->contrato = $local->contrato;
        $this->estadocontrato = $local->estadocontrato;
        $this->estado = $local->estado;
    }

    public function setPersona($persona) {
        $this->persona_id = $persona;
        // $idrol = $rol;
        // $personarolList.push($rol);
        $this->localpersonaList[] = $persona;
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

    public function setServicios($servicios) {
        $this->serviciosList = $servicios;
        // $this->fotosList[] = $fotos;
    }

    public function setLocalServicio($localservicio) {
        $this->localservicioList = $localservicio;
    }

    public function setnMensajes($nmensajes) {
        $this->nmensajes = $nmensajes;
    }
}
?>