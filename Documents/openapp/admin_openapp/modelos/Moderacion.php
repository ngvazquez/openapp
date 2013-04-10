<?php
class MModeracion extends MESIS {
    protected $Tabla = 'moderacion';
    protected $PriKey = "Id";

    protected $Id;
    protected $Administrador;
    protected $Proyecto;
    protected $Estado;
    protected $Motivo;
    protected $Fecha;
    
    public function Abrir_XProyectoEstado($Proyecto, $Estado){
        
        $SQL = "SELECT id FROM ".$this->Tabla." WHERE Proyecto = ".$Proyecto." AND Estado = ".$Estado;
        
        $Select = MySQL::FetchRows($SQL);
        
        $this->Abrir($Select[0]["id"]);
    }
}
