<?php
class VProyecto extends VESIS {
    public function Listado(){
        $this->setTemplate("openapp_int");
        $this->addCSS("estilos_int.css");
        $this->addJS("script.js");
        $this->setTITLE("Open App :: Proyecto");
        $this->setModulo("Proyectos");
        return $this->Compilar();
    }
    public function Ver(){
        $this->setTemplate("openapp_int");
        $this->addCSS("estilos_int.css");
        $this->addJS("script.js");
        $this->setTITLE("Open App :: Proyecto :: Ver");      
        $this->setModulo("Proyectos_Ver");
        return $this->Compilar();
    }
    
    public function FormProyectoCompleto(){
        $this->setTemplate("openapp_int");
        $this->addCSS("estilos_int.css");
        $this->addJS("script.js");
        $this->setTITLE("Open App :: Proyecto");
        $this->setModulo("FormProyectoC");
        return $this->Compilar();
    }
    
    public function FormCargaFile(){
        $this->setTemplate("openapp_int");
        $this->addCSS("estilos_int.css");
        $this->addJS("script.js");
        $this->addJS("validar.js");
        $this->setTITLE("Open App :: Proyecto :: Carga de archivo");
        $this->setModulo("FormCargaFile");
        return $this->Compilar();
    }
}