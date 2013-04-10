<?php
class VNoticias extends VESIS {
    public function Ver(){
        $this->setTemplate("openapp_int");
        $this->addCSS("estilos_int.css");
        $this->addJS("script.js");
        $this->setTITLE("Open App :: Noticia :: Ver");      
        $this->setModulo("Noticia_Ver");
        return $this->Compilar();
    }
}

?>
