<?php
class MNoticias extends MESIS {
    protected $Tabla = 'noticias';
    protected $PriKey = "Id";

    protected $Id;
    protected $Nombre;
    protected $Subtitulo;
    protected $Descripcion;
    protected $Imagen;
    protected $Link;

   public function getGanadoresAleatorio($CantADevolver){
        $SQL = 'SELECT * FROM noticias ORDER BY RAND( ) LIMIT '.$CantADevolver;
        return $this->FetchRows($SQL);
    }
    
}

