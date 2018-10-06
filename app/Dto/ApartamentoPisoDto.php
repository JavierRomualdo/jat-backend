<?php 
namespace App\Dto;

class ApartamentoPisoDto
{
    public $id;
    public $persona_id;
    public $apartamento_id;
    public $precio;
    public $largo;
    public $ancho;
    public $numero;
    public $ncuartos;
    public $nbanios;
    public $nmensajes;
    public $descripcion;
    public $foto;
    public $path;
    public $estado;
    public $apartamentopisopersonaList = [];
    public $fotosList;

    function __construct() {
        $ncuartos = 0;
        $nbanios = 0;
        $descripcion = null;
        $path = null;
        $foto = null;
        $estado = true;
    }

    public function setApartamentoPiso($apartamentopiso) {
        $this->id = $apartamentopiso->id;
        // $this->rol_id = $persona->rol_id;
        $this->precio = $apartamentopiso->precio;
        $this->largo = $apartamentopiso->largo;
        $this->ancho = $apartamentopiso->ancho;
        $this->numero = $apartamentopiso->numero;
        $this->ncuartos = $apartamentopiso->ncuartos;
        $this->nbanios = $apartamentopiso->nbanios;
        $this->descripcion = $apartamentopiso->descripcion;
        $this->foto = $apartamentopiso->foto;
        $this->path = $apartamentopiso->path;
        $this->nmensajes = $apartamentopiso->nmensajes;
        $this->estado = $apartamentopiso->estado;
    }

    public function setPersona($persona) {
        $this->persona_id = $persona;
        // $idrol = $rol;
        // $personarolList.push($rol);
        $this->apartamentopisopersonaList[] = $persona;
        //array_push($personarolList, $rol);
    }

    public function setFotos($fotos) {
        $this->fotosList = $fotos;
        // $this->fotosList[] = $fotos;
    }
    /*public function setnMensajes($nmensajes) {
        $this->nmensajes = $nmensajes;
    }*/
}