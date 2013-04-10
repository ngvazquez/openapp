<?php
class CHome {
    public function Run(){
        $Vista = new VHome();
        if(admin_openapp::isLogin()){
            Funciones::Location(CESIS::$ROOT."admin/Proyectos/");
        }else{
            $Vista->Login();
        }
    }
    
    public function Login(){
        if(admin_openapp::isLogin()){
            $this->Run();
        }elseif(!is_null(POST::take("user_admin"))){
            
            if(admin_openapp::SetLogin(POST::take("user_admin"), POST::take("pass_admin"))){
                $this->Run();
            }else{
                $Vista = new VHome();
                $Vista->Login();
            }
        }else{
            $Vista = new VHome();
            $Vista->Login();
        }
    }
}