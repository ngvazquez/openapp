<?php

/**
 * 
 * Clase DataBase [Edbase]
 *
 * @developer  
 * @version 3.0
 Nicolas Vazquez
 *
 */

class MySQL{
    //
    const EDBASE_ERROR_NO_CONNECTION = "No existe una conexion previa!";
    const EDBASE_ERROR_CONNECTION = "No se pudo conectar ha la DB!";
    const EDBASE_SHOW_ERROR = "<p>\n<h1>Edbase Error</h1>\n<dl>\n\t<dt>Numero:</dt>\n\t<dd>%s</dd>\n\t<dt>Descripcion:</dt>\n\t<dd>%s</dd>\n\t<dt>Query:</dt>\n\t<dd>%s</dd>\n\t<dt>URL Conexion:</dt>\n\t<dd>%s</dd>\n</dl>\n";
    const EDBASE_LAST_CONNECTION = "-1";
    //
    static private $Connection;
    static private $Query;
    static private $AffectedRows;
    static private $Error;
    static private $ConnectionURL;
    //
    static private $TestMode = false;
    static private $Instances;
    static private $Querys;

    public static function Connect($ConnectionURL){
        if(!MySQL::CheckResource()){
            $ConURL = parse_url($ConnectionURL);
            MySQL::$Connection = mysql_connect($ConURL["host"], $ConURL["user"], $ConURL["pass"]);

            if(!MySQL::$Connection){
                MySQL::setError(1, self::EDBASE_ERROR_CONNECTION);
                return false;
            }
            mysql_select_db( substr($ConURL["path"],1), MySQL::$Connection);

            MySQL::$ConnectionURL = $ConnectionURL;
        }
        return true;
    }

    public static function TestMode($isTestMode = NULL){
        if(!is_null($isTestMode)){
            MySQL::$TestMode = $isTestMode;
        }
        return MySQL::$TestMode;
    }

    protected static function getError(){
        return MySQL::$Error;
    }

    protected static function getInstance($ConnectionURL = false){

        if(!$ConnectionURL){

            if(!is_array(MySQL::$Instances)){
                die(sprintf(self::EDBASE_SHOW_ERROR, 0, self::EDBASE_ERROR_NO_CONNECTION, "", "NULL"));
                return false;
            }
            $LastConnection = MySQL::$Instances[self::EDBASE_LAST_CONNECTION];
            $Instance =& MySQL::$Instances[$LastConnection];

        }else{

            MySQL::$Instances[self::EDBASE_LAST_CONNECTION] = $ConnectionURL;
            if(!isset(MySQL::$Instances[$ConnectionURL])){
                $Instance = new MySQL($ConnectionURL);
                MySQL::$Instances[$ConnectionURL] = $Instance;
                $Instance->ConnectionURL = $ConnectionURL;
            }else{
                $Instance =& MySQL::$Instances[$ConnectionURL];
            }

        }

        return $Instance;
    }

    protected static function Querys($Query = NULL){
        if(!is_null($Query)){
                MySQL::$Querys[] = $Query;
        }
        return MySQL::$Querys;
    }

    protected static function getQuery(){
        return MySQL::$Query;
    }

    protected static function select($Table, $Fields = "*", $Where = false, $Joins = "", $OrderBy = false, $OrderType = 'ASC', $Limit = false, $BefoureFrom = '', $GroupBy = false){
        // Genera la consulta
        $Query = "SELECT $BefoureFrom $Fields FROM $Table $Joins";
        // Cuando
        if($Where){
            $Query .= " WHERE $Where";
        }
        // Agrupar
        if($GroupBy){
            $Query .= " GROUP BY $GroupBy";
        }
        // Orden
        if($OrderBy){
            $Query .= " ORDER BY ";
            if(!is_array($OrderBy) || !is_array($OrderType)){
                $Query .= "$OrderBy $OrderType";
            }else{
                $Order = array();
                $OrderTotal = sizeof($OrderBy);
                for($Field = 0; $Field < $OrderTotal; $Field++){
                    $Order[] = $OrderBy[$Field]." ".$OrderType[$Field];
                }
                $Query .= implode(', ', $Order);
            }
        }
        // Limite
        if($Limit){
            $Query .= " LIMIT $Limit";
        }

        // Ejecuta la consulta
        return MySQL::FetchRows($Query);
    }

    protected static function select_field($Table, $Field, $Where, $Joins = ""){

            $Select = MySQL::select($Table, $Field, $Where, $Joins, false, false, "1");

            if(strpos($Field, ".")){
                $hasTable = strpos($Field, ".");

                $Field = substr($Field, $hasTable+1);
            }

            return $Select[0][$Field];
    }

    protected static function select_row($Table, $Fields, $Where, $Joins = ""){
        $Select = MySQL::select($Table, $Fields, $Where, $Joins, false, false, "1");

        return $Select[0];
    }

