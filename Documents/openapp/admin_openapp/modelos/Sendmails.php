<?php
class MSendmails extends MESIS{
    protected $Tabla = 'sendmails';
    protected $PriKey = "Id";

    protected $Id;
    protected $Proyecto;
    protected $Tipo;
    
    public function CantMailSend ($IdP, $TipoMail){
        $Query = "SELECT sendmails.id FROM sendmails WHERE Proyecto = ".$IdP." AND Tipo = ".$TipoMail;
        $Return = $this->FetchRows($Query);
        
        return count($Return) ;
    }
}
