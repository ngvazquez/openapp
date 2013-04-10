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
    
    public function Registro(){
        $this->setTemplate("Mails");
        $this->setModulo("Registro");

        return $this->Compilar();
    }
}

?>
