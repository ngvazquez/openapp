<?php
class VHome extends VESIS {
    public function Home(){
        $this->setTemplate("openapp_home");
        $this->setTITLE("Open App :: Home");
        $this->addCSS("estilos.css");
        $this->addJS("script.js");
        return $this->Compilar();
    }
    
    public function FormRegistro(){
        $this->setTemplate("Vacio");
        $this->setTITLE("");
        $this->setModulo("FormRegistro");
        return $this->Compilar();
    }
    
    public function FormProyecto(){
        $this->setTemplate("Vacio");
        $this->setTITLE("");
        $this->setModulo("FormProyecto");
        return $this->Compilar();
    }
    
    public function FormProyectoExt(){
        $this->setTemplate("Vacio");
        $this->setTITLE("");
        $this->setModulo("FormProyectoExt");
        return $this->Compilar();
    }
    
    public function FormLogin(){
        $this->setTemplate("Vacio");
        $this->setTITLE("");
        $this->setModulo("FormLogin");
        return $this->Compilar();
    }
    
    public function FormOClave(){
        $this->setTemplate("Vacio");
        $this->setTITLE("");
        $this->setModulo("FormOClave");
        return $this->Compilar();
    }
    
    /*Vistas para los popup*/
    public function MensOKReg(){
        $this->setTemplate("Vacio");
        $this->setTITLE("");
        $this->setModulo("MensOKReg");
        return $this->Compilar();
    }
    public function MensErrorReg(){
        $this->setTemplate("Vacio");
        $this->setTITLE("");
        $this->setModulo("MensErrorReg");
        return $this->Compilar();
    }
    public function MensUsuarioExiste(){
        $this->setTemplate("Vacio");
        $this->setTITLE("");
        $this->setModulo("MensUsuarioExiste");
        return $this->Compilar();
    }
    
    public function MensErrorLogin(){
        $this->setTemplate("Vacio");
        $this->setTITLE("");
        $this->setModulo("MensErrorLogin");
        return $this->Compilar();
    }
    
    public function MensOKProy(){
        $this->setTemplate("Vacio");
        $this->setTITLE("");
        $this->setModulo("MensOKProy");
        return $this->Compilar();
    }
    public function MensErrorProy(){
        $this->setTemplate("Vacio");
        $this->setTITLE("");
        $this->setModulo("MensErrorProy");
        return $this->Compilar();
    }
    
    public function MensOKOClave(){
        $this->setTemplate("Vacio");
        $this->setTITLE("");
        $this->setModulo("MensOKOClave");
        return $this->Compilar();
    } 
    public function MensErrorOClave(){
        $this->setTemplate("Vacio");
        $this->setTITLE("");
        $this->setModulo("MensErrorOClave");
        return $this->Compilar();
    }
}