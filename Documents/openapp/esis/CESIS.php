<?php
class CESIS {
    public static $NSitio = ""; //Nombre del Sitio [No modificar]
    public static $ROOT = "/"; //Direccion Root del sitio [No modificar]
    public static $PreROOT = ""; //Direccion PreRoot del sitio [No modificar]
    public static $RutasA = false; //Rutas Amigables [No modificar]

    protected $EstadoP = true; //En Produccion [No modificar]

    private $SModelo = false;
    private $SVista = false;
    private $SControlador = false;

    public function setPreRoot($PRE){
        self::$PreROOT = $PRE;
    }
    public function NombreSitio($Nombre = Null){
        if(!is_null($Nombre)){
            $NewNombre = preg_replace("[^A-Za-z0-9]", "", $Nombre);

            if($NewNombre == $Nombre){
                self::$NSitio = $Nombre;
                return true;
            }else{
                $this->PrintErrorSys("Nombre del sitio invalido. (No tiene que tener caracteres especiales)");
                return false;
            }
        }else{
            return self::$NSitio;
        }
    }
    public function enProduccion($Estado = Null){
        if(!is_null($Estado)){
            $this->EstadoP = (bool)$Estado;
        }else{
            return $this->EstadoP;
        }
    }
    public function RutasAmigables($Estado = Null){
        if(!is_null($Estado)){
            CESIS::$RutasA = (bool)$Estado;
        }else{
            return CESIS::$RutasA;
        }
    }
    public function EjecutSystem($TSistema){
        if($this->EstadoP){
            error_reporting(0);
            ini_set('display_errors', 0);
        }else{
            error_reporting(0);
            ini_set('display_errors', 1);
        }

        $SIS = explode("+", $TSistema);
        foreach($SIS as $SI){
            switch ($SI){
                case 'M':
                    $this->SModelo = true;
                break;
                case 'V':
                    $this->SVista = true;
                break;
                case 'C':
                    $this->SControlador = true;
                break;
            }
        }

        $this->LlamarClases();
        $this->EstablecerROOT();

        $this->LlamarConfig();

        session_name("ESIS::".$this->NombreSitio());
        session_start();

        $this->CrearEntorno();
    }
    public function ProcesarREQUEST(){
        GET::ProcesarVar();
        POST::ProcesarVar();
    }
    public function SITIO(){
        $Modulo = "";
        $Accion = "";
        if(GET::take("Modulo")){
            $Modulo = GET::take("Modulo");
        }else{
            $Modulo = Config::Take("ModuloPrincipal");
        }

        if(GET::take("Accion")){
            $Accion = GET::take("Accion");
        }else{
            $Accion = Config::Take("MetodoPrincipal");
        }

        $ModuloSC = preg_replace("[^A-Za-z0-9]", "", $Modulo);

        if($ModuloSC == $Modulo){
            $Modulo = ucfirst(strtolower($Modulo));
            $Accion = ucfirst(strtolower($Accion));

            if($this->SModelo){
                require_once ESIS_PB.ESIS_DS.$this->NombreSitio().ESIS_DS.$this->NombreSitio().".php";
                $DirModelos = dir(ESIS_PB.ESIS_DS.$this->NombreSitio().ESIS_DS."modelos".ESIS_DS);
                
                while (false !== ($Modelo = $DirModelos->read())) {
                   if(is_file($DirModelos->path."/".$Modelo)){
                       require_once $DirModelos->path."/".$Modelo;
                   }
                }
                $DirModelos->close();
            }

            if($this->SVista){
                require_once(ESIS_PB.ESIS_DS."esis".ESIS_DS."VESIS.php");
            }

            if($this->SControlador){
                $Controlador = "";
                if(class_exists('C'.$Modulo)){
                    eval('$Controlador = new C'.$Modulo.'();');
                    eval('$Controlador->'.$Accion.'();');
                }else{
                    //eval('$Controlador = new C'.Config::Take("ModuloError404").'();');
                    //eval('$Controlador->'.Config::Take("MetodoPrincipal").'();');
                }
            }else{
                if($this->SVista){
                    $Vista = "";
                    if(class_exists('V'.$Modulo)){
                        eval('$Vista = new V'.$Modulo.'();');
                        eval('$Vista->'.$Accion.'();');
                    }else{
                        eval('$Vista = new V'.Config::Take("ModuloError404").'();');
                        eval('$Vista->'.Config::Take("MetodoPrincipal").'();');
                    }
                }
            }

        }else{
            $this->PrintErrorSys("Modulo Invalido");
        }
    }

