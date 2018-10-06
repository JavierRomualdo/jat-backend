<?php 
namespace App\Dto;
use App\Dto\UbigeoDetalleDto;

class EmpresaDto
{
    public $id;
    public $ubigeo_id;
    public $nombre;
    public $ruc;
    public $direccion;
    public $telefono;
    public $correo;
    public $nombrefoto;
    public $foto;
    public $estado;
    public $ubigeo;

    function __construct() {
        $correo = null;
        $nombrefoto = null;
        $estado = true;
        $ubigeo = new UbigeoDetalleDto();
    }

    public function setEmpresa($empresa) {
        $this->id = $empresa->id;
        // $this->rol_id = $persona->rol_id;
        $this->nombre = $empresa->nombre;
        $this->ruc = $empresa->ruc;
        $this->direccion = $empresa->direccion;
        $this->telefono = $empresa->telefono;
        $this->correo = $empresa->correo;
        $this->nombrefoto = $empresa->nombrefoto;
        $this->foto = $empresa->foto;
        $this->estado = $empresa->estado;
    }

    public function setUbigeo($ubigeodetalledto) {
        $this->ubigeo = $ubigeodetalledto;
        $this->ubigeo_id = $ubigeodetalledto->ubigeo;
    }
}
?>