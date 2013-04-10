<?php
class VMails extends VESIS{
    public function CargaProyecto(){
        $this->setTemplate("Mails");
        $this->setModulo("CargaProyecto");
        return $this->Compilar();
    }
    
    public function Postulacion(){
        $this->setTemplate("Mails");
        $this->setModulo("PostulaProyecto");

        return $this->Compilar();
    }
    
    public function MensajeVideo(){
        $this->setTemplate("Mails");
        $this->setModulo("MensajeVideo");

        return $this->Compilar();
    }
    
    public function Registro(){
        $this->setTemplate("Mails");
        $this->setModulo("Registro");

        return $this->Compilar();
    }
    
   public function Consulta(){
        $this->setTemplate("Mails");
        $this->setModulo("Consulta");

        return $this->Compilar();
    }
}

?>
