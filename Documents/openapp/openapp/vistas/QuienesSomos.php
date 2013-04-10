<?php
class VQuienesSomos extends VESIS {
    public function QuienesSomos(){
        $this->setTemplate("openapp_int");
        $this->setTITLE("Open App :: &iquest;Quienes somos?");
        $this->addCSS("estilos_int.css");
        $this->setModulo("QuienesSomos");
        $this->addJS("script.js");
        return $this->Compilar();
    }
}
