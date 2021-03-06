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
    public $nombrehabilitacionurbana;
    public $siglas;
    public $ubicacion;
    public $direccion;
    public $latitud;
    public $longitud;
    public $ncamas;
    // public $nmensajes;
    public $tbanio;
    public $referencia;
    public $descripcion;
    public $foto;
    public $path;
    public $pathArchivos;
    public $contrato;
    public $estadocontrato;
    public $estado;
    public $habitacionpersonaList = [];
    public $ubigeo;
    public $habilitacionurbana;
    public $serviciosList;
    public $habitacionservicioList;
    public $fotosList;
    public $archivosList;

    function __construct() {
        $referencia = null;
        $descripcion = null;
        $path = null;
        $pathArchivos = null;
        $foto = null;
        $estado = true;
        $ubigeo = new UbigeoDetalleDto();
        $habilitacionurbana = null; // objetoModel habilitacionurbana
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
        $this->nombrehabilitacionurbana = $habitacion->nombrehabilitacionurbana;
        $this->siglas = $habitacion->siglas;
        $this->ubicacion = $habitacion->ubicacion;
        $this->direccion = $habitacion->direccion;
        $this->latitud = $habitacion->latitud;
        $this->longitud = $habitacion->longitud;
        $this->ncamas = $habitacion->ncamas;
        $this->tbanio = $habitacion->tbanio;
        $this->referencia = $habitacion->referencia;
        $this->descripcion = $habitacion->descripcion;
        $this->foto = $habitacion->foto;
        $this->path = $habitacion->path;
        $this->pathArchivos = $habitacion->pathArchivos;
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
        $this->ubigeo->ubigeo->siglas = $this->habilitacionurbana->siglas;
        $this->ubigeo_id = $ubigeodetalledto->ubigeo;
        $this->ubigeo_id->habilitacionurbana_id = $this->habilitacionurbana;
        $this->ubigeo_id->siglas = $this->habilitacionurbana->siglas;
    }

    public function setHabilitacionUrbana($habilitacionurbana)
    {
        # code...
        $this->habilitacionurbana = $habilitacionurbana;
    }

    public function setFotos($fotos) {
        $this->fotosList = $fotos;
        // $this->fotosList[] = $fotos;
    }

    public function setArchivos($archivos) { 
        $this->archivosList = $archivos;
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