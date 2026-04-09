<?php
class Espacio
{
    private $idEspacio;
    private $capacidad;
    private $nombreSala;
    private $estado;
    private $centro;
    private $imagen;

    public function __construct($idEspacio, $capacidad, $nombreSala, $estado, $centro = null, $imagen = null)
    {
        $this->idEspacio = $idEspacio;
        $this->capacidad = $capacidad;
        $this->nombreSala = $nombreSala;
        $this->estado = $estado;
        $this->centro = $centro;
        $this->imagen = $imagen;
    }

    public function getIdEspacio()
    {
        return $this->idEspacio;
    }

    public function getCapacidad()
    {
        return $this->capacidad;
    }

    public function getNombreSala()
    {
        return $this->nombreSala;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getCentro()
    {
        return $this->centro;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setIdEspacio($idEspacio)
    {
        $this->idEspacio = $idEspacio;
    }

    public function setCapacidad($capacidad)
    {
        $this->capacidad = $capacidad;
    }

    public function setNombreSala($nombreSala)
    {
        $this->nombreSala = $nombreSala;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function setCentro($centro)
    {
        $this->centro = $centro;
    }

    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }
}
?>
