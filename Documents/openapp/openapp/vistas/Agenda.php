<?php
class VAgenda extends VESIS {
    public function Agenda(){
        $this->setTemplate("openapp_int");
        $this->setTITLE("Open App :: Agenda");
        $this->addCSS("estilos_int.css");
        $this->setModulo("agenda");
        $this->addJS("script.js");
        $this->addJS("SlideSpeakers.js");
        return $this->Compilar();
    }
}
