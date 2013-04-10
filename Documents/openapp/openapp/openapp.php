<?php

class openapp {
    
    private static $ActivarFormsInscripcion = true;
    
    public static function SetLogin($User, $Pass){
        $Responsable = new MResponsables();
        $IdLogin = $Responsable->BuscarLogin($User, md5($Pass));
        if($IdLogin !== false){
            $Responsable->Abrir($IdLogin);

            $_SESSION["LOGIN"]["Very"] = md5($Responsable->getId());
            $_SESSION["LOGIN"]["User"] = $Responsable->getEmail();
            $_SESSION["LOGIN"]["Pass"] = $Responsable->getClave();
            $_SESSION["LOGIN"]["Show"] = $Responsable->getNombre()." ".$Responsable->getApellido();
            
            return true;
            
        }else{
            return false;
        }
    }
    
    public static function isLogin(){
        if(isset($_SESSION["LOGIN"]["User"]) AND !is_null($_SESSION["LOGIN"]["User"])){
            $Responsable = new MResponsables();
            if(md5($Responsable->BuscarLogin($_SESSION["LOGIN"]["User"], $_SESSION["LOGIN"]["Pass"])) == $_SESSION["LOGIN"]["Very"]){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    public static function EnviarMailPostulate($Proyecto, $Key){

        $Responsable = new MResponsables();
        $Responsable->Abrir($Proyecto->getResponsable());
        
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= 'From: Open APP <info@openapp.com>' . "\r\n";
       
        ob_start();

            $Mail = new VMails();
            $Mail->agregarFunVars(get_defined_vars());
            $Mail->Postulacion();

        $HTML = ob_get_contents();

        ob_end_clean();

        mail($Responsable->getEmail(), "Termina de postular el proyecto", $HTML, $headers);
        
        
        /*****************/
        ob_start();

            $Mail = new VMails();
            $Mail->agregarFunVars(get_defined_vars());
            $Mail->MensajeVideo();

        $HTML = ob_get_contents();

        ob_end_clean();
        
        mail($Responsable->getEmail(), "Participa de la capacitacion virtual que te acerca Open App", $HTML, $headers);
    }
    
    public static function EnviarMailRegistro($Responsable, $Clave){
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= 'From: Open APP <info@openapp.com>' . "\r\n";
        ob_start();

            $Mail = new VMails();
            $Mail->agregarFunVars(get_defined_vars());
            $Mail->Registro();

        $HTML = ob_get_contents();
        ob_end_clean();
        
        mail($Responsable->getEmail(), "Registro Correcto de tu proyecto", $HTML, $headers);
    }
    
    public static function EnviarMailOClave($Responsable, $NClave){
        $Clave = $NClave;
        
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= 'From: Open APP <info@openapp.com>' . "\r\n";
       
        ob_start();

            $Mail = new VMails();
            $Mail->agregarFunVars(get_defined_vars());
            $Mail->Registro();

        $HTML = ob_get_contents();
        ob_end_clean();
        
        mail($Responsable->getEmail(), "Cambio de Clave", $HTML, $headers);
    }
    
    public static function EnviarMailInscripcion($Proyecto){
        $Responsable = new MResponsables();
        $Responsable->Abrir($Proyecto->getResponsable());
        
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= 'From: Open APP <info@openapp.com>' . "\r\n";
        
        ob_start();

            $Mail = new VMails();
            $Mail->agregarFunVars(get_defined_vars());
            $Mail->CargaProyecto();

        $HTML = ob_get_contents();
        ob_end_clean();

        mail($Responsable->getEmail(), "Inscripcion del proyecto", $HTML, $headers);
    }
    
  public static function EnviarMailConsulta($Email, $Nombre, $Asunto, $Comentario){
        $Coment = htmlentities($Comentario, ENT_QUOTES, "UTF-8");
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: '.$Nombre.' <'.$Email.'>' . "\r\n";
        ob_start();

            $Mail = new VMails();
            $Mail->agregarFunVars(get_defined_vars());
            $Mail->Consulta($Email, $Nombre, $Coment);

        $HTML = ob_get_contents();
        ob_end_clean();

        mail("info@openapp.com.ar", $Asunto, $HTML, $headers);
    }
    
    public static function GenerarClave($cantChar = 5){
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $cad = "";

        for($i=0;$i<$cantChar;$i++) {
                $cad .= substr($str,rand(0,strlen($str)),1);
        }

        return $cad;
    }
    
    public static function getActivoFormsHome(){
        return self::$ActivarFormsInscripcion;
    }
    
    public static function getMensajeDesactivoH(){
        return urlencode("<div style='position:relative;'><div align='right' style='position:absolute;right:5px;top:2px'><img class='PunteroCursor' src='".CESIS::$ROOT."img/cerrar_negro.png' onclick='MensajeAuto(true);'/></div><div class='Clr'></div><div style='background-color:#f49200;color:#FFF;width:150px;padding:15px;padding-top:25px;'>El <b>19 de Octubre</b> se abre la inscripci&oacute;n al concurso.</div></div>");
    }
}

?>
