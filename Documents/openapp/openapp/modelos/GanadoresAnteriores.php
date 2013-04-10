<?php
class MGanadoresAnteriores extends MESIS {
    protected $Tabla = 'ganadoresanteriores';
    protected $PriKey = "Id";

    protected $Id;
    protected $Nombre;
    protected $Descripcion;
    protected $Empresa;
    protected $Plataforma;
    
    public function getGanadoresAleatorio($CantADevolver){
        $SQL = 'SELECT * FROM ganadoresanteriores ORDER BY RAND( ) LIMIT '.$CantADevolver;
        return $this->FetchRows($SQL);
    }
}