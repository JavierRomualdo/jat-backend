<?php 
namespace App\Dto;
use App\Dto\UbigeoDetalleDto;

class CasaDto
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
    public $direccion;
    public $latitud;
    public $longitud;
    public $npisos;
    public $ncuartos;
    public $nbanios;
    // public $nmensajes;
    public $tjardin;
    public $tcochera;
    public $referencia;
    public $descripcion;
    public $foto;
    public $path;
    public $contrato;
    public $estadocontrato;
    public $estado;
    public $casapersonaList = [];
    public $ubigeo;
    public $habilitacionurbana;
    public $serviciosList;
    public $casaservicioList;
    public $fotosList;

    function __construct() {
        $npisos = 0;
        $ncuartos = 0;
        $nbanios = 0;
        $tjardin = false;
        $tcochera = false;
        $referencia = null;
        $descripcion = null;
        $path = null;
        $foto = null;
        $estado = true;
        $ubigeo = new UbigeoDetalleDto();
        $habilitacionurbana = null; // objetoModel habilitacionurbana
    }

    public function setCasa($casa) {
        $this->id = $casa->id;
        // $this->rol_id = $persona->rol_id;
        $this->codigo = $casa->codigo;
        $this->precioadquisicion = $casa->precioadquisicion;
        $this->preciocontrato = $casa->preciocontrato;
        $this->ganancia = $casa->ganancia;
        $this->largo = $casa->largo;
        $this->ancho = $casa->ancho;
        $this->nombrehabilitacionurbana = $casa->nombrehabilitacionurbana;
        $this->direccion = $casa->direccion;
        $this->latitud = $casa->latitud;
        $this->longitud = $casa->longitud;
        $this->npisos = $casa->npisos;
        $this->ncuartos = $casa->ncuartos;
        $this->nbanios = $casa->nbanios;
        $this->tjardin = $casa->tjardin;
        $this->tcochera = $casa->tcochera;
        $this->referencia = $casa->referencia;
        $this->descripcion = $casa->descripcion;
        $this->foto = $casa->foto;
        $this->path = $casa->path;
        $this->contrato = $casa->contrato;
        $this->estadocontrato = $casa->estadocontrato;
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

    public function setHabilitacionUrbana($habilitacionurbana)
    {
        # code...
        $this->habilitacionurbana = $habilitacionurbana;
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