    protected static function update($Table, $Values, $Where, $Joins = false){
        $Query = 'UPDATE ' . $Table . $Joins . ' SET ';

        foreach($Values as $Column => $sValue){
            $Query .= '`' . $Column . '` = ';
            if(is_int($sValue)){
                $Query .= $sValue . ', ';
            }elseif(substr($sValue,0,4) == 'SQL:'){
                $Query .= substr($sValue,4) . ', ';
            }else{
                $Query .= '"' . str_replace('"', "'", $sValue) . '", ';
            }
        }

        $Query = substr($Query, 0, strlen($Query) - 2) . ' WHERE ' . $Where;

        MySQL::$Query = $Query;

        if(MySQL::Exec($Query)){
            return true;
        }else{
            return false;
        }

    }

    protected static function delete($Table, $Where = false){

        if ($Where){
            $Query = "DELETE FROM $Table  WHERE $Where";
        }else{
            $Query = "TRUNCATE $Table";
        }

        if( MySQL::Exec($Query)){
            return true;
        }else{
            return false;
        }
    }

    protected static function insert($Table, $Values){
        $Query = 'INSERT INTO ' . $Table;
        $Query1 = ' (';
        $Query2 = ') VALUES (';

        foreach( $Values as $Column => $Value ){
            $Query1 .= $Column . ', ';
            if (is_int($Value)){
                $Query2 .= $Value . ', ';
            }elseif(substr($Value,0,4) == 'SQL:'){
                $Query2 .= substr($Value,4) . ', ';
            }else{
                $Query2 .= '"' . addslashes($Value) . '", ';
            }
        }

        $Query1 = substr($Query1, 0, strlen($Query1) - 2);
        $Query2 = substr($Query2, 0, strlen($Query2) - 2);
        $Query = $Query . $Query1 . $Query2 . ')';

        if(MySQL::Exec($Query)){
            return MySQL::insert_id();
        }else{
            return false;
        }
    }

    protected static function list_tables(){

        $tables = array();

        $ConURL = parse_url(MySQL::ConnectionURL);

        $resourse = mysql_list_tables(substr($ConURL["path"],1), MySQL::Connection);

        $i = 0;
        while ($i < mysql_num_rows ($resourse)) {

            $tables[$i] = mysql_tablename ($resourse,$i);
            $i++;

        }

        return $tables;
    }

    protected static function columns($Tabla){
        $column = array();

        $resourse = MySQL::Exec('SHOW COLUMNS FROM `'.$Tabla.'`');

        if (mysql_num_rows($resourse) > 0) {
            while ($row = mysql_fetch_assoc($resourse)) {
                $column[] = $row;
            }
            return $column;
        }else{
            return false;
        }
    }

    protected static function insert_id(){
        return mysql_insert_id(MySQL::$Connection);
    }

    protected static function Exec($Query){
        $total_arguments = func_num_args();

        if($total_arguments > 1){
            $arguments = func_get_args();
            $Query = call_user_func_array("sprintf", $arguments);
        }

        if(!MySQL::Connect(MySQL::$ConnectionURL)){
            MySQL::setError(mysql_errno(MySQL::$Connection), mysql_error(MySQL::$Connection));
            return false;
        }

        MySQL::$Query = $Query;

        if(MySQL::TestMode()){
            MySQL::Querys($Query);
        }

        $results = mysql_query($Query, MySQL::$Connection);

        if(!$results){
            MySQL::setError(mysql_errno(MySQL::$Connection), mysql_error(MySQL::$Connection));
            return false;
        }else{
            $num_rows = @mysql_num_rows($results);

            if(!$num_rows){
                $num_rows = @mysql_affected_rows(MySQL::$Connection);
                $is_select = false;
            }else{
                $is_select = true;
            }

            MySQL::$AffectedRows = (int) $num_rows;

            return $results;
        }
    }

    protected static function setError($errorNumber, $errorText){
        $error = sprintf(self::EDBASE_SHOW_ERROR, $errorNumber, $errorText, MySQL::$Query, MySQL::$ConnectionURL);
        MySQL::$Error = $error;
        if(MySQL::TestMode()){
            die($error);
        }
    }

    protected static function CheckResource(){
        return is_resource(MySQL::$Connection);
    }

    protected static function FetchRows(){
        $arguments = func_get_args();

        if(is_array($arguments)){
            $arguments = $arguments[0];
        }

        if(method_exists('MySQL', 'Exec')){
            $results = MySQL::Exec($arguments);
        }else{
            $results = false;
        }

        if(!$results){
            return false;
        }else{
            $Rows = array();
            while($Row = mysql_fetch_assoc($results)){
                $Rows[] = $Row;
            }

            return $Rows;
        }
    }

    protected static function getAffectedRows(){
        return MySQL::$AffectedRows;
    }

}

?>
