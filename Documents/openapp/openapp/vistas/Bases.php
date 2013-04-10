<?php
class VBases extends VESIS {
    public function Bases(){
        $this->setTemplate("openapp_int");
        $this->addCSS("estilos_int.css");
        $this->addJS("script.js");
        $this->setTITLE("Open App :: Bases");
        $this->setModulo("bases");
        return $this->Compilar();
    }
}
