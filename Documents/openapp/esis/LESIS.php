<?php
class LESIS {
    public function Run($Modulo){
        if(strtolower($Modulo) == "logout"){
            session_destroy();
            Funciones::Location("/");
        }else{
            if(!isset($_SESSION["UserLogin"])){
                $_SESSION["UserLogin"] = NULL;
            }

            if(is_null($_SESSION["UserLogin"])){
                $this->PedirLogin();
            }else{
                if($_SESSION["UserLogin"]["IdUser"] == "" OR !$this->ValidarLogin()){
                    $this->PedirLogin();
                }else{
                    if(!REDDEMAILS::Permisos($_SESSION["UserLogin"]["IdUser"], $Modulo)){
                        return false;
                    }
                }
            }
        }

        return true;
    }

    private function PedirLogin(){
        if((isset($_POST["login_user"])) AND (isset($_POST["login_pass"]))){
            $Usuario = new MUsuarios();
            
            if(!$Usuario->getLogin($_POST["login_user"], md5($_POST["login_pass"]))){
                $LoginView = new VLogin();
                $LoginView->Formulario();
                die();
            }else{
               $_SESSION["UserLogin"]["IdUser"] = $Usuario->getId();
               $_SESSION["UserLogin"]["Usuario"] = md5($Usuario->getUsuario());
               $_SESSION["UserLogin"]["Clave"] = $Usuario->getClave();
               $_SESSION["UserLogin"]["NameShow"] = $Usuario->getNombre()." ".$Usuario->getApellido();
            }
        }else{
            $LoginView = new VLogin();
            $LoginView->Formulario();
            die();
        }
    }

    private function ValidarLogin(){
        $Usuario = new MUsuarios();
        $Usuario->abrir($_SESSION["UserLogin"]["IdUser"]);

        if(md5($Usuario->getUsuario()) != $_SESSION["UserLogin"]["Usuario"] OR $Usuario->getClave() != $_SESSION["UserLogin"]["Clave"]){
            return false;
        }else{
            return true;
        }
    }
}