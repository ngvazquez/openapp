<?php
class CProyectos {
    public function Run(){
        $this->Listado();
    }
    
    public function Listado(){
        $MProyectos = new MProyectos();
        $CantXPag = 10;
        $Proyectos = $MProyectos->SQL_SELECT('*',"Estado >= 3");
        
        $CantDePag = count($Proyectos)/$CantXPag;
        
        if(is_float($CantDePag)){
            $CantDePag = ceil($CantDePag);
        }
        if(GET::take('Id')){
            $PagAct = GET::take('Id');
            $LimitInf = $CantXPag*($PagAct-1);
        }else {
            $PagAct = 1;
            $LimitInf = 0;
        }
        
        $Proyectos = $MProyectos->SQL_SELECT('*',"Estado >= 3 ORDER BY id LIMIT ".$LimitInf." , ".$CantXPag);
        $Vista = new VProyecto();
        $Vista->agregarFunVars(get_defined_vars());
        $Vista->Listado();
    }


    public function Ver(){
        if (GET::take("otro_0") == "true"){
            $First = true;
        }
        
        $MProyectos = new MProyectos();
        $Proyecto = $MProyectos->AbrirXKey(GET::take("Id"));
        
        $Vista = new VProyecto();
        $Vista->agregarFunVars(get_defined_vars());
        
        if(isset(GET::take("Otro_1")) and GET::take("Otro_1") == 'facebook'){
            $Vista->addPROPERTY("og:title","Ayudame a aumentar mis chances de ganar el concurso OpenApp votando por mi proyecto :: ".$Proyecto[0]['Nombre']);
            if(isset($Proyecto[0]['Logo']) AND !is_null($Proyecto[0]['Logo']) AND $Proyecto[0]['Logo'] != ""){
                $Vista->addPROPERTY("og:image","http://openapp.com.ar/archivos/logos/".$Proyecto[0]['Logo']);
            }else{
                $Vista->addPROPERTY("og:image","http://openapp.com.ar/img/sin_imagen_chica_face.jpg");
            }
            $Vista->addPROPERTY("og:description",$Proyecto[0]['Descripcion']);

            $Vista->Ver();  
        }else{
            $Vista->addPROPERTY("og:title","Doy un voto por este proyecto de OpenApp :: ".$Proyecto[0]['Nombre']);
            if(isset($Proyecto[0]['Logo']) AND !is_null($Proyecto[0]['Logo']) AND $Proyecto[0]['Logo'] != ""){
            $Vista->addPROPERTY("og:image","http://openapp.com.ar/archivos/logos/".$Proyecto[0]['Logo']);
            }else{
            $Vista->addPROPERTY("og:image","http://openapp.com.ar/img/sin_imagen_chica_face.jpg");
            }
            $Vista->addPROPERTY("og:description",$Proyecto[0]['Descripcion']);
        
            $Vista->Ver(); 
        }

    }
    
