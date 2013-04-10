<?php
class Admin_openapp {
    public static function SetLogin($User, $Pass){
        $Administrador = new MAdministradores();
        $IdLogin = $Administrador->BuscarLogin($User, md5($Pass));
        
        if($IdLogin !== false){
            $Administrador->Abrir($IdLogin);

            $_SESSION["LOGIN_ADMIN"]["Very"] = md5($Administrador->getId());
            $_SESSION["LOGIN_ADMIN"]["User"] = $Administrador->getUser();
            $_SESSION["LOGIN_ADMIN"]["Pass"] = $Administrador->getPass();
            
            return true;
            
        }else{
            return false;
        }
    }
    
    public static function isLogin(){
        if(isset($_SESSION["LOGIN_ADMIN"]["User"]) AND !is_null($_SESSION["LOGIN_ADMIN"]["User"])){
            $Administrador = new MAdministradores();
            if(md5($Administrador->BuscarLogin($_SESSION["LOGIN_ADMIN"]["User"], $_SESSION["LOGIN_ADMIN"]["Pass"])) == $_SESSION["LOGIN_ADMIN"]["Very"]){
                return true;
            }else{
                session_destroy();
                return false;
            }
        }else{
            return false;
        }
    }
}

?>
