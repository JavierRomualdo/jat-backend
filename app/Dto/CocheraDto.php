<?php 
namespace App\Dto;
use App\Dto\UbigeoDetalleDto;

class CocheraDto
{
    public $id;
    public $persona_id;
    public $ubigeo_id;
    public $precio;
    public $largo;
    public $ancho;
    public $direccion;
    public $descripcion;
    public $foto;
    public $path;
    public $nmensajes;
    public $tiposervicio;
    public $estado;
    public $cocherapersonaList = [];
    public $ubigeo;
    public $serviciosList;
    public $cocheraservicioList;
    public $fotosList;

    function __construct() {
        $descripcion = null;
        $path = null;
        $foto = null;
        $estado = true;
        $ubigeo = new UbigeoDetalleDto();
    }

    public function setCochera($cochera) {
        $this->id = $cochera->id;
        // $this->rol_id = $persona->rol_id;
        $this->precio = $cochera->precio;
        $this->largo = $cochera->largo;
        $this->ancho = $cochera->ancho;
        $this->direccion = $cochera->direccion;
        $this->descripcion = $cochera->descripcion;
        $this->foto = $cochera->foto;
        $this->path = $cochera->path;
        $this->nmensajes = $cochera->nmensajes;
        $this->tiposervicio = $cochera->tiposervicio;
        $this->estado = $cochera->estado;
    }

    public function setPersona($persona) {
        $this->persona_id = $persona;
        // $idrol = $rol;
        // $personarolList.push($rol);
        $this->cocherapersonaList[] = $persona;
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

    public function setCocheraServicio($cocheraservicio) {
        $this->cocheraservicioList = $cocheraservicio;
    }

    /*public function setnMensajes($nmensajes) {
        $this->nmensajes = $nmensajes;
    }*/
}