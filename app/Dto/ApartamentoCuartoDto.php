<?php 
namespace App\Dto;

class ApartamentoCuartoDto
{
    public $id;
    public $persona_id;
    public $apartamento_id;
    public $precio;
    public $largo;
    public $ancho;
    public $piso;
    public $nbanios;
    // public $nmensajes;
    public $descripcion;
    public $foto;
    public $path;
    public $estado;
    public $apartamentocuartopersonaList = [];
    public $fotosList;

    function __construct() {
        $nbanios = 0;
        $descripcion = null;
        $path = null;
        $foto = null;
        $estado = true;
    }

    public function setApartamentoCuarto($apartamentocuarto) {
        $this->id = $apartamentocuarto->id;
        // $this->rol_id = $persona->rol_id;
        $this->precio = $apartamentocuarto->precio;
        $this->largo = $apartamentocuarto->largo;
        $this->ancho = $apartamentocuarto->ancho;
        $this->piso = $apartamentocuarto->piso;
        $this->nbanios = $apartamentocuarto->nbanios;
        $this->descripcion = $apartamentocuarto->descripcion;
        $this->foto = $apartamentocuarto->foto;
        $this->path = $apartamentocuarto->path;
        $this->estado = $apartamentocuarto->estado;
    }

    public function setPersona($persona) {
        $this->persona_id = $persona;
        // $idrol = $rol;
        // $personarolList.push($rol);
        $this->apartamentocuartopersonaList[] = $persona;
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