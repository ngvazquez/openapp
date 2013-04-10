<?php

class VPatrocinadores extends VESIS{
    
    public function Patrocinadores(){
        $this->setTemplate("openapp_int");
        $this->addCSS("estilos_int.css");
        $this->addJS("script.js");
        $this->setTITLE("Open App :: Patrocinadores");
        $this->setModulo("Patrocinadores");
        return $this->Compilar();
    }
    
}

?>
