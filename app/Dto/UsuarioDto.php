<?php 
namespace App\Dto;
use App\Dto\NotificacionDto;

class UsuarioDto
{
    public $usuario;
    public $notificacionDto;

    function __construct() {
        $this->notificacionDto = new NotificacionDto();
    }

    public function setNotificacionDtO($notificacionDto) {
        $this->notificacionDto= $notificacionDto;
    }

    public function setUsuario($usuario)
    {
        # code...
        $this->usuario = $usuario;
    }
}
?>