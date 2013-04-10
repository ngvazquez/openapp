<?php
    define('ESIS_PB', dirname(__FILE__) );
    define('ESIS_DS', DIRECTORY_SEPARATOR );

    include(ESIS_PB.ESIS_DS."esis".ESIS_DS."CESIS.php");

    $OpenApp = new CESIS();
    $OpenApp->enProduccion(false);
    $OpenApp->RutasAmigables(true);

    $OpenApp->NombreSitio("openapp");

    $OpenApp->EjecutSystem("M+V+C");
    
    $OpenApp->ProcesarREQUEST();

    $OpenApp->SITIO();