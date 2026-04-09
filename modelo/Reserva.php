<?php
class Reserva
{
    private $id;
    private $observaciones;
    private $fechaYHoraInicio;
    private $fechaYHoraFin;

    public function __construct($id, $observaciones, $fechaYHoraInicio, $fechaYHoraFin)
    {
        $this->id = $id;
        $this->observaciones = $observaciones;
        $this->fechaYHoraInicio = $fechaYHoraInicio;
        $this->fechaYHoraFin = $fechaYHoraFin;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getObservaciones()
    {
        return $this->observaciones;
    }

    public function getFechaYHoraInicio()
    {
        return $this->fechaYHoraInicio;
    }

    public function getFechaYHoraFin()
    {
        return $this->fechaYHoraFin;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    }

    public function setFechaYHoraInicio($fechaYHoraInicio)
    {
        $this->fechaYHoraInicio = $fechaYHoraInicio;
    }

    public function setFechaYHoraFin($fechaYHoraFin)
    {
        $this->fechaYHoraFin = $fechaYHoraFin;
    }
}
?>
