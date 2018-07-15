<?php 
namespace App\Dto;
class LoteDto
{
    public $id;
    public $persona_id;
    public $precio;
    public $largo;
    public $ancho;
    public $ubicacion;
    public $direccion;
    public $descripcion;
    public $foto;
    public $path;
    public $estado;
    public $lotepersonaList = [];
    public $fotosList = [];

    function __construct() {
        $descripcion = null;
        $path = null;
        $foto = null;
        $estado = true;
    }

    public function setLote($rol) {
        $this->id = $persona->id;
        // $this->rol_id = $persona->rol_id;
        $this->precio = $persona->precio;
        $this->largo = $persona->largo;
        $this->ancho = $persona->ancho;
        $this->ubicacion = $persona->ubicacion;
        $this->direccion = $persona->direccion;
        $this->descripcion = $persona->descripcion;
        $this->foto = $persona->foto;
        $this->path = $persona->path;
        $this->estado = $persona->estado;
    }

    public function setPersona($persona) {
        $this->persona_id = $persona;
        // $idrol = $rol;
        // $personarolList.push($rol);
        $this->lotepersonaList[] = $persona;
        //array_push($personarolList, $rol);
    }

    public function setFotos($fotos) {
        $this->fotosList[] = $fotos;
    }
}
?>