    public function Actualizar(){
        $Key = POST::take("idKey");
        $idCEmail = substr($Key, 0, 32);
        $idCProy = substr($Key, 32, 32);
        $Auth = substr($Key, 64, 32);
        $Responsable = new MResponsables();
        if(openapp::isLogin()){
            $IdResp = $Responsable->BuscarLogin($_SESSION["LOGIN"]["User"], $_SESSION["LOGIN"]["Pass"]);
            $Responsable->Abrir($IdResp);
            if(md5($idCEmail.$idCProy) == $Auth){
                if(md5($Responsable->getEmail()) == $idCEmail){
                    $Proyectos = new MProyectos();
                    $ListProy = $Proyectos->getProyectosXR($Responsable->getId());
                    foreach($ListProy as $Proyecto){
                        if(md5($Proyecto["Id"]) == $idCProy){
                            $IdProy = $Proyecto["Id"];
                            $Proyectos->Abrir($Proyecto["Id"]);
                            continue 1;
                        }
                    }
                    
                    if (!is_null(POST::take("R_DNI"))){
                        $Responsable->setDNI(POST::take("R_DNI"));
                        $Responsable->setDireccion(POST::take("R_Direccion"));
                        $Responsable->setLocalidad(POST::take("R_Localidad"));
                        $Responsable->setProvincia(POST::take("R_Provincia"));
                        $Responsable->setCodigoPostal(POST::take("R_CodPostal"));
                        $Responsable->Grabar();
                    }
                    
                    $Proyectos->setGradoavance(POST::take("gradoavance"));
                    $Proyectos->setCompetencia(POST::take("competencia"));
                    $Proyectos->setEstado(2);
                    $Proyectos->setDescripcionDetallada(POST::take("descripcion"));
                    $Proyectos->setFechaCargaC("SQL:NOW()");
                    $Proyectos->Grabar();
                    
                    $MSubCatProyect = new MSCatProyecto();
                    
                    foreach ($_POST["subcategoria"] as $SubCategorias){
                        $MSubCatProyect->setProyecto($Proyectos->getId());
                        $MSubCatProyect->setSubCategoria($SubCategorias);
                        $MSubCatProyect->Grabar();
                        $MSubCatProyect->Clear();
                    }
                    
                    $NombreSocios = POST::take("NombreSocios");
                    $ApellidoSocios = POST::take("ApellidoSocios");
                    if(is_array($NombreSocios)){
                        $CantSocios = count($NombreSocios);

                        $Socio = new MSocios();
                        for($n=0; $n < $CantSocios; $n++){
                            $Socio->setProyecto($Proyectos->getId());
                            $Socio->setNombre($NombreSocios[$n]);
                            $Socio->setApellido($ApellidoSocios[$n]);
                            $Socio->Grabar();
                            $Socio->Clear();
                        }
                    }
                    $MD = strtoupper(md5($Proyectos->getId()));
                    
                    $ArrKey = $Proyectos->getAllKeys();
                    
                    $CatRetro = 5;
                    $Temp = 0;
                    while(array_search(substr($MD, strlen($MD)-($CatRetro+$Temp), 5), $ArrKey) !== false){
                        if((strlen($MD)-($CatRetro+$Temp)) > 0){
                            $Temp++;
                        }else{
                            $MD = strtoupper(md5(openapp::GenerarClave(7)));
                            $Temp = 0;
                        }
                    }

                    $KeyProyect = substr($MD, strlen($MD)-($CatRetro+$Temp), 5);
                    
                    $Proyectos->setKey($KeyProyect);
                    /* LOGO */
                    
                    
                    if($_FILES["logo"]["name"]!= "" || $_FILES["logo"]["name"]!= null){
                        $FiltroExtImg = array("jpg", "png", "gif");
                        $Extencion = explode("/", $_FILES["logo"]["type"]);
                        
                        $AnchoIMG = 122;
                        $AltoIMG = 122;
                        
                        $Nombre = $_FILES["logo"]["name"];
                        $newName = $KeyProyect.".".$Extencion[1];
                        Imagenes::Procesar($_FILES["logo"], "archivos/logos/");
                        Imagenes::setName($newName);
                        Imagenes::ReSize($AnchoIMG, $AltoIMG);
                        //Imagenes::Cut($AnchoIMG, $AltoIMG);
                        Imagenes::Save();
                        
                        $Proyectos->setLogo($newName); 	
                    }
                    
                    $Proyectos->Grabar();
                    
                    Funciones::Location(CESIS::$ROOT."Proyectos/Ver/".$KeyProyect."/"."true"."/");
                    
                    openapp::EnviarMailInscripcion($Proyectos);
                    
                }else{
                    $Vista = new VHome();
                    $Vista->FormLogin();
                }
            }else{
                $Vista = new VHome();
                $Vista->FormLogin();
            }
        }else{
            $Vista = new VHome();
            $Vista->FormLogin();
        }
    }
    
