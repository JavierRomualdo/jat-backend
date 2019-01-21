<?php 
namespace App\EntityWeb\Entidades\Mensajes;

class NotificacionTO
{
    public $propiedad;
    public $notificacion;
    public $cantidad;
    public $url;
    
    function __construct($propiedad, $notificacion, $cantidad, $url) {
        $this->propiedad = $propiedad;
        $this->notificacion = $notificacion;
        $this->cantidad = $cantidad;
        $this->url = $url;
    }
    
    public function setNotificacion($propiedad, $notificacion, $cantidad, $url)
    {
        # code...
        $this->propiedad = $propiedad;
        $this->notificacion = $notificacion;
        $this->cantidad = $cantidad;
        $this->url = $url;
    }

    public function setPropiedad($propiedad)
    {
        # code...
        $this->propiedad = $propiedad;
    }

    public function setNotification($notificacion)
    {
        # code...
        $this->notificacion = $notificacion;
    }

    public function setCantidad($cantidad)
    {
        # code...
        $this->cantidad = $cantidad;
    }

    public function setUrl($url)
    {
        # code...
        $this->url = $url;
    }
}