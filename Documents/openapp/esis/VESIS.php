<?php
class VESIS{
    private $JS = array("prototype.js", "scriptaculous.js", "validar.js");
    private $CSS = array();
    private $META = array();
    private $PROPERTY = array();
    private $Analytics = array();
    private $Titulo = "ESIS :: Sistema de WEBs";
    private $Template = "default";
    private $Modulo = "Home";
    /**/
    private $DirTemp = "template";
    /**/
    private $VarsPrint = array();

    protected function addJS($URI_JS){
        $this->JS[] = $URI_JS;
    }
    protected function addCSS($URI_CSS){
        $this->CSS[] = $URI_CSS;
    }
    public function addPROPERTY($Name, $Value){  //Funcion para agregar meta de facebook
        $this->PROPERTY[$Name] = $Value;
    }
    protected function addMETA($Name, $Value){
        $this->META[$Name] = $Value;
    }
    protected function setTITLE($Title){
        $this->Titulo = $Title;
    }
    protected function addAnalytics($KeyAnalytics){
        $this->Analytics[] = $KeyAnalytics;
    }

    protected function setModulo($Modulo){
        $this->Modulo = $Modulo;
        return true;
    }

    protected function setTemplate($Template){
        $this->Template = $Template;
    }

    public function agregarFunVars($FunGETVars){
        if(is_array($FunGETVars)){
            foreach($FunGETVars as $Nombre => $Valor){
                $this->agregarVar($Nombre, $Valor);
            }
        }
    }
    public function agregarVar($Nombre, $Valor){
        $this->VarsPrint[$Nombre] = $Valor;
    }

    protected function Compilar(){
        $header = "";
        //$header .= "<title>".$this->Titulo."</title>"."\n";
        foreach($this->PROPERTY as $Name => $Content){
            $header .= '<meta property="'.$Name.'" content="'.$Content.'" />'."\n";
        }
        foreach($this->META as $Name => $Content){
            $header .= '<meta name="'.$Name.'" content="'.$Content.'" />'."\n";
        }
        foreach($this->JS as $ArchivoJS){
            $URI = $this->ComprobarFile($ArchivoJS, "js");
            $header .= "<script type='text/javascript' src='".$URI."'></script>"."\n";
        }
        foreach($this->CSS as $ArchivoCSS){
            $URI = $this->ComprobarFile($ArchivoCSS, "css");
            $header .= '<link rel="stylesheet" type="text/css" href="'.$URI.'" media="screen" />'."\n";
        }

        $FileDir = $this->ComprobarHTML("index.html");

        $handle = fopen(ESIS_PB.$FileDir, "r");
        $Index = fread($handle, filesize(ESIS_PB.$FileDir));
        fclose($handle);

        while(($Inicio = strpos($Index, "<esis::")) !== false){
            
            $Inicio2 = $Inicio + strlen("<esis::");
            $Fin2 = strpos($Index, "/>", $Inicio2);
            $Fin = $Fin2 + strlen("/>");

            $Comando = trim(substr($Index, ($Inicio2), ($Fin2-$Inicio2)));
            $Remplace = substr($Index, ($Inicio), ($Fin-$Inicio));
            
            $Comandos = explode("::", $Comando);

            if(strtolower($Comandos[0]) == "seccion"){
                $File = ESIS_PB.$this->ComprobarHTML($Comandos[1].".html");

                $handle = fopen($File, "r");
                $Contenido = fread($handle, filesize($File));
                fclose($handle);

                $Index = str_replace($Remplace, $Contenido, $Index);
            }else if(strtolower($Comandos[0]) == "modulo"){
                $File = ESIS_PB.$this->ComprobarHTML($this->Modulo.".html");

                $handle = fopen($File, "r");
                $Contenido = fread($handle, filesize($File));
                fclose($handle);

                $Index = str_replace($Remplace, $Contenido, $Index);
            }else if(strtolower($Comandos[0]) == "title"){
                $Index = str_replace($Remplace, $this->Titulo, $Index);
            }else{
                $Index = str_replace($Remplace, $$Comando, $Index);
            }
        }
        
        $FileTemp = tempnam(ESIS_PB."/temp/", md5($this->Modulo).'_'.md5(Funciones::FechaHoy()));
        
        if($FileTemp !== false){
            if (!$handle = fopen($FileTemp, 'w')) {
                 echo "Error al Abrir ($FileTemp)";
                 exit;
            }

            if (fwrite($handle, $Index) === FALSE) {
                echo "Error al Escribir ($filename)";
                die();
            }
            fclose($handle);
        }

        $this->IncludeFile($FileTemp);

        unlink($FileTemp);
    }

    private function ComprobarHTML($File){
        if(is_file(ESIS_PB."/".$this->DirTemp."/".$this->Template."/".$File)){
            return "/".$this->DirTemp."/".$this->Template."/".$File;
        }else{
            return "/".$this->DirTemp."/default/".$File;
        }
    }
    private function ComprobarFile($File, $Dir){
        if(is_file(ESIS_PB."/".$this->DirTemp."/".$this->Template."/".$Dir."/".$File)){
            return CESIS::$ROOT.$this->DirTemp."/".$this->Template."/".$Dir."/".$File;
        }else if(is_file(ESIS_PB."/".$this->DirTemp."/default/".$Dir."/".$File)){
            return CESIS::$ROOT.$this->DirTemp."/default/".$Dir."/".$File;
        }else{
            return CESIS::$ROOT.$Dir."/".$File;
        }
    }

    private function IncludeFile($File){
        if(count($this->VarsPrint) > 0){
            foreach($this->VarsPrint as $ViewKeys => $ViewValores){
                if(!isset($$ViewKeys)){
                    $$ViewKeys = $ViewValores;
                }
            }
        }

        include($File);
    }
}