    public function Inscripcion(){
        $IdMedio = array("fbook_bnner", "fbook_cont", "twtt", "adw", "nwltter", "medio");

        $Take = false;
        if(!is_null(GET::take("Id")) AND GET::take("Id") != "") {
            if(in_array(GET::take("Id"), $IdMedio)){
                $RefMedio = GET::take("Id");
            }else{
                $Key = GET::take("Id");
                $idCEmail = substr($Key, 0, 32);
                $idCProy = substr($Key, 32, 32);
                $Auth = substr($Key, 64, 32);
                if($Key != ""){
                    $Take = true;
                }
            }
            
        }
        $NoLogeado = true;
        
        $Responsable = new MResponsables();
        if(openapp::isLogin() AND $Take){
            $IdResp = $Responsable->BuscarLogin($_SESSION["LOGIN"]["User"], $_SESSION["LOGIN"]["Pass"]);
            $Responsable->Abrir($IdResp);
            if(md5($idCEmail.$idCProy) == $Auth){
                
                if(md5($Responsable->getEmail()) == $idCEmail){
                    $MProvincias = new MProvincias();
                    $Categorias = new MCategorias();
                    $Proyectos = new MProyectos();
                    
                    $ArCategorias = $Categorias->SQL_SELECT('*','Padre IS NULL');
                    $ListProy = $Proyectos->getProyectosXR($Responsable->getId());
                    $Provincias = $MProvincias->SQL_SELECT();
                    foreach($ListProy as $Proyecto){
                        if(md5($Proyecto["Id"]) == $idCProy){
                            $IdProy = $Proyecto["Id"];
                            $Proyectos->Abrir($Proyecto["Id"]);
                            $SubCategorias = $Categorias->SQL_SELECT('*','Padre = '.$Proyectos->getCategoria());
                            $NoLogeado = false;
                            continue 1;
                        }
                    }
                    $GradodeAvance = new MGradosDeAvances();
                    $LisGDA = $GradodeAvance->SQL_SELECT();
                    if($Proyectos->getFechaCargaC() != "" AND !is_null($Proyectos->getFechaCargaC())){
                        ?>
                            <script type="text/javascript">
                                window.alert("Este Proyecto ya fue cargado con anterioridad");
                                window.location.href = "<?php echo CESIS::$ROOT; ?>";
                            </script>
                        <?php
                        die();
                    }else{
                        $Vista = new VProyecto();
                        $Vista->agregarFunVars(get_defined_vars());
                        $Vista->FormProyectoCompleto();                        
                    }
                }else{
                    $Take = false;
                    $Categorias = new MCategorias();
                    $ArCategorias = $Categorias->SQL_SELECT('*','Padre IS NULL');

                    $Vista = new VProyecto();
                    $Vista->agregarFunVars(get_defined_vars());
                    $Vista->FormProyectoCompleto();
                }
            }else{
                $Take = false;
                $Categorias = new MCategorias();
                $ArCategorias = $Categorias->SQL_SELECT('*','Padre IS NULL');

                $Vista = new VProyecto();
                $Vista->agregarFunVars(get_defined_vars());
                $Vista->FormProyectoCompleto();
            }
        }else{
            $NoLogeado = true;
            $Categorias = new MCategorias();
            $ArCategorias = $Categorias->SQL_SELECT('*','Padre IS NULL');
            
            $Vista = new VProyecto();
            $Vista->agregarFunVars(get_defined_vars());
            $Vista->FormProyectoCompleto();
        }
        //http://openapp-sitio.localserver.dev/Proyectos/Inscripcion/c4ce969e83affe44b643103b406d94b9
    }
    
