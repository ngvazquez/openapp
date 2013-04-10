<?php
class VEvento extends VESIS{
    public function Evento(){
        $this->setTemplate("openapp_int");
        $this->addCSS("estilos_int.css");
        $this->addJS("script.js");
        $this->setTITLE("Open App :: El Evento");
        $this->setModulo("evento");
        return $this->Compilar();
    }
}