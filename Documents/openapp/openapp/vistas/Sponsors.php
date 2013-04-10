<?php
class VSponsors extends VESIS {
    public function Sponsors(){
        $this->setTemplate("openapp_int");
        $this->setTITLE("Open App :: Sponsors");
        $this->addCSS("estilos_int.css");
        $this->setModulo("Sponsors");
        $this->addJS("script.js");
        return $this->Compilar();
        
    }
}
