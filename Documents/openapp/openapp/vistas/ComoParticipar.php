<?php

class VComoParticipar extends VESIS{
   public function ComoParticipar(){
        $this->setTemplate("openapp_int");
        $this->addCSS("estilos_int.css");
        $this->addJS("script.js");
        $this->setTITLE("Open App :: Como Participar");
        $this->setModulo("ComoParticipar");
        return $this->Compilar();
   }
}

?>
