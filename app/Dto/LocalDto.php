<?php 
namespace App\Dto;
use App\Dto\UbigeoDetalleDto;

class LocalDto
{
    public $id;
    public $persona_id;
    public $ubigeo_id;
    public $precio;
    public $largo;
    public $ancho;
    public $ubicacion;
    public $direccion;
    public $nmensajes;
    public $tbanio;
    public $descripcion;
    public $foto;
    public $path;
    public $estado;
    public $localpersonaList = [];
    public $ubigeo;
    public $serviciosList;
    public $localservicioList;
    public $fotosList;

    function __construct() {
        $descripcion = null;
        $path = null;
        $foto = null;
        $estado = true;
        $ubigeo = new UbigeoDetalleDto();
    }

    public function setLocal($local) {
        $this->id = $local->id;
        // $this->rol_id = $persona->rol_id;
        $this->precio = $local->precio;
        $this->largo = $local->largo;
        $this->ancho = $local->ancho;
        $this->ubicacion = $local->ubicacion;
        $this->direccion = $local->direccion;
        $this->tbanio = $local->tbanio;
        $this->descripcion = $local->descripcion;
        $this->foto = $local->foto;
        $this->path = $local->path;
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