    protected function PrintErrorSys($Leyenda){
        echo $Leyenda;
        die();
    }

    private function LlamarClases(){
        require_once(ESIS_PB.ESIS_DS."esis".ESIS_DS."lib".ESIS_DS."MySQL.php");
        require_once(ESIS_PB.ESIS_DS."esis".ESIS_DS."lib".ESIS_DS."Imagenes.php");
        require_once(ESIS_PB.ESIS_DS."esis".ESIS_DS."lib".ESIS_DS."Funciones.php");
        if($this->SModelo){
            require_once(ESIS_PB.ESIS_DS."esis".ESIS_DS."MESIS.php");
        }
    }
    private function LlamarConfig(){
        require_once(ESIS_PB.ESIS_DS."esis".ESIS_DS."ESISC.php");
    }
    private function EstablecerROOT(){
        $Root = "/";
        
        $RootAbs = ESIS_PB;
            $TempCB = explode("\\", $RootAbs);
            $TempB = explode("/", $RootAbs);
        $CarpetasRoot = array();
        if(count($TempB) > 0 AND $TempB[0] != $RootAbs){
            $CarpetasRoot = array_merge($CarpetasRoot, $TempB);
        }
        if(count($TempCB) > 0 AND $TempCB[0] != $RootAbs){
            $CarpetasRoot = array_merge($CarpetasRoot, $TempCB);
        }

        $URI = $_SERVER["REQUEST_URI"];
        $CarpetasURI = explode("/", $_SERVER["REQUEST_URI"]);

        // Limpio Carpetas Root Server.
            unset($CarpetasRoot[0]);
            unset($CarpetasRoot[1]);
        //
        
        foreach($CarpetasURI as $CarpetaURI){
            if($CarpetaURI != ""){
                if(in_array($CarpetaURI, $CarpetasRoot)){
                    $Root .= $CarpetaURI."/";
                }
            }
        }

        self::$ROOT = $Root.self::$PreROOT;
    }
    private function CrearEntorno(){
        MySQL::Connect("mysql://".Config::Take("DB_USER").":".Config::Take("DB_PASS")."@".Config::Take("DB_HOST")."/".Config::Take("DB_NAME"));
        
        if($this->EstadoP){
            MySQL::TestMode(false);
        }else{
            MySQL::TestMode(true);
        }
    }
}

class Objetos{
    protected static $VarSite = array();

    public static function take($name){
        $name = ucfirst(strtolower($name));
        $Son = get_called_class();
        $Temp = array();
        eval('$Temp = '.$Son.'::$VarSite;');
        if(array_key_exists($name, $Temp)){
            eval('$Temp = '.$Son.'::$VarSite["'.$name.'"];');
            return $Temp;
        }else{
            return false;
        }
    }
    public static function set($name, $value){
        $name = ucfirst(strtolower($name));
        $Son = get_called_class();
        var_dump($Son.'::$VarSite["'.$name.'"] = "'.$value.'";');
        eval($Son.'::$VarSite["'.$name.'"] = "'.$value.'";');
        //$Son::$VarSite[$name] = $value;
    }
}

class GET extends Objetos{
    protected static $VarSite = array();

    public static function ProcesarVar(){
        if(CESIS::$RutasA){
            $Parametros = explode("/",$_GET["parametros"]);
            if(isset($Parametros[0]) AND !is_null($Parametros[0])){
                self::set("Modulo", $Parametros[0]);
            }
            if(isset($Parametros[1]) AND !is_null($Parametros[1])){
                self::set("Accion", $Parametros[1]);
            }   
            if(isset($Parametros[2]) AND !is_null($Parametros[2])){
                self::set("Id", $Parametros[2]);
            }
            if(isset($Parametros[3]) AND !is_null($Parametros[3])){
                if(count($Parametros) > 4){
                    $y = 0;
                    for($x = 3; $x < count($Parametros); $x++){
                        self::set("Otro_".$y, $Parametros[$x]);
                        $y++;
                    }
                }else{
                    self::set("Otro_0", $Parametros[3]);
                }
            }

        }else{
            foreach($_GET as $name => $value){
                self::set($name, $value);
            }
        }

        return true;
    }
    public static function take($name){
        $name = ucfirst(strtolower($name));
        //$Son = get_called_class();
        //$Temp = array();
        //eval('$Temp = '.$Son.'::$VarSite;');
        if(array_key_exists($name, self::$VarSite)){
            return self::$VarSite[$name];
        }else{
            return false;
        }
    }
    public static function set($name, $value){
        $name = ucfirst(strtolower($name));
        //$Son = get_called_class();
        //var_dump($Son.'::$VarSite["'.$name.'"] = "'.$value.'";');
        //eval($Son.'::$VarSite["'.$name.'"] = "'.$value.'";');
        self::$VarSite[$name] = $value;
    }
}
class POST extends Objetos{
    protected static $VarSite = array();

    public static function ProcesarVar(){
        foreach($_POST as $name => $value){
            self::set($name, $value);
        }
        return true;
    }
    public static function take($name){
        $name = ucfirst(strtolower($name));
        //$Son = get_called_class();
        //$Temp = array();
        //eval('$Temp = '.$Son.'::$VarSite;');
        if(array_key_exists($name, self::$VarSite)){
            return self::$VarSite[$name];
        }else{
            return false;
        }
    }
    public static function set($name, $value){
        $name = ucfirst(strtolower($name));
        //$Son = get_called_class();
        //var_dump($Son.'::$VarSite["'.$name.'"] = "'.$value.'";');
        //eval($Son.'::$VarSite["'.$name.'"] = "'.$value.'";');
        self::$VarSite[$name] = $value;
    }
}


function __autoload($NClase){

    $Categoria = substr($NClase, 0, 1);
    $ClaseFile = substr($NClase, 1, strlen($NClase));
    $ClassArch = strtolower($ClaseFile).".php";

    $Ruta = ESIS_PB.ESIS_DS.CESIS::$NSitio;
    switch(strtolower($Categoria)){
        case 'c':
        case 'C':
            $Ruta .= ESIS_DS.Config::Take("CarpetaControlador");
        break;
        case 'v':
        case 'V':
            $Ruta .= ESIS_DS.Config::Take("CarpetaVista");
        break;
        case 'm':
        case 'M':
            $Ruta .= ESIS_DS.Config::Take("CarpetaModelo");
        break;
        default:
            $Ruta .= "";
            $ClassArch = strtolower($NClase).".php";
        break;
    }

    //$Ruta .= ESIS_DS.$ClassArch;
    $ControlDir = dir($Ruta);
    while (false !== ($Archivo = $ControlDir->read())) {
        if(is_file($ControlDir->path.ESIS_DS.$Archivo)){
            if(strtolower($Archivo) == $ClassArch){
                $ArchivoFinal = $Archivo;
            }
        }
    }
    $ControlDir->close();
    
    $Ruta .= ESIS_DS.$ArchivoFinal;

    if(is_file($Ruta)){
        require_once($Ruta);
    }else{
        return false;
    }
}


if(!function_exists('get_called_class'))
 {
  class classTools
   {
    static $i = 0;
    static $fl = null;
    static function get_called_class()
     {
      $bt = debug_backtrace();
      if(self::$fl == $bt[2]['file'].$bt[2]['line']) self::$i++;
      else {
       self::$i = 0;
       self::$fl = $bt[2]['file'].$bt[2]['line'];}
      $lines = file($bt[2]['file']);
      preg_match_all('/([a-zA-Z0-9\_]+)::'.$bt[2]['function'].'/', $lines[$bt[2]['line']-1], $matches);
      
      return $matches[1][self::$i];
     }
   }
  function get_called_class()
   {
    return classTools::get_called_class();
   }
 }
