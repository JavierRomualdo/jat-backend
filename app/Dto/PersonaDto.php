<?php 
namespace App\Dto;
class PersonaDto
{
    public $id;
    public $rol_id;
    public $dni;
    public $nombres;
    public $correo;
    public $ubicacion;
    public $direccion;
    public $telefono;
    public $estado;
    public $personarolList = [];

    function __construct() {
        $correo = null;
        $estado = true;
    }

    public function setPersona($persona) {
        $this->id = $persona->id;
        // $this->rol_id = $persona->rol_id;
        $this->dni = $persona->dni;
        $this->nombres = $persona->nombres;
        $this->correo = $persona->correo;
        $this->ubicacion = $persona->ubicacion;
        $this->direccion = $persona->direccion;
        $this->telefono = $persona->telefono;
        $this->estado = $persona->estado;
    }

    public function setRol($rol) {
        $this->rol_id = $rol;
        // $idrol = $rol;
        // $personarolList.push($rol);
        $this->personarolList[] = $rol;
        //array_push($personarolList, $rol);
    }
}
?>