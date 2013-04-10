<?php
class VMails extends VESIS{
    public function Activo(){
        $this->setTemplate("Mails");
        $this->setModulo("MensajesActivo");
        return $this->Compilar();
    }
    
    public function Top180(){
        $this->setTemplate("Mails");
        $this->setModulo("Mensajes180");

        return $this->Compilar();
    }
    
    public function Top60(){
        $this->setTemplate("Mails");
        $this->setModulo("Mensajes60");

        return $this->Compilar();
    }
    
    public function TopGan(){
        $this->setTemplate("Mails");
        $this->setModulo("MensajesGan");

        return $this->Compilar();
    }
    
    public function CargaPdf(){
        $this->setTemplate("Mails");
        $this->setModulo("CargaPdf");

        return $this->Compilar();
    }
    
}

?>
