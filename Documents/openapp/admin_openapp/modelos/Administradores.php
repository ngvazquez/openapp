<?php
class MAdministradores extends MESIS {
    protected $Tabla = 'administradores';
    protected $PriKey = "Id";

    protected $Id;
    protected $Tipo;
    protected $User;
    protected $Pass;
    
   public function BuscarLogin($User, $Pass){
        $SQL = "SELECT ".$this->PriKey." FROM ".$this->Tabla." WHERE User = '".$User."' AND Pass = '".$Pass."'";
        $Usuario = $this->FetchRows($SQL);
        
        if(count($Usuario) > 0){
            return $Usuario[0][$this->PriKey];
        }
        
        return false;
    }
    
    public function BuscarIdUser($User){
         $SQL = "SELECT ".$this->PriKey." FROM ".$this->Tabla." WHERE User LIKE '".$User."'";
         
         return $this->FetchRows($SQL);
    }
}