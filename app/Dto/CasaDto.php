<?php 
namespace App\Dto;
class CasaDto
{
    public $id;
    public $persona_id;
    public $precio;
    public $largo;
    public $ancho;
    public $ubicacion;
    public $direccion;
    public $npisos;
    public $ncuartos;
    public $nbanios;
    public $tjardin;
    public $tcochera;
    public $descripcion;
    public $foto;
    public $path;
    public $estado;
    public $casapersonaList = [];
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
    }

    public function setCasa($casa) {
        $this->id = $casa->id;
        // $this->rol_id = $persona->rol_id;
        $this->precio = $casa->precio;
        $this->largo = $casa->largo;
        $this->ancho = $casa->ancho;
        $this->ubicacion = $casa->ubicacion;
        $this->direccion = $casa->direccion;
        $this->npisos = $casa->npisos;
        $this->ncuartos = $casa->ncuartos;
        $this->nbanios = $casa->nbanios;
        $this->tjardin = $casa->tjardin;
        $this->tcochera = $casa->tcochera;
        $this->descripcion = $casa->descripcion;
        $this->foto = $casa->foto;
        $this->path = $casa->path;
        $this->estado = $casa->estado;
    }

    public function setPersona($persona) {
        $this->persona_id = $persona;
        // $idrol = $rol;
        // $personarolList.push($rol);
        $this->casapersonaList[] = $persona;
        //array_push($personarolList, $rol);
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
}