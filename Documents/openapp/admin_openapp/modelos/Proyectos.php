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
    protected $Plataforma;
    protected $Autores;
    protected $Logo;
    protected $Poravance;
    protected $CantSocios;
    protected $Web;
    protected $Descripcion;
    protected $Archivo;
    protected $Fechacarga;
    protected $ShortUri;
    protected $ArchivoAprobado;
    
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
    
    public function getXEstado($Estado){
        $SQL = "SELECT * FROM ".$this->Tabla." WHERE Estado = ".$Estado;
        
        return $this->FetchRows($SQL);
    }
    
    public function getXPaginaYEstado($limitInf,$tamPag,$Estado){
        $SQL = "SELECT * FROM ".$this->Tabla." WHERE Estado IN (".$Estado.") LIMIT ".$limitInf." , ".$tamPag;
        
        return $this->FetchRows($SQL);
    }
    
    public function getXPaginaYEstadoConFiltro($limitInf,$tamPag,$Estado,$Catego){
        //$SQL = "SELECT * FROM ".$this->Tabla." WHERE Categoria IN ( ".$Catego.") AND Estado = ".$Estado." LIMIT ".$limitInf." , ".$tamPag;
        $Where = "Categoria IN ( ".$Catego.")";
        return $this->getProyectos($Estado, $Where, "ASC", false, $limitInf.", ".$tamPag);//$this->FetchRows($SQL);
    }
    
    public function getProyectos($IdsEstado, $Where = false, $Orden = false, $Estado = false, $Limit = false){
        $WhereFinal = "";
        $OrderByFila = "";
        //$Limit = "";
        
        if(is_array($IdsEstado)){
            $ListIds = "";
            foreach($IdsEstado as $IdEstado){
                if($ListIds != ""){
                    $ListIds .= ",";
                }
                $ListIds .= $IdEstado;
            }
            $WhereFinal .= "Estado IN (".$ListIds.")";
        }else{
            $WhereFinal .= "Estado = ".$IdsEstado;
        }
        
        if($Where !== false){
            $WhereFinal .= " AND (".$Where.")";
        }
        
        if($Limit !== false){
            $Limit = "LIMIT ".$Limit;
        }
        
        if($Estado === false){
            $OrderByFila .= " ORDER BY FechaCargaC ".$Orden."";
        }
        
        if( $WhereFinal != ""){
            $SQL = "SELECT * FROM ".$this->Tabla." WHERE ".$WhereFinal." ".$OrderByFila." ".$Limit;
        }else{
            $SQL = "SELECT * FROM ".$this->Tabla." ".$OrderByFila." ".$Limit;
        }
       
        $ListaProyectos = $this->FetchRows($SQL);
        
        foreach($ListaProyectos as $Key => $Proyecto){
            if($Estado === false){
                $ListaProyectos[$Key]["ShowFecha"] = Funciones::SQLtoFecha($Proyecto["FechaCargaC"]);
                $ListaProyectos[$Key]["OrdenFecha"] = $Proyecto["FechaCargaC"];
            }else if($Estado > 2 OR $Estado == 0){
                $Moderacion = new MModeracion();
                $Moderacion->Abrir_XProyectoEstado($Proyecto["id"], $Estado);
                
                $ListaProyectos[$Key]["ShowFecha"] = Funciones::SQLtoFecha($Moderacion->getFecha());
                $ListaProyectos[$Key]["OrdenFecha"] = $Moderacion->getFecha();
            }else{
                
            }
        }
        
        $ListaProyectos = Funciones::OrdenarArrayXCampo($ListaProyectos, "OrdenFecha");
        
        return $ListaProyectos;
    }
}