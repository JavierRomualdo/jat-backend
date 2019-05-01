<?php 
namespace App\Dto;

use App\Models\Ubigeo;
use App\Models\UbigeoTipo;
use App\Dto\UbigeoDto;

class UbigeoDetalleDto
{
    public $departamento;
    public $provincia;
    public $distrito;
    public $ubigeo; // distrito

    function __construct() {
        $ubigeo = new UbigeoDto();
    }

    public function setDepartamento($ubigeo)
    {
        # code...
        $this->departamento = $ubigeo;
    }

    public function setProvincia($ubigeo)
    {
        # code...
        $this->provincia = $ubigeo;
    }

    public function setDistrito($ubigeo)
    {
        # code...
        $this->distrito = $ubigeo;
    }

    public function setUbigeo($ubigeodto) {
        $this->ubigeo = $ubigeodto;
    }
}
?>