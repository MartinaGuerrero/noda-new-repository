<?php
class Espacio{
    private $IdEspacio;
    private $Capacidad;
    private $NombreSala;
    private $Estado;

    public function __construct($IdEspaacio, $Capacidad, $NombreSala, $Estado){
        $this->IdEspacio = $IdEspacio;
        $this->Capacidad = $Capacidad;
        $this->NombreSala = $NombreSala;
        $this->Estado = $Estado;
    }

    public function getIdEspacio(){
        return $this->IdEspacio;
    }
    public function getCapacidad(){
        return $this->Capacidad;
    }
    public function getNombreSala(){
        return $this->NombreSala;
    }
    public function getEstado(){
        return $this->Estado;
    }
    public function setObservaciones($IdEspacios){
        $this->IdEspacio = $IdEspaacio;
    }
    public function setObservaciones($Capacidad){
        $this->Capacidad = $Capacidad;
    }
    public function setObservaciones($NombreSala){
        $this->NombreSala = $NombreSala;
    }
    public function setObservaciones($Estado){
        $this->Estado = $Estado;
    }
}
?>