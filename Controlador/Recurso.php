<?php
    class Recurso{
        private $Descripcion;
        private $NombreRecurso;
        public function __construct($Descripcion, $NombreRecurso){
            $this->Descripcion = $Descripcion;
            $this->NombreRecurso = $NombreRecurso;
        }
        
        public function getDescripcion(){
            return $this->Descripcion;
        }
        public function setDescripcion($Descripcion){
            $this->Descripcion = $Descripcion;
        }
        public function getNombreRecurso(){
            return $this->NombreRecurso;
        }
        public function setNombreRecurso($NombreRecurso){
            $this->NombreRecurso = $NombreRecurso;
        }
    }
?>