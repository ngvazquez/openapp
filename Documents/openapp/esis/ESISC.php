<?php
class Config{
    private static $DB_NAME = '';
    private static $DB_HOST = '';
    private static $DB_USER = '';
    private static $DB_PASS = '';

    private static $ModuloPrincipal = 'Home';
    private static $ModuloError404 = 'Home';
    private static $MetodoPrincipal = "Run";
    
    private static $CarpetaControlador = "controladores";
    private static $CarpetaModelo = "modelos";
    private static $CarpetaVista = "vistas";

    public static function Take($Valor){
        $Return = false;
        eval('$Return = self::$'.$Valor.';');
        return $Return;
    }
}
