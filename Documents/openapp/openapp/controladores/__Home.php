<?php
class CHome {
    public function Run(){
        $GanadoresAnt = new MGanadoresAnteriores();
        $ListGanadoresAnt = $GanadoresAnt->getGanadoresAleatorio(4);
        
        $Vista = new VHome();
        $Vista->agregarFunVars(get_defined_vars());
        $Vista->Home();
    }
    
    public function RegResponsable(){

        $Responsable = new MResponsables();
        $Responsable->setNombre(POST::take("nombre"));
        $Responsable->setApellido(POST::take("apellido"));
        $Responsable->setDNI(POST::take("DNI"));
        $Responsable->setFechaNacimiento(Funciones::FechatoSQL(POST::take("fechanacimiento")));
        $Responsable->setProvincia(POST::take("provincia"));
        $Responsable->setLocalidad(POST::take("localidad"));
        $Responsable->setEmail(POST::take("email"));
        $Responsable->setTelefono(POST::take("telefono"));
        $Responsable->setClave(md5(POST::take("clave")));
        $Responsable->Grabar();
        
        openapp::SetLogin($Responsable->getEmail(), POST::take("clave"));
        openapp::EnviarMailRegistro($Responsable);
        
        echo "OK";
    }

    public function EnviarClave(){
        $User = POST::take("user");
        $Responsable = new MResponsables();
        if($Responsable->AbrirXEmail($User)){
            $NewPass = openapp::GenerarClave();
            $Responsable->setClave(md5($NewPass));
            $Responsable->Grabar();
            
            openapp::EnviarMailOClave($Responsable, $NewPass);
            
            echo "OK";
        }else{
            echo "ERROR";
        }
    }
    
    public function Logeate(){
        if(!is_null(POST::take("user")) AND !is_null(POST::take("pass"))){
            if(openapp::SetLogin(POST::take("user"), POST::take("pass"))){
                echo "OK";
            }else{
                echo "ERROR";
            }
        }
    }
    
    public function LogeateExt(){
        if (isset($_POST["CargaFilePropuesta"])){
            $URL = CESIS::$ROOT."Proyectos/CargaFilePropuesta/".$_POST["CargaFilePropuesta"];
        }else{
            $URL = CESIS::$ROOT."Proyectos/Inscripcion/".GET::take("id");
        }
        
        if(!is_null(POST::take("user")) AND !is_null(POST::take("pass"))){
            if(openapp::SetLogin(POST::take("user"), POST::take("pass"))){
                Funciones::Location($URL);
            }else{
                Funciones::Location($URL);
            }
        }
    }
    
    public function Postularme(){
        if(openapp::isLogin()){
            $Responsable = new MResponsables();
            $IdResp = $Responsable->BuscarLogin($_SESSION["LOGIN"]["User"], $_SESSION["LOGIN"]["Pass"]);
            $Responsable->Abrir($IdResp);
            $Proyecto = new MProyectos();
            $Proyecto->setResponsable($IdResp);
            $Proyecto->setCategoria(POST::take("categoria"));
            $Proyecto->setDescripcion(POST::take("descripcion"));
            $Proyecto->setNombre(POST::take("nombre"));
            $Proyecto->Grabar();
            $Key = md5($Responsable->getEmail()).md5($Proyecto->getId())."".md5(md5($Responsable->getEmail()).md5($Proyecto->getId()));
            
            openapp::EnviarMailPostulate($Proyecto, $Key);           
            
            echo "OK";
        }else{
            $Responsable = new MResponsables();
            
            if (count($Responsable->SQL_SELECT("id","Email LIKE '".POST::take("user_mail")."'")) > 0){
                echo "USUARIOC";
                
            }else{            
                $NewClave = openapp::GenerarClave();

                $Responsable->setNombre(POST::take("user_nombre"));
                $Responsable->setApellido(POST::take("user_apellido"));
                $Responsable->setEmail(POST::take("user_mail"));
                $Responsable->setTelefono(POST::take("user_telefono"));
                $Responsable->setClave(md5($NewClave));
                $Responsable->Grabar();

                $Proyecto = new MProyectos();
                $Proyecto->setResponsable($Responsable->getId());
                $Proyecto->setCategoria(POST::take("categoria"));
                $Proyecto->setDescripcion(POST::take("proy_descripcion"));
                $Proyecto->setNombre(POST::take("proy_nombre"));
                $Proyecto->Grabar();

                $Key = md5($Responsable->getEmail()).md5($Proyecto->getId())."".md5(md5($Responsable->getEmail()).md5($Proyecto->getId()));

                openapp::EnviarMailRegistro($Responsable, $NewClave);
                openapp::EnviarMailPostulate($Proyecto, $Key);

                echo "OK";
            }
        }
    }
   

    
 public function MailConsulta(){
      $Email = GET::take("Email");
      $Nombre = GET::take("Nombre");
      $Asunto = GET::take("Asunto");
      $Comentario = GET::take("Comentario");

      return openapp::EnviarMailConsulta($Email, $Nombre, $Asunto, $Comentario);                    
    }
    
public function GraciasConsulta(){
    $Vista = new VHome();
    $Vista->FormConsulta();      
  }

public function FormReg(){
        $Provincias = new MProvincias();
        $ArProvincias = $Provincias->SQL_SELECT();
        
        $Vista = new VHome();
        $Vista->agregarFunVars(get_defined_vars());
        $Vista->FormRegistro();
    }
    
    public function FormLogin(){
        $Vista = new VHome();
        $Vista->FormLogin();
    }
    
    public function FormProy(){
        if(openapp::isLogin()){
            $Categorias = new MCategorias();
            $ArCategorias = $Categorias->SQL_SELECT();
            if(is_null(GET::take("Id"))){
                $CatPreSelect = null;
            }else{
                $CatPreSelect = GET::take("Id");
            }
            
            $Vista = new VHome();
            $Vista->agregarFunVars(get_defined_vars());
            $Vista->FormProyecto();
        }else{
            $Categorias = new MCategorias();
            $ArCategorias = $Categorias->SQL_SELECT();
            if(is_null(GET::take("Id"))){
                $CatPreSelect = null;
            }else{
                $CatPreSelect = GET::take("Id");
            }
            
            $Vista = new VHome();
            $Vista->agregarFunVars(get_defined_vars());
            $Vista->FormProyectoExt();
        }
    }
    
    public function FormOClave(){
        $Vista = new VHome();
        $Vista->FormOClave();
    }
    
    /*MSJ En LighBox*/
    
    public function MensOKReg(){
        $Vista = new VHome();
        $Vista->MensOKReg();
    }
    public function MensErrorReg(){
        $Vista = new VHome();
        $Vista->MensErrorReg();
    }
    public function MensUsuarioExiste(){
        $Vista = new VHome();
        $Vista->MensUsuarioExiste();
    }
    
    public function MensErrorLogin(){
        $Vista = new VHome();
        $Vista->MensErrorLogin();
    }
    
    public function MensOKProy(){
        $Vista = new VHome();
        $Vista->MensOKProy();
    }
    
    public function MensErrorProy(){
        $Vista = new VHome();
        $Vista->MensErrorProy();
    }
    
    public function MensOKOClave(){
        $Vista = new VHome();
        $Vista->MensOKOClave();
    }
    
    public function MensErrorOClave(){
        $Vista = new VHome();
        $Vista->MensErrorOClave();
    }
    
}