<?php 
namespace App\Dto;
class NotificacionDto
{
    public $cantidad;
    public $notificaciones = []; // NotificacionTO

    function __construct() {
        $this->notificaciones = [];
    }

    public function setNotificacion($notificacion) {
        $this->notificaciones[] = $notificacion;
    }

    public function setCantidad($cantidad)
    {
        # code...
        $this->cantidad = $cantidad;
    }
}
?>