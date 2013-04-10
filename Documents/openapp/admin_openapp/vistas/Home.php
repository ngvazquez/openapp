<?php
class VHome extends VESIS {
    public function Home(){
        $this->setTemplate("openapp_admin");
        $this->setTITLE("Open App :: Admin");
        $this->addCSS("estilos.css");
        $this->addJS("script.js");
        $this->setModulo("Inicio");
        
        return $this->Compilar();
    }
    
   public function ExaminarArchivo(){
        $this->setTemplate("Vacio");
        $this->setModulo("ExaminarArchivo");
        
        return $this->Compilar();
    }
    
    public function Login(){
        $this->setTemplate("openapp_admin");
        $this->setTITLE("Open App :: Admin");
        $this->addCSS("estilos_admin.css");
        $this->addJS("script.js");
        $this->setModulo("login");
        
        return $this->Compilar();
    }
    
    public function ListProy(){
        $this->setTemplate("openapp_admin");
        $this->setTITLE("Open App :: Administrador :: Proyectos");
        $this->addCSS("estilos_admin.css");
        $this->addJS("script.js");
        $this->addJS("tabs.js");
        $this->setModulo("list_proyectos");
        return $this->Compilar();
    }
    
    public function VerProy(){
        $this->setTemplate("Vacio");
        $this->setModulo("VerProyecto");
        return $this->Compilar();
    }
    
    public function VerPdf(){
        /*
        $this->setTemplate("openapp_admin");
        $this->setModulo("VerPdf");
        return $this->Compilar();
        */
        $extencion = explode(".", GET::take("id"));
        switch($extencion[1]){
            case "pdf":$aplication= "pdf";
                break;
            case "doc":$aplication = "msword";
                break;
            case "docx":$aplication = "msword";
                break;
            case "pps":$aplication = "powerpoint";
                break;
            case "ppsx":$aplication = "powerpoint";
                break;
            case "ppt":$aplication = "powerpoint";
                break;
            case "pptx":$aplication = "powerpoint";
                break;
        }

        $tam = filesize("archivos/pdf/".GET::take("id"));

        header("Content-type: application/".$aplication);
        header("Content-Length: $tam"); 
        header("Content-Disposition: inline; filename=archivos/pdf/".GET::take("id"));

        $file="archivos/pdf/".GET::take("id");
        readfile($file);
    }
    
    public function VerPdfAprov(){
        /*
        $this->setTemplate("openapp_admin");
        $this->setModulo("VerPdf");
        return $this->Compilar();
        */
        $extencion = explode(".", GET::take("id"));
        switch($extencion[1]){
            case "pdf":$aplication= "pdf";
                break;
            case "doc":$aplication = "msword";
                break;
            case "docx":$aplication = "msword";
                break;
            case "pps":$aplication = "powerpoint";
                break;
            case "ppsx":$aplication = "powerpoint";
                break;
            case "ppt":$aplication = "powerpoint";
                break;
            case "pptx":$aplication = "powerpoint";
                break;
        }

        $tam = filesize("archivos/Aprobacion/".GET::take("id"));

        header("Content-type: application/".$aplication);
        header("Content-Length: $tam"); 
        header("Content-Disposition: inline; filename=archivos/Aprobacion/".GET::take("id"));

        $file="archivos/Aprobacion/".GET::take("id");
        readfile($file);
    }
    
}