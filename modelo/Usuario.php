<?php
class Usuario
{
    private $nombre;
    private $apellido;
    private $correo;
    private $contrasena;
    private $cargo;

    public function __construct($nombre, $apellido, $correo, $contrasena, $cargo = null)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->contrasena = $contrasena;
        $this->cargo = $cargo;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function getContrasena()
    {
        return $this->contrasena;
    }

    public function getCargo()
    {
        return $this->cargo;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    public function setCorreo($correo)
    {
        $this->correo = $correo;
    }

    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;
    }

    public function setCargo($cargo)
    {
        $this->cargo = $cargo;
    }
}
?>
