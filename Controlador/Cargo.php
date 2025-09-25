<?php
class cargo{
        private $NombreCargo;
        private $IdCargo;
    
        public function __construct($NombreCargo, $IdCargo){
            $this->NombreCargo = $NombreCargo;
            $this->IdCargo = $IdCargo;
        }
    
        public function getNombre(){
            return $this->NombreCargo;
        }
        public function getid(){
            return $this->IdCargo;
        }
        public function setNombre($NombreCargo){
            $this->NombreCargo = $NombreCargo;
        }
        
        public function setId($IdCargo){
            $this->IdCargo = $IdCargo;
        }

    
}
?>