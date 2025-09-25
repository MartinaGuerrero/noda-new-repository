<?php
class usuario{
    private $nombre;
    private $apellido;
    private $correo;
    private $contracena;

    public function __construct($nombre, $apellido, $correo, $contracena){
        $this->Nombre = $nombre;
        $this->Apellido = $apellido;
        $this->Correo = $correo;
        $this->Contracena = $contracena;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getApellido(){
        return $this->apellido;
    }
    public function getCorreo(){
        return $this->correo;
    }
    public function getContracena(){
        return $this->contracena;
    }

    public function setNombre($Nombre){
        $this->Nombre = $Nombre;
    }

    public function setApellido($Apellido){
        $this->Apellido = $Apellido;
    }

    public function setCorreo($Correo){
        $this->Correo = $Correo;
    }

    public function setContracena($Contracena){
        $this->Contracena = $Contracena;
    }
}
?>