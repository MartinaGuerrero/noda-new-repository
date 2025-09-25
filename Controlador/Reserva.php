<?php
class usuario{
    private $Id;
    private $Observaciones;
    private $FechaYHoraInicio;
    private $FechaYHoraFin;

    public function __construct($Id, $Observaciones, $FechaYHoraInicio, $FechaYHoraFin){
        $this->Id = $Id;
        $this->Observaciones = $Observaciones;
        $this->FechaYHoraInicio = $FechaYHoraInicio;
        $this->FechaYHoraFin = $FechaYHoraFin;
    }

    public function getId(){
        return $this->Id;
    }
    public  function getObservaciones(){
        return $this->Observaciones;
    }
    public function FechaYHoraInicio(){
        return $this->FechaYHoraInicio;
    }
    public function FechaYHoraFin(){
        return $this->FechaYHoraFin;
    }
    public function setId($Id){
        $this->Id = $Id;
    }
    public function setObservaciones($Observaciones){
        $this->Observaciones = $Observaciones;
    }
    public function setFechaYHoraFin($FechaYHoraFin){
        $this->FechaYHoraFin = $FechaYHoraFin;
    }
    public function setFechaYHoraInicio($FechaYHoraInicio){
        $this->FechaYHoraInicio = $FechaYHoraInicio;
    }
}
?>
    