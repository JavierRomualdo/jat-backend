<?php 
namespace App\EntityWeb\Entidades\Casas;

class CasaTO
{
    public $id;
    public $foto;
    public $propietario;
    public $ubicacion;
    public $direccion;
    public $largo;
    public $ancho;
    public $precio;
    public $npisos;
    public $ncuartos;
    public $nbanios;
    public $tjardin;
    public $tcochera;
    public $estado;

    function __construct() {
        $id = null;
        $foto = null;
        $propietario = null;
        $ubicacion = null;
        $direccion = null;
        $largo = 0;
        $ancho = 0;
        $precio = 0.0;
        $npisos = 0;
        $ncuartos = 0;
        $nbanios = 0;
        $tjardin = false;
        $tcochera = false;
        $estado = false;
    }

    public function setCasa($casa) {
        $this->id = $casa->id;
        $this->foto = $casa->foto;
        $this->propietario = $propietario->propietario;
        $this->ubicacion = $casa->ubicacion;
        $this->direccion = $casa->direccion;
        $this->largo = $casa->largo;
        $this->ancho = $casa->ancho;
        $this->precio = $casa->precio;
        $this->npisos = $casa->npisos;
        $this->ncuartos = $casa->ncuartos;
        $this->nbanios = $casa->nbanios;
        $this->tjardin = $casa->tjardin;
        $this->tcochera = $casa->tcochera;
        $this->estado = $casa->estado;
    }
}