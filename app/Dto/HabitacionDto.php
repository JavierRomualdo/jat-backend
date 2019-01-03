<?php 
namespace App\Dto;
use App\Dto\UbigeoDetalleDto;

class HabitacionDto
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
    public $ncamas;
    // public $nmensajes;
    public $tbanio;
    public $descripcion;
    public $foto;
    public $path;
    public $contrato;
    public $estadocontrato;
    public $estado;
    public $habitacionpersonaList = [];
    public $ubigeo;
    public $serviciosList;
    public $habitacionservicioList;
    public $fotosList;

    function __construct() {
        $descripcion = null;
        $path = null;
        $foto = null;
        $estado = true;
        $ubigeo = new UbigeoDetalleDto();
    }

    public function setHabitacion($habitacion) {
        $this->id = $habitacion->id;
        // $this->rol_id = $persona->rol_id;
        $this->codigo = $habitacion->codigo;
        $this->precioadquisicion = $habitacion->precioadquisicion;
        $this->preciocontrato = $habitacion->preciocontrato;
        $this->ganancia = $habitacion->ganancia;
        $this->largo = $habitacion->largo;
        $this->ancho = $habitacion->ancho;
        $this->ubicacion = $habitacion->ubicacion;
        $this->direccion = $habitacion->direccion;
        $this->ncamas = $habitacion->ncamas;
        $this->tbanio = $habitacion->tbanio;
        $this->descripcion = $habitacion->descripcion;
        $this->foto = $habitacion->foto;
        $this->path = $habitacion->path;
        $this->contrato = $habitacion->contrato;
        $this->contrato = $habitacion->contrato;
        $this->estadocontrato = $habitacion->estadocontrato;
        $this->estado = $habitacion->estado;
    }

    public function setPersona($persona) {
        $this->persona_id = $persona;
        // $idrol = $rol;
        // $personarolList.push($rol);
        $this->habitacionpersonaList[] = $persona;
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

    public function setHabitacionServicio($habitacionservicio) {
        $this->habitacionservicioList = $habitacionservicio;
    }

    /*public function setnMensajes($nmensajes) {
        $this->nmensajes = $nmensajes;
    }*/
}
?>