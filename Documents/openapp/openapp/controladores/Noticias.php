<?php
class CNoticias {
    public function Ver(){
        
        $Noticias = new MNoticias();
        $Noticias->Abrir(GET::take("Id"));
        
        //$Not = $Noticias->SQL_SELECT('Id');
        
        
        $Vista = new VNoticias();
        $Vista->agregarFunVars(get_defined_vars());
        $Vista->Ver();
    }
}
