<?php 
namespace App\Dto;
use App\Dto\UbigeoDetalleDto;

class ApartamentoDto
{
    public $id;
    public $ubigeo_id;
    public $codigo;
    public $precioadquisicion;
    public $preciocontrato;
    public $ganancia;
    public $largo;
    public $ancho;
    public $direccion;
    public $npisos;
    public $nmensajes;
    public $tcochera;
    public $descripcion;
    public $foto;
    public $path;
    public $contrato;
    public $estadocontrato;
    public $estado;
    public $ubigeo;
    public $serviciosList;
    public $apartamentoservicioList;
    public $fotosList;

    function __construct() {
        $npisos = 0;
        $tcochera = false;
        $descripcion = null;
        $path = null;
        $foto = null;
        $estado = true;
        $ubigeo = new UbigeoDetalleDto();
    }

    public function setDepartamento($apartamento) {
        $this->id = $apartamento->id;
        $this->codigo = $apartamento->codigo;
        $this->precioadquisicion = $apartamento->precioadquisicion;
        $this->preciocontrato = $apartamento->preciocontrato;
        $this->ganancia = $apartamento->ganancia;
        $this->largo = $apartamento->largo;
        $this->ancho = $apartamento->ancho;
        $this->direccion = $apartamento->direccion;
        $this->npisos = $apartamento->npisos;
        $this->tcochera = $apartamento->tcochera;
        $this->descripcion = $apartamento->descripcion;
        $this->foto = $apartamento->foto;
        $this->path = $apartamento->path;
        $this->nmensajes = $apartamento->nmensajes;
        $this->contrato = $apartamento->contrato;
        $this->estadocontrato = $apartamento->estadocontrato;
        $this->estado = $apartamento->estado;
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

    public function setApartamentoServicio($apartamentoservicio) {
        $this->apartamentoservicioList = $apartamentoservicio;
    }

    /*public function setnMensajes($nmensajes) {
        $this->nmensajes = $nmensajes;
    }*/
}