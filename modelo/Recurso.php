<?php
class Recurso
{
    private $descripcion;
    private $nombreRecurso;

    public function __construct($descripcion, $nombreRecurso)
    {
        $this->descripcion = $descripcion;
        $this->nombreRecurso = $nombreRecurso;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function getNombreRecurso()
    {
        return $this->nombreRecurso;
    }

    public function setNombreRecurso($nombreRecurso)
    {
        $this->nombreRecurso = $nombreRecurso;
    }
}
?>
