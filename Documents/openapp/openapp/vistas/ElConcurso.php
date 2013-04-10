<?php

class VElConcurso extends VESIS{
    public function ElConcurso(){
        $this->setTemplate("openapp_int");
        $this->addCSS("estilos_int.css");
        $this->addJS("script.js");
        $this->setTITLE("Open App :: El Concurso");
        $this->setModulo("concurso");
        return $this->Compilar();
    }
}

?>
