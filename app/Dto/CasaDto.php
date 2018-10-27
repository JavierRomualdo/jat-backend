<?php 
namespace App\Dto;
use App\Dto\UbigeoDetalleDto;

class CasaDto
{
    public $id;
    public $persona_id;
    public $ubigeo_id;
    public $precio;
    public $largo;
    public $ancho;
    public $direccion;
    public $npisos;
    public $ncuartos;
    public $nbanios;
    // public $nmensajes;
    public $tjardin;
    public $tcochera;
    public $descripcion;
    public $foto;
    public $path;
    public $tiposervicio;
    public $estado;
    public $casapersonaList = [];
    public $ubigeo;
    public $serviciosList;
    public $casaservicioList;
    public $fotosList;

    function __construct() {
        $npisos = 0;
        $ncuartos = 0;
        $nbanios = 0;
        $tjardin = false;
        $tcochera = false;
        $descripcion = null;
        $path = null;
        $foto = null;
        $estado = true;
        $ubigeo = new UbigeoDetalleDto();
    }

    public function setCasa($casa) {
        $this->id = $casa->id;
        // $this->rol_id = $persona->rol_id;
        $this->precio = $casa->precio;
        $this->largo = $casa->largo;
        $this->ancho = $casa->ancho;
        $this->direccion = $casa->direccion;
        $this->npisos = $casa->npisos;
        $this->ncuartos = $casa->ncuartos;
        $this->nbanios = $casa->nbanios;
        $this->tjardin = $casa->tjardin;
        $this->tcochera = $casa->tcochera;
        $this->descripcion = $casa->descripcion;
        $this->foto = $casa->foto;
        $this->path = $casa->path;
        $this->tiposervicio = $casa->tiposervicio;
        $this->estado = $casa->estado;
    }

    public function setPersona($persona) {
        $this->persona_id = $persona;
        // $idrol = $rol;
        // $personarolList.push($rol);
        $this->casapersonaList[] = $persona;
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

    public function setCasaServicio($casaservicio) {
        $this->casaservicioList = $casaservicio;
    }

    /*public function setnMensajes($nmensajes) {
        $this->nmensajes = $nmensajes;
    }*/
}