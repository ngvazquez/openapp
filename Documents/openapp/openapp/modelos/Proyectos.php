<?php
class MProyectos extends MESIS {
    protected $Tabla = 'proyectos';
    protected $PriKey = "Id";

    protected $Id;
    protected $Key;
    protected $Estado;
    protected $Responsable;
    protected $Nombre;
    protected $Categoria;
    protected $Logo;
    protected $Gradoavance;
    protected $Descripcion;
    protected $DescripcionDetallada;
    protected $Competencia;
    protected $PDF_1C;
    protected $PDF_2C;
    protected $Fechacarga;
    protected $FechaCargaC;
    
    public function getProyectosXR($IdResp){
        $SQL = "SELECT ".$this->PriKey." FROM ".$this->Tabla." WHERE Responsable = ".$IdResp;
        
        
        return $this->FetchRows($SQL);
    }
    public function getAllKeys(){
        $ListKeyDB = $this->SQL_SELECT("`Key`");
        $ListKey = array();
        foreach($ListKeyDB as $Keys){
            $ListKey[] = $Keys["Key"];
        }
        
        return $ListKey;
    }
    
    public function AbrirXKey($key){
        $SQL = "SELECT *
                FROM `proyectos`
                WHERE `key` LIKE '".$key."'";
        
        
        return $this->FetchRows($SQL);
    }
}