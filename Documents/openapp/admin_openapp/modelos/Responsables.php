<?php
class MResponsables extends MESIS {
    protected $Tabla = 'responsables';
    protected $PriKey = "Id";

    protected $Id;
    protected $Dni;
    protected $Nombre;
    protected $Apellido;
    protected $FechaNacimiento;
    protected $Provincia;
    protected $Localidad;
    protected $Email;
    protected $Telefono;
    protected $Clave;
    
    public function BuscarLogin($User, $Pass){
        $SQL = "SELECT ".$this->PriKey." FROM ".$this->Tabla." WHERE Email = '".$User."' AND Clave = '".$Pass."'";
        $Usuario = $this->FetchRows($SQL);
        if(count($Usuario) > 0){
            return $Usuario[0][$this->PriKey];
        }
        
        return false;
    }
    
    public function AbrirXEmail($Email){
        $SQL = "SELECT ".$this->PriKey." FROM ".$this->Tabla." WHERE Email = '".$Email."'";
        $Usuario = $this->FetchRows($SQL);
        if(count($Usuario) > 0){
            $this->Abrir($Usuario[0][$this->PriKey]);
            return true;
        }
        
        return false;
    }
}