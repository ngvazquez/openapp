<?php
class MESIS extends MySQL{
    protected $Tabla = '';
    protected $PriKey = "";

    public function __call($methodName, $args) {
        if (preg_match('~^(set|get)([A-Z])(.*)$~', $methodName, $matches)) {
            $property = strtoupper($matches[2]) . strtolower($matches[3]);
            switch($matches[1]) {
                case 'set':
                    $this->checkArguments($args, 1, 1, $methodName);
                    return $this->set($property, $args[0]);
                case 'get':
                    $this->checkArguments($args, 0, 0, $methodName);
                    return $this->get($property);
                case 'default':
                    throw new MemberAccessException('Method ' . $methodName . ' not exists');
            }
        }
    }

    private function get($property){
        //$Son = get_called_class();

        $ExistVar = false;

        eval('$ExistVar = isset($this->'.$property.');');

        if($ExistVar){
            $Var = "";
            eval('$Var = $this->'.$property.';');
            return $Var;
        }
        return false;
    }

    private function set($property, $value){
        eval('$this->'.$property.' = $value;');
    }

    private function checkArguments(array $args, $min, $max, $methodName) {
        $argc = count($args);
        if ($argc < $min || $argc > $max) {
            throw new MemberAccessException('Method ' . $methodName . ' needs minimaly ' . $min . ' and maximaly ' . $max . ' arguments. ' . $argc . ' arguments given.');
        }
    }

    public function SQL_SELECT($Campos = "*", $Where = false){
        return MySQL::select($this->Tabla, $Campos, $Where);
    }

    public function Borrar(){
        $Id = "";
        eval('$Id = $this->get'.$this->PriKey.'();');

        if($Id != ""){
            MySQL::delete($this->Tabla, $this->PriKey." = ".$Id);

            $this->Clear();
            return true;
        }

        return false;
    }

    public function Grabar(){
        $Columnas = $this->columns($this->Tabla);

        $iValue = array();//["Field"]
        foreach($Columnas as $Columna){
            $Campo = ucfirst($Columna["Field"]);
            
            if($Campo != $this->PriKey){
                $Valor = "";
                eval('$Valor = $this->get'.$Campo.'();');
                if($Valor != "" AND !is_null($Valor)){
                    $iValue[$Columna["Field"]] = $Valor;
                }
            }
        }

        $Id = "";
        eval('$Id = $this->get'.$this->PriKey.'();');
        if($Id != "" AND !is_null($Id)){
            $this->update($this->Tabla, $iValue, $this->PriKey." = '".$Id."'");
        }else{
            $this->insert($this->Tabla, $iValue);
            //eval('$this->set'.$this->PriKey.'('.$this->insert_id().');');
            $Id = $this->insert_id();
        }
        
        $this->Abrir($Id);
    }

    public function Abrir($PriKey){
        $this->Clear();
        
        $Select = $this->select_row($this->Tabla, "*", $this->PriKey." = ".$PriKey);
        foreach($Select as $Campos => $Valor){
            $Campos = ucfirst(strtolower($Campos));
            eval('$this->set'.$Campos."('".$Valor."');");
        }
    }

    public function Clear(){
        $Columnas = $this->columns($this->Tabla);
        foreach($Columnas as $Columna){
            $Campo = ucfirst($Columna["Field"]);

            eval('$Valor = $this->set'.$Campo.'("");');
        }
    }
}