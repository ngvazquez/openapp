<?php
Class Funciones{

    public static function SQLtoFecha($FechaSQL, $Formato = "dd/mm/aaaa"){
        $FechaFinal = $Formato;

        $FechaHora = explode(" ", $FechaSQL);
        $Fecha = explode("-", $FechaHora[0]);
        
        $FechaFinal = str_replace("dd", $Fecha[2], $FechaFinal);
        $FechaFinal = str_replace("sd", self::dateGetDay($Fecha[2]), $FechaFinal);
        $FechaFinal = str_replace("mm", $Fecha[1], $FechaFinal);
        $FechaFinal = str_replace("sm", self::dateGetMonth($Fecha[1]), $FechaFinal);
        $FechaFinal = str_replace("aaaa", $Fecha[0], $FechaFinal);

        if(isset($FechaHora[1]) AND !is_null($FechaHora[1])){
            $Hora = explode(":", $FechaHora[1]);

            $FechaFinal = str_replace("hh", $Hora[0], $FechaFinal);
            $FechaFinal = str_replace("ii", $Hora[1], $FechaFinal);
            $FechaFinal = str_replace("ss", $Hora[2], $FechaFinal);
        }

        return $FechaFinal;
    }
    
    public static function FechatoSQL ($Fecha){
           $Fecha = explode ("/",$Fecha);
           
           $FechaFinal  = $Fecha[2].'-'.$Fecha[1].'-'.$Fecha[0].' 00:00:00';
           
           return $FechaFinal;
           
    }

    public static function FechaHoy($Formato = "dd/mm/aaaa"){
        $FechaFinal = $Formato;

        $FechaHoy = date("Y-m-d H:i:s");

        return self::SQLtoFecha($FechaHoy, $FechaFinal);
    }

    public static function dateGetMonth($numMes){
        switch($numMes){
            case 1: return 'Enero'; break;
            case 2: return 'Febrero'; break;
            case 3: return 'Marzo'; break;
            case 4: return 'Abril'; break;
            case 5: return 'Mayo'; break;
            case 6: return 'Junio'; break;
            case 7: return 'Julio'; break;
            case 8: return 'Agosto'; break;
            case 9: return 'Septiembre'; break;
            case 10: return 'Octubre'; break;
            case 11: return 'Noviembre'; break;
            case 12: return 'Diciembre'; break;
        }
    }

    public static function dateGetDay($dia){
        if(is_numeric($dia)){
            switch($dia){
                case 0: return 'Domingo'; break;
                case 1: return 'Lunes'; break;
                case 2: return 'Martes'; break;
                case 3: return 'Miercoles'; break;
                case 4: return 'Jueves'; break;
                case 5: return 'Viernes'; break;
                case 6: return 'Sabado'; break;
            }
        }else{
            switch($dia){
                case 'Sun': return 'Domingo'; break;
                case 'Mon': return 'Lunes'; break;
                case 'Tue': return 'Martes'; break;
                case 'Wed': return 'Miercoles'; break;
                case 'Thu': return 'Jueves'; break;
                case 'Fri': return 'Viernes'; break;
                case 'Sat': return 'Sabado'; break;
            }
        }

    }

    public static function debug(){
        $nArgs = func_num_args();
        $aArgs = func_get_args();
        echo '<html><head><style>body,pre{font-family: Arial;line-height: 25px;}.var{font-weight: bolder;}.flecha{color: #808080;}.llave{background-color: #FFCCCC;font-weight: bolder;}.int{background-color: #FCCCFC;font-weight: bolder;}.array{background-color: #FFCCCC;font-weight: bolder;}.string{background-color: #CCCCFF;font-weight: bolder;}.bool{background-color: #CCFCFC;font-weight: bolder;}.object{background-color: #FCFCCC;font-weight: bolder;}</style></head><body><pre>';
        ob_start();
        if($nArgs <= 0){
            var_dump($GLOBALS);
        }else{
            for( $i = 0; $i < $nArgs; $i++ )
            {
                var_dump($aArgs[$i]);
            }
        }
        $sVars = ob_get_clean();
        $sVars = str_replace('[','<span class="var">[',$sVars);
        $sVars = str_replace(']',']</span>',$sVars);
        $sVars = str_replace('{','<span class="llave">{</span>',$sVars);
        $sVars = str_replace('}','<span class="llave">}</span>',$sVars);
        $sVars = str_replace('=>','<span class="flecha">=></span>',$sVars);
        $sVars = str_replace('string','<span class="string">string</span>',$sVars);
        $sVars = str_replace('int','<span class="int">int</span>',$sVars);
        $sVars = str_replace('array','<span class="array">array</span>',$sVars);
        $sVars = str_replace('bool','<span class="bool">bool</span>',$sVars);
        $sVars = str_replace('object','<span class="object">object</span>',$sVars);
        echo $sVars , '</pre></body></html>';
        die();
    }

    public static function Location($sLocation){
        if(headers_sent()){
            echo "<script language=\"javascript\">window.location.replace('$sLocation')</script>";
        }else{
            header("Location: $sLocation");
        }
        die();
    }
    
    public static function PageBack ($nPage){
        
        echo "<script language=\"javascript\">window.history.back(".$nPage.")</script>";        
        die();
    }

    public static function GenerarClave($cantCaracteres){
        $Caracteres = "1234567890abcdefghijklmnopqrstuvwxyz";
        $Clave = "";

        for($i=0;$i<$cantCaracteres;$i++) {
                $Clave .= $Caracteres{rand(0,35)};
        }

        return $Clave;
    }

    public static function OrdenarArrayXCampo($toOrderArray, $field, $inverse = false){
        $position = array();
        $newRow = array();
        foreach ($toOrderArray as $key => $row) {
            $position[$key]  = $row[$field];
            $newRow[$key] = $row;
        }
        if ($inverse) {
            arsort($position);
        }else {
            asort($position);
        }
        $returnArray = array();
        foreach ($position as $key => $pos) {
            $returnArray[] = $newRow[$key];
        }
        return $returnArray;
    }

}