    public function CargaFilePropuesta(){
        $Take = false;
        
        if(!is_null(GET::take("Id")) AND GET::take("Id") != "") {
            $Key = substr(GET::take("Id"),0, strlen(GET::take("Id"))-2);
            $Instancia = substr(GET::take("Id"),strlen(GET::take("Id"))-2, strlen(GET::take("Id")));
            $idCEmail = substr($Key, 0, 32);
            $idCProy = substr($Key, 32, 32);
            $Auth = substr($Key, 64, 32);
            if($Key != ""){
                $Take = true;
            }
        }
        $NoLogeado = true;
        
        $Responsable = new MResponsables();
        if(openapp::isLogin() AND $Take){
            $IdResp = $Responsable->BuscarLogin($_SESSION["LOGIN"]["User"], $_SESSION["LOGIN"]["Pass"]);
            $Responsable->Abrir($IdResp);
            if(md5($idCEmail.$idCProy) == $Auth){
                if(md5($Responsable->getEmail()) == $idCEmail){
                    $Proyectos = new MProyectos();
                    
                    $ListProy = $Proyectos->getProyectosXR($Responsable->getId());
                    foreach($ListProy as $Proyecto){
                        if(md5($Proyecto["Id"]) == $idCProy){
                            $IdProy = $Proyecto["Id"];
                            $Proyectos->Abrir($Proyecto["Id"]);
                            $NoLogeado = false;
                            continue 1;
                        }
                    }
                    
                    switch ($Instancia){
                        case "PC":
                                if($Proyectos->getPDF_2C() == "" OR is_null($Proyectos->getPDF_2C())){
                                    if($Proyectos->getPDF_1C() != "" AND !is_null($Proyectos->getPDF_1C())){
                                    ?>
                                        <script type="text/javascript">
                                            window.alert("El archivo ya fue cargado");
                                            window.location.href = "<?php echo CESIS::$ROOT; ?>";
                                        </script>
                                    <?php
                                    die();
                                    }else{
                                        $Vista = new VProyecto();
                                        $Vista->agregarFunVars(get_defined_vars());
                                        $Vista->FormCargaFile();                       
                                    }
                                }else{
                                    ?>
                                        <script type="text/javascript">
                                            window.location.href = "<?php echo CESIS::$ROOT; ?>";
                                        </script>
                                    <?php
                                    die();
                                }
                                break;
                        case "SC":
                                if($Proyectos->getPDF_1C() != "" AND !is_null($Proyectos->getPDF_1C())){
                                    if($Proyectos->getPDF_2C() != "" AND !is_null($Proyectos->getPDF_2C())){
                                        ?>
                                            <script type="text/javascript">
                                                window.alert("El archivo ya fue cargado");
                                                window.location.href = "<?php echo CESIS::$ROOT; ?>";
                                            </script>
                                        <?php
                                        die();
                                    }else{
                                        $Vista = new VProyecto();
                                        $Vista->agregarFunVars(get_defined_vars());
                                        $Vista->FormCargaFile();                       
                                    }
                                }else{
                                    ?>
                                        <script type="text/javascript">
                                            window.location.href = "<?php echo CESIS::$ROOT; ?>";
                                        </script>
                                    <?php
                                    die();
                                }
                                
                                break;
                     }
                    
                }else{
                    $Take = false;

                    $Vista = new VProyecto();
                    $Vista->agregarFunVars(get_defined_vars());
                    $Vista->FormCargaFile();
                }
            }else{
                $Take = false;

                $Vista = new VProyecto();
                $Vista->agregarFunVars(get_defined_vars());
                $Vista->FormCargaFile();
            }
        }else{
            $NoLogeado = true;
            
            $Vista = new VProyecto();
            $Vista->agregarFunVars(get_defined_vars());
            $Vista->FormCargaFile();
        }
    }
    
    public function UpLoadFile (){
        $Proyecto = new MProyectos();
        
        $Proyecto->Abrir(GET::take("id"));
        if($Proyecto->getPDF_1C() == "" OR is_null($Proyecto->getPDF_1C())){
            if($_FILES["manual"]["name"]!= "" || $_FILES["manual"]["name"]!= null){
                $Extencion = explode(".", $_FILES["manual"]["name"]);
               // var_dump($Extencion);die();
                $newName = $Proyecto->getKey()."_1C.".$Extencion[1];
                if(!move_uploaded_file($_FILES["manual"]['tmp_name'], "archivos/pdf/".$newName)){
                    echo "<script> window.alert('Hubo un erro al cargar el archivo intentelo nuevamente'); </script>";
                    Funciones::PageBack(-1);
                    die();
                }             
                $Proyecto->setPDF_1C($newName);
                $Proyecto->Grabar();
            }
        }else{
            if($_FILES["manual"]["name"]!= "" || $_FILES["manual"]["name"]!= null){
                
                $Extencion = explode(".", $_FILES["manual"]["name"]);
                $newName = $Proyecto->getKey()."_2C.".$Extencion[1];
                if(!move_uploaded_file($_FILES["manual"]['tmp_name'], "archivos/pdf/".$newName)){
                    echo "<script> window.alert('Hubo un erro al cargar el archivo intentelo nuevamente'); </script>";
                    Funciones::PageBack(-1);
                    die();
                }             
                $Proyecto->setPDF_2C($newName);
                $Proyecto->Grabar();
                
             
        }
        Funciones::Location(CESIS::$ROOT."Proyectos/Ver/".$Proyecto->getKey()."/"."true"."/");
       }
       
    }
}
