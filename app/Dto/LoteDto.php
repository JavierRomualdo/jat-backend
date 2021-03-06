<?php 
namespace App\Dto;
use App\Dto\UbigeoDetalleDto;

class LoteDto
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
    public $referencia;
    public $descripcion;
    public $foto;
    public $path;
    public $pathArchivos;
    public $contrato;
    public $estadocontrato;
    public $estado;
    // public $nmensajes;
    public $lotepersonaList = [];
    public $ubigeo;
    public $habilitacionurbana;
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

    public function setLote($lote) {
        $this->id = $lote->id;
        // $this->rol_id = $persona->rol_id;
        $this->codigo = $lote->codigo;
        $this->precioadquisicion = $lote->precioadquisicion;
        $this->preciocontrato = $lote->preciocontrato;
        $this->ganancia = $lote->ganancia;
        $this->largo = $lote->largo;
        $this->ancho = $lote->ancho;
        $this->nombrehabilitacionurbana = $lote->nombrehabilitacionurbana;
        $this->siglas = $lote->siglas;
        $this->ubicacion = $lote->ubicacion;
        $this->direccion = $lote->direccion;
        $this->latitud = $lote->latitud;
        $this->longitud = $lote->longitud;
        $this->referencia = $lote->referencia;
        $this->descripcion = $lote->descripcion;
        $this->foto = $lote->foto;
        $this->path = $lote->path;
        $this->pathArchivos = $lote->pathArchivos;
        $this->contrato = $lote->contrato;
        $this->estadocontrato = $lote->estadocontrato;
        $this->estado = $lote->estado;
    }

    public function setPersona($persona) {
        $this->persona_id = $persona;
        // $idrol = $rol;
        // $personarolList.push($rol);
        $this->lotepersonaList[] = $persona;
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

    /*public function setnMensajes($nmensajes) {
        $this->nmensajes = $nmensajes;
    }*/
}
?>