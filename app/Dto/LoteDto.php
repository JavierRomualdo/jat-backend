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
    public $fotosList;

    function __construct() {
        $descripcion = null;
        $path = null;
        $foto = null;
        $estado = true;
    }

    public function setLote($lote) {
        $this->id = $lote->id;
        // $this->rol_id = $persona->rol_id;
        $this->precio = $lote->precio;
        $this->largo = $lote->largo;
        $this->ancho = $lote->ancho;
        $this->ubicacion = $lote->ubicacion;
        $this->direccion = $lote->direccion;
        $this->descripcion = $lote->descripcion;
        $this->foto = $lote->foto;
        $this->path = $lote->path;
        $this->estado = $lote->estado;
    }

    public function setPersona($persona) {
        $this->persona_id = $persona;
        // $idrol = $rol;
        // $personarolList.push($rol);
        $this->lotepersonaList[] = $persona;
        //array_push($personarolList, $rol);
    }

    public function setFotos($fotos) {
        $this->fotosList = $fotos;
        // $this->fotosList[] = $fotos;
    }
}
?>