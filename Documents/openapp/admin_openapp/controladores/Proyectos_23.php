<?php
class CProyectos {
    private $TopeP1 = 180;
    private $TopeP2 = 60;
    private $TopeP3 = 15;
    private $TopeP0 = 50;
    
    private $CantXPagPPM = 50;
    
    public function Run(){
        if(admin_openapp::isLogin()){
            $this->Listado();
        }else{
            Funciones::Location("/admin/");
        }
    }
    
    public function Export(){
        if(!admin_openapp::isLogin()){
            Funciones::Location("/admin/");
        }
        
        $Proyectos = new MProyectos();
        $Categoria = new MCategorias();
        $Responsable = new MResponsables();
        $Provincia = new MProvincias();
        $EstadoProy = new MEstadoproyecto();

        
        $getID = GET::take('id');
        
        //var_dump($_SESSION["ExportWhere"]);die();
        if(substr_count($_SESSION["ExportWhere"], 'LIMIT')== 1){
            $Cortar = substr($_SESSION["ExportWhere"],-13);
            $Sesion = str_replace($Cortar, ' ',$_SESSION["ExportWhere"]);
        }else{
            $Sesion = $_SESSION["ExportWhere"];
        }
        //var_dump($Sesion);die();
        $Filas = "`key`,Nombre,Categoria,Responsable,Estado,ArchivoAprobado";
        $FilasRes = "Email,Telefono";
        
        
        $ListExport = $Proyectos->SQL_SELECT($Filas, $Sesion);
        $ListExportMail = $Responsable->SQL_SELECT($FilasRes, false);

        $Keys = array_keys($ListExport[0]);
        
        $EstadoProy->Abrir($getID);
        $Reporte = $EstadoProy->getEstado();
        
        if($getID == 4){ 
            $Reporte = substr($Reporte,0,3);;
            $Reporte .= '_180';
        }
        
        if($getID == 5){
            $Reporte = substr($Reporte,0,3);;
            $Reporte .= '_60';
        }
        
        if($getID == 0  OR $getID == 3){
            $Reporte .= 's';
        }

       header("Content-Type: application/csv");
       header("Content-Disposition: attachment;Filename=".$Reporte.".csv");
        

      switch ($getID) {
        case 3:
            echo '"Codigo","Proyecto","Categoria","Nombre","Apellido","Provincia","Mail","Telefono","Nuevos estados ","Archivo Cargado"';
            break;
        case 4:
            echo '"Codigo","Proyecto","Categoria","Nombre","Apellido","Manual-1","Envio de Mail"';
            break;
        case 5:
            echo '"Codigo","Proyecto","Categoria","Nombre","Apellido","Manual-2","Envio de Mail"';
            break;
        case 0:
           echo '"Codigo","Proyecto","Categoria","Nombre","Apellido","Provincia","Mail","Telefono"';
           break;
        case 6:
           echo '"Codigo","Proyecto","Categoria","Nombre","Apellido","Provincia","Mail","Telefono"';
           break;
        case 2:
           echo '"Codigo","Proyecto","Categoria","Nombre","Apellido","Provincia","Mail","Telefono"';
           break;
        }

        echo "\n";

        
        for($x=0;$x<Count($ListExport); $x++){
            foreach($ListExport[$x] as $Key => $Valor){
                
                if($Key == 'key'){
                    echo $Valor.",";
                }
                
                if($Key == 'Nombre'){
                    echo $Valor.",";
                }
                
                if($Key == 'Categoria'){
                     $Categoria->abrir($Valor);
                     $Categoty = $Categoria->getNombre();
                     echo html_entity_decode($Categoty).",";
                }
                if($Key == 'Responsable'){
                     $Responsable->abrir($Valor);
                     $ResponN = $Responsable->getNombre();
                     $ResponA = $Responsable->getApellido();
                     
                     if ($getID == 4){
                          echo $ResponN.",";
                     }else{
                         
                         $Email = $Responsable->getEmail();
                         $Tel = (string)$Responsable->getTelefono();
                         $Prov = $Responsable->getProvincia();
                         $Provincia->Abrir($Prov);
                         $Provin = html_entity_decode($Provincia->getProvincia());
                         
                         echo $ResponN.",".$ResponA.",".$Provin.",".$Email.",".$Tel.",";
                     }  
                }
                
              if($getID == 3){
                  if($Key == 'Estado'){
                    $EstadoProy->Abrir($Valor);
                    $Est = $EstadoProy->getEstado();
                    
                    echo $Est.",";
                  }
                  
                  if($Key == 'ArchivoAprobado'){
                      if(is_null($Valor) AND $Valor ==''){
                          echo 'S/Cargar';
                      }else{
                         echo CESIS::$ROOT."admin/Proyectos/VerPdfAprov/".$Valor; 
                      } 
                  }
   
              }  
                
           }
            echo "\n";
        }
    }
    
    public function Listado(){
        if(!admin_openapp::isLogin()){
            Funciones::Location("/admin/");
        }
        
        $_SESSION["ExportWhere"] = "";
        
        $Proyectos = new MProyectos();
        $Moderacion = new MModeracion();
        $SendMails = new MSendmails();
        
        $getID = GET::take('id');
        $Catego = GET::take('Otro_0');
        $CodValor = GET::take('Otro_1');
        $CodEstado = GET::take('Otro_2');        
        
        $ListPA = $Proyectos->getProyectos(array(3, 7, 8, 9, 10), false, "ASC", 3);
        //$ListPA = $Proyectos->SQL_SELECT("*", "Estado IN (3,7,8,9,10)");
        
        $ListPI = $Proyectos->SQL_SELECT("*", "Estado = 1");
        
        $ListPPM = $Proyectos->getProyectos(2, false, "ASC", false);
        //$ListPPM = $Proyectos->SQL_SELECT("*", "Estado = 2");
        
        $List180 = $Proyectos->getProyectos(4, false, "ASC", 4);
        //$List180 = $Proyectos->SQL_SELECT("*", "Estado = 4");
        $List60 = $Proyectos->getProyectos(5, false, "ASC", 5);
        //$List60 = $Proyectos->SQL_SELECT("*", "Estado = 5");
        $ListGanadores = $Proyectos->getProyectos(6, false, "ASC", 6);
        //$ListGanadores = $Proyectos->SQL_SELECT("*", "Estado = 6");
        $ListRechazados = $Proyectos->getProyectos(0, false, "ASC", 0);
        //$ListRechazados = $Proyectos->SQL_SELECT("*", "Estado = 0");


        $CantTotal = count($ListPPM);
        
        $CantXPag = $this->CantXPagPPM; //Cantidad Por Paginas (Listado Proyectos Para Moderar)
        
        $CantPag = $CantTotal/$CantXPag;
        if(is_float($CantPag)){
            $CantPag= ceil($CantPag);
        }
        $Pag = 1;
        
        if(POST::take('Pagina') != 1 AND POST::take('Pagina') != false){
            $Pag = POST::take('Pagina');
        }
        
        $limitInf = $CantXPag*($Pag-1);
        
    
        if($getID == 2 AND $Catego == '' AND $CodValor == '' AND $CodEstado == ''){
           $ListPPM = $Proyectos->getXPaginaYEstadoConFiltro($limitInf, $CantXPag, $getID, '1,2,3');
        }
        
        if($getID == 3){
            $_SESSION["ExportWhere"] = "Estado IN (3,7,8,9,10)";
        }else{
            $_SESSION["ExportWhere"] = "Estado = ".$getID;
        }
        //var_dump($_SESSION["ExportWhere"]);die();

        /*FILTRO ESTADO 3*/ 
        if( $getID == 3 ){
            if ($getID == 3 AND $CodEstado != "Indistinto" AND $CodEstado != "" AND $CodValor != "Indistinto" AND $CodValor != "" AND $Catego != "Indistinto" AND $Catego != ""){
                $_SESSION["ExportWhere"] =  "Categoria =".$Catego." AND `Key` = '".$CodValor."'";
                $ListPA = $Proyectos->getProyectos($CodEstado, $_SESSION["ExportWhere"], "ASC", 3);
            }

            if($getID == 3 AND $CodEstado == "Indistinto" AND $CodValor == "Indistinto" AND $Catego != "Indistinto"){ 
                $_SESSION["ExportWhere"] .=  " AND Categoria = ".$Catego;
                $ListPA = $Proyectos->getProyectos(array($getID, 7, 8, 9, 10), $_SESSION["ExportWhere"], "ASC", 3);
            }

            if($getID == 3 AND $CodEstado == "Indistinto" AND $CodValor != "Indistinto" AND $Catego != "Indistinto"){ 
                $_SESSION["ExportWhere"] =  "Categoria =".$Catego." AND `Key` = '".$CodValor."'";
                $ListPA = $Proyectos->getProyectos(array($getID, 7, 8, 9, 10), $_SESSION["ExportWhere"], "ASC", 3);
            }

            if($getID == 3 AND $CodEstado == "Indistinto" AND $CodValor != "Indistinto" AND $Catego == "Indistinto"){ 
                $_SESSION["ExportWhere"] =  "`Key` = '".$CodValor."' AND Categoria IN (1,2,3)";
                $ListPA = $Proyectos->getProyectos(array($getID, 7, 8, 9, 10), $_SESSION["ExportWhere"], "ASC", 3);
            }

            if($getID == 3 AND $CodEstado != "Indistinto" AND $CodValor != "Indistinto" AND $Catego == "Indistinto"){ 
                $_SESSION["ExportWhere"] =  "`Key` = '".$CodValor."' AND Categoria IN (1,2,3)";
                $ListPA = $Proyectos->getProyectos(array($getID, 7, 8, 9, 10), $_SESSION["ExportWhere"], "ASC", 3);
            }

            if($getID == 3 AND $CodEstado != "Indistinto" AND $CodValor == "Indistinto" AND $Catego == "Indistinto"){ 
                $_SESSION["ExportWhere"] =  "Categoria IN (1,2,3)";
                $ListPA = $Proyectos->getProyectos(array($CodEstado), $_SESSION["ExportWhere"], "ASC", 3);
                //$_SESSION["ExportWhere"] =  "Categoria IN (1,2,3) AND Estado =".$CodEstado;
            }

            if($getID == 3 AND $CodEstado != "Indistinto" AND $CodValor == "Indistinto" AND $Catego != "Indistinto"){ 
                $_SESSION["ExportWhere"] =  "Categoria =".$Catego;
                $ListPA = $Proyectos->getProyectos(array($CodEstado), $_SESSION["ExportWhere"], "ASC", 3);
                //$_SESSION["ExportWhere"] =  "Categoria =".$Catego." AND Estado =".$CodEstado;
            }

          }else{
            
//var_dump($CodValor."+".$Catego);die();
            /*FILTRO ESTADOS*/               
            if($CodValor == "Indistinto" AND $Catego != "Indistinto"){
                
                // AND Estado = ".$getID."
                if( $getID == 2){
                   $_SESSION["ExportWhere"] = "Categoria = ".$Catego;
                   $Limit = " LIMIT ".$limitInf." , ".$CantXPag;
                }else{
                   $_SESSION["ExportWhere"] = "Categoria = ".$Catego;
                }
                //var_dump($_SESSION["ExportWhere"]);die();
                if( $getID == 0){
                   $ListRechazados = $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", $getID);
                }
                
                if( $getID == 1){
                   $ListPI =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", $getID);//$Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]); 
                }
                
                if( $getID == 2){
                   $ListPPM =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", false, false, $Limit);// $Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]);
                }
                
                if( $getID == 4){
                   $List180 =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", $getID);// $Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]); 
                }
                
                if( $getID == 5){
                   $List60 =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", $getID);// $Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]); 
                }
                
                if( $getID == 6){
                   $ListGanadores =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", $getID);// $Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]); 
                }
            }
            
            if($CodValor != "Indistinto" AND $Catego == "Indistinto"){
                
                if( $getID == 2){
                   $_SESSION["ExportWhere"] = "`Key` = '".$CodValor."' AND Categoria IN (1,2,3) LIMIT ".$limitInf." , ".$CantXPag;
                }else{
                    $_SESSION["ExportWhere"] = "`Key` = '".$CodValor."' AND Categoria IN (1,2,3)";
                    
                }
                
                //$_SESSION["ExportWhere"] = "`Key` = '".$CodValor."' AND Estado = ".$getID." AND Categoria IN (1,2,3)";
                //var_dump($_SESSION["ExportWhere"]);die();
                if( $getID == 0){
                    $ListRechazados =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", $getID);// $Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]); 
                }
                
                if( $getID == 1){
                    $ListPI =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", $getID);// $Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]); 
                }
                
                if( $getID == 2){
                    $ListPPM =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", false);// $Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]); 
                }
                
                if( $getID == 4){
                    $List180 =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", $getID);// $Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]); 
                }
                
                if( $getID == 5){
                    $List60 =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", $getID);// $Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]); 
                }
                
                if( $getID == 6){
                    $ListGanadores =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", $getID);// $Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]);
                }
               
            }
            
            if($CodValor != "Indistinto" AND $CodValor != ""  AND $Catego != "Indistinto" AND $Catego != ""){ 
                
                if( $getID == 2){
                   $_SESSION["ExportWhere"] = "`Key` = '".$CodValor."' AND Categoria = ".$Catego." LIMIT ".$limitInf." , ".$CantXPag;
                }else{
                   $_SESSION["ExportWhere"] = "`Key` = '".$CodValor."' AND Categoria = ".$Catego;     
                }
                
                //$_SESSION["ExportWhere"] = "`Key` = '".$CodValor."' AND Categoria = ".$Catego." AND Estado = ".$getID;
                if( $getID == 0){
                    $ListRechazados =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", $getID);//$Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]); 
                }
                
                if( $getID == 1){
                    $ListPI =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", $getID);// $Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]); 
                }
                
                if( $getID == 2){
                    $ListPPM =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", false);// $Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]); 
                }
                
                if( $getID == 4){
                    $List180 =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", $getID);// $Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]); 
                }
                
                if( $getID == 5){
                    $List60 =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", $getID);// $Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]); 
                }
                
                if( $getID == 6){
                    $ListGanadores =  $Proyectos->getProyectos($getID, $_SESSION["ExportWhere"], "ASC", $getID);// $Proyectos->SQL_SELECT("*", $_SESSION["ExportWhere"]);
                }
               
            }
            
            if( $getID == 2 AND $Catego == "Indistinto" AND $CodValor == 'Indistinto'){
                Funciones::Location(CESIS::$ROOT."admin/Proyectos/Listado/2");
            }

           /* FIN FILTRO ESTADOS*/
        }
    

        
        $P180 = $Moderacion->SQL_SELECT("Proyecto","Estado = 4");
        $P60 = $Moderacion->SQL_SELECT("Proyecto","Estado = 5");
        
        $PGanadores = $Moderacion->SQL_SELECT("Proyecto","Estado = 6");
        $PRechazados = $Moderacion->SQL_SELECT("Proyecto","Estado = 0");
        
        $PAprbadosIns1 = $Moderacion->SQL_SELECT("Proyecto","Estado = 7");
        $PAprbadosIns2 = $Moderacion->SQL_SELECT("Proyecto","Estado = 8");
        $PAprbadosIns3 = $Moderacion->SQL_SELECT("Proyecto","Estado = 9");
        $PAprbadosIns4 = $Moderacion->SQL_SELECT("Proyecto","Estado = 10");
        

        
        $VerPI = false;
        $VerPPM = true;
        $VerPA = true;
        $Ver180 = true;
        $Ver60 = true;
        $VerGanadores = true;
        $VerRechazados = true;
        
        $CantEstado4 = false;
        $CantEstado5 = false;
        $CantEstado6 = false;
        $CantEstado0 = false;
        
        if(count($ListPI) < 1){
            $VerPI = false;
        }
        if(count($ListPPM) < 1){
            $VerPPM = false;
        }
        if(count($ListPA) < 1){
            $VerPA = false;
        }
        if(count($List180) < 1){
            $Ver180 = false;
        }
        if(count($List60) < 1){
            $Ver60 = false;
        }
        if(count($ListGanadores) < 1){
            $VerGanadores = false;
        }
        
        if(count($ListRechazados) < 1){
            $VerRechazados = false;
        }
        
        if(count($P180) == $this->TopeP1){
            $CantEstado4 = true;
            $Id180 = "";
            foreach($P180 as $_P180){
                $Id180 .= $_P180["Proyecto"].",";
            }
            $Id180 = substr($Id180, 0, strlen($Id180)-1);

            $ListPA = $Proyectos->SQL_SELECT("*", "Estado = 3 OR id IN (".$Id180.")");
        }else{
            $LimitFaltante = $this->TopeP1 - count($P180);
        }
        if(count($P60) == $this->TopeP2){
            $CantEstado5 = true;
            $Id60 = "";
            foreach($P60 as $_P60){
                $Id60 .= $_P60["Proyecto"].",";
            }
            $Id60 = substr($Id60, 0, strlen($Id60)-1);
            $List180 = $Proyectos->SQL_SELECT("*", "Estado = 4 OR id IN (".$Id60.")");
        }else{
            $LimitFaltante60 = $this->TopeP2 - count($P60);
        }
        
        if(count($PGanadores) == $this->TopeP3){
            $CantEstado6 = true;
            $IdGanadores = "";
            foreach($PGanadores as $_PGanadores){
                $IdGanadores .= $_PGanadores["Proyecto"].",";
            }
            $IdGanadores = substr($IdGanadores, 0, strlen($IdGanadores)-1);
            $List60 = $Proyectos->SQL_SELECT("*", "Estado = 5 OR id IN (".$IdGanadores.")");
        }else{
            $LimitFaltanteGan = $this->TopeP3 - count($PGanadores);
        }
        
        if(count($PRechazados) == $this->TopeP0){
            $CantEstado0 = true;
            $IdRechazados = "";
            foreach($PGanadores as $_PGanadores){
                $IdRechazados .= $_PGanadores["Proyecto"].",";
            }
            $IdRechazados = substr($IdRechazados, 0, strlen($IdRechazados)-1);
            $List60 = $Proyectos->SQL_SELECT("*", "Estado = 0 OR id IN (".$IdRechazados.")");
        }else{
            $LimitFaltanteGan = $this->TopeP0 - count($PGanadores);
        }
        
        
        if($CodValor == "Indistinto"){
            $CodValor = "";
        }
        
        $Vista = new VHome();
        $Vista->agregarFunVars(get_defined_vars());
        $Vista->ListProy();
        
    }
    
   /* public function ListadoDinamico(){
        if(!admin_openapp::isLogin()){
            Funciones::Location("/admin/");
        }
        
        $Proyectos = new MProyectos();
        
        $ListPPM = $Proyectos->SQL_SELECT("*", "Estado = 2");
         
        $CantTotal = count($ListPPM);
        $CantXPag = $this->CantXPagPPM;
       
        $CantPag = $CantTotal/$CantXPag;
        if(is_float($CantPag)){
            $CantPag= ceil($CantPag);
        }
        
        $Pag = POST::take('Pagina');
        $limitInf = $CantXPag*($Pag-1);
        
        $ListPPM = $Proyectos->getXPaginaYEstado($limitInf, $CantXPag, 2);
        
        echo '<form action="'.CESIS::$ROOT.'admin/Proyectos/Aceptar/" method="POST" id="FormCkack">
                <div class="PosicionAbsoluta" style="left: 600px; top: -30px;"><input type="submit" onclick="return window.confirm(\'Esta seguro que desea aceptar estos proyectos?\n(Tenga en cuenta que se enviara un mail a los responsables avisando el cambio de estado del proyecto)\')" value="Aceptar"/></div>
                <div class="PosicionAbsoluta" style="left: 800px; top: -30px;"><input type="button" onclick="Valickeck(this.form)" value="Rechazar"/></div>
                <table widht="644">
                    <thead style="background-color: #7AB800">
                        <tr>
                            <th widht="40" style="min-width: 40px">#</th>
                            <th style="text-align:center; width: 30px; min-width: 30px"><input type="checkbox" name="checkbox" onclick="SelectAllCheck(this, \'id[]\')" id="SelectALL" /></th>
                            <th widht="151" style="min-width: none">C&oacute;digo</th>
                            <th widht="151">Proyecto</th>
                            <th widht="151">Categor&iacute;a</th>
                            <th widht="151">Responsable</th>
                            <th>Provincia</th>
                            <th>Mail</th>
                            <th>Tel&eacute;fono</th>
                    </tr>
                </thead>
                <tbody>';
                if(POST::take('Pagina') != 1 AND POST::take('Pagina') != false){
                    $Pag = POST::take('Pagina');
         
                }else{
                    $Pag = 1;
                }
                $ContDesa = 1;
                $Cont = (1 + ($CantXPag * ($Pag-1)));
                $ContEstado = 1;
                
                    $EstilosBlock = "background-color:red;text-decoration: line-through;";
                    foreach($ListPPM as $Proyecto){
                        $Responsable = new MResponsables();
                        $Responsable->Abrir($Proyecto["Responsable"]);
                        $MProvincia = new MProvincias();
                        $MProvincia->abrir($Responsable->getProvincia());
                        
                        $EstiloExtra = "";
                    echo '<tr id="Tr'.$Proyecto['id'].'" style="background-color: #C8D33B;border-bottom: 1px solid #EE9A11; text-decoration: none;">
                            <td widht="20">'.$Cont.'</td>
                            <td widht="20" style="'.$EstiloExtra.'"><input type="checkbox" name="id[]" id="checkbox"  value="'.$Proyecto['id'].'"/></td>
                            <td widht="151" style="'.$EstiloExtra.'">'.$Proyecto["key"].'</td>
                            <td widht="151" style="'.$EstiloExtra.'" class="HoverProy"><a onclick="PopUp("'.CESIS::$ROOT.'admin/Proyectos/ResumentProyecto/'.$Proyecto['id'].'");" style="cursor: pointer; color: #000">'.$Proyecto["Nombre"].'</a></td>
                            <td widht="151" style="'.$EstiloExtra.'">';
                                $Cate = $Proyecto["Categoria"];
                                if ($Cate == 1){
                                    echo "Pymes";
                                }else if ($Cate == 2){
                                    echo "Entretenimiento y educaci&oacute;n";
                                }else if ($Cate == 3){
                                    echo "M&oacute;viles";
                                }else{
                                    echo "No tiene categoria";
                                };
                      echo '</td>
                            <td widht="151" style="'.$EstiloExtra.'">'.$Responsable->getNombre().'</td>
                            <td widht="151" style="'.$EstiloExtra.'">'.$MProvincia->getProvincia().'</td>
                            <td class="HoverProy" widht="151" style="'.$EstiloExtra.'"><a href="mailto:<?php echo $Responsable->getEmail(); ?>" style="color:#000000;">'.$Responsable->getEmail().'</a></td>
                            <td widht="151" style="'.$EstiloExtra.'">'.$Responsable->getTelefono().'</td>        
                        </tr>';
                      $Cont++;
                }
                
                echo '</tbody>
                </table>';
        //--Paginador--
        if ($CantPag>1){
            echo "<div><div style=\"float:left;font-family:Georgia,Arial;color:#ffffff;font-size:15px;\">P&aacute;ginas ";
            $i=0;
            while ($i<$CantPag){
                $i++;
                if($Pag==$i){
                    echo " - <span style=\"font-size:17px;font-weight:bold\">".$i."</span>";
                }else{
                    echo " - <a onclick=\"PaginaList(".$i.",'".CESIS::$ROOT."admin/Proyectos/ListadoDinamico/');\" style=\"cursor: pointer;\">".$i."</a>";
                }
            }   
            echo "</div></div>";
        }
    }*/
	
    public function Aceptar(){
        if(!admin_openapp::isLogin()){
            Funciones::Location("/admin/");
        }
        
        $Administrador = new MAdministradores();
        if(admin_openapp::isLogin()){
            $IdUser = $Administrador->BuscarIdUser($_SESSION["LOGIN_ADMIN"]["User"]);
        }else{
            $Vista = new VHome();
            $Vista->Login();
            die();
        }
        
        
        $Proyecto = new MProyectos();
        $Moderacion = new MModeracion();
        if (POST::take('id')){
            foreach (POST::take('id') as $_Id){
                $Proyecto->Abrir($_Id);
                $Proyecto->setEstado(3);
                $UrlShort = $this->make_bitly_url('http://www.openapp.com.ar/Proyectos/Ver/'.$Proyecto->getKey(), 'epexo', 'R_9130eb7dea3589d970f25ad3188b33e4', 'json');
                $Proyecto->setShortUri($UrlShort);
                
                $Proyecto->Grabar();
                
                //
                
                $Moderacion->setAdministrador($IdUser[0]["Id"]);
                $Moderacion->setProyecto($_Id);
                $Moderacion->setEstado(3);
                $Moderacion->Grabar();
                
                
                
                $Responsable = new MResponsables();
                $Responsable->Abrir($Proyecto->getResponsable());
                
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                $headers .= 'From: Open APP <info@openapp.com>' . "\r\n";

                ob_start();
                
                    $Mail = new VMails();
                    $Mail->agregarFunVars(get_defined_vars());
                    $Mail->Activo();

                $HTML = ob_get_contents();

                ob_end_clean();

                mail($Responsable->getEmail(), "Noticias sobre tu proyecto ".$Proyecto->getNombre(), $HTML, $headers);

                $Proyecto->Clear();
                $Moderacion->Clear();
            }
        }
        
        Funciones::Location(CESIS::$ROOT."admin/Proyectos/Listado/2");
    }
    
    public function Rechazar(){
        $Administrador = new MAdministradores();
        if(admin_openapp::isLogin()){
            $IdUser = $Administrador->BuscarIdUser($_SESSION["LOGIN_ADMIN"]["User"]);
        }else{
            Funciones::Location("/admin/");
        }
        
        $Proyecto = new MProyectos();
        $Moderacion = new MModeracion();
        if (GET::take('id')){
            $Proyecto->Abrir(GET::take('id'));
            $Proyecto->setEstado('0');
            $Proyecto->Grabar();
            //
            $Moderacion->setAdministrador($IdUser[0]["Id"]);
            $Moderacion->setProyecto(GET::take('id'));
            $Moderacion->setEstado('0');
            $Moderacion->Grabar();
        }
        
        Funciones::Location("/admin/Proyectos/Listado/2");
                
    }
    
    public function RechazarMode(){
        $Administrador = new MAdministradores();
        if(admin_openapp::isLogin()){
            $IdUser = $Administrador->BuscarIdUser($_SESSION["LOGIN_ADMIN"]["User"]);
        }else{
            Funciones::Location("/admin/");
        }
        
        $Proyecto = new MProyectos();
        $Moderacion = new MModeracion();
        if (GET::take('id')){
            $Proyecto->Abrir(GET::take('id'));
            $Proyecto->setEstado('2');
            $Proyecto->Grabar();
            //
            $Moderacion->setAdministrador($IdUser[0]["Id"]);
            $Moderacion->setProyecto(GET::take('id'));
            $Moderacion->setEstado('2');
            $Moderacion->Grabar();
        }
        
        Funciones::Location("/admin/Proyectos/Listado/0");
                
    }
    
    public function CambiarDeEstado(){
        
        $Administrador = new MAdministradores();
        if(admin_openapp::isLogin()){
            $IdUser = $Administrador->BuscarIdUser($_SESSION["LOGIN_ADMIN"]["User"]);
        }else{
            Funciones::Location("/admin/");
        }
        $id = GET::take('Id');
        $instacia = GET::take('Otro_0');
        
        $Proyecto = new MProyectos();
        $Moderacion = new MModeracion();
        
        if (GET::take('Id')){

            $Proyecto->Abrir(GET::take('Id'));
            $Proyecto->setEstado($instacia);
            $Proyecto->Grabar();
            //
            $Moderacion->setAdministrador($IdUser[0]["Id"]);
            $Moderacion->setProyecto(GET::take('Id'));
            $Moderacion->setEstado($instacia);
           
        }
        
    }
    public function Aceptar180(){
        if(!admin_openapp::isLogin()){
            Funciones::Location("/admin/");
        }
        
        $_SESSION["Reporte"]= "";
        
        $Administrador = new MAdministradores();
        $IdUser = $Administrador->BuscarIdUser($_SESSION["LOGIN_ADMIN"]["User"]);
        
        $Proyecto = new MProyectos();
        $Moderacion = new MModeracion();
        
        $P180 = $Moderacion->SQL_SELECT("Proyecto","Estado = 4");
                
        $FaltanCarg = $this->TopeP1 - count($P180);
        
        if (1 <= $FaltanCarg AND GET::take('id')){
            $Proyecto->Abrir(GET::take('id'));
            $Proyecto->setEstado(4);
            $Proyecto->Grabar();

            $Moderacion->setAdministrador($IdUser[0]["Id"]);
            $Moderacion->setProyecto(GET::take('id'));
            $Moderacion->setEstado(4);
            $Moderacion->Grabar();

            $Responsable = new MResponsables();
            $Responsable->Abrir($Proyecto->getResponsable());


            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            $headers .= 'From: Open APP <info@openapp.com>' . "\r\n";

            ob_start();

                $Mail = new VMails();
                $Mail->agregarFunVars(get_defined_vars());
                $Mail->Top180();

            $HTML = ob_get_contents();

            ob_end_clean();

            mail($Responsable->getEmail(), "Noticias sobre tu proyecto #".$Proyecto->getKey(), $HTML, $headers);

            $Proyecto->Clear();
            $Moderacion->Clear();

            
            $P180 = $Moderacion->SQL_SELECT("Proyecto","Estado = 4");
            $FaltanCarg = $this->TopeP1 - count($P180);
            if($FaltanCarg > 0){
                Funciones::Location(CESIS::$ROOT."admin/Proyectos/Listado/4");
            }else{
                Funciones::Location(CESIS::$ROOT."admin/Proyectos/Listado/3");    
            }            
        }else{
            if(GET::take('id')){$_SESSION["Reporte"]= "La cantidad de proyectos aceptados superan el cupo de 180";}
            Funciones::Location(CESIS::$ROOT."admin/Proyectos/Listado/4");
        }
        

    }
    
    public function Aceptar60(){
        if(!admin_openapp::isLogin()){
            Funciones::Location("/admin/");
        }
        
        $_SESSION["Reporte"]= "";
        
        $Administrador = new MAdministradores();
        $IdUser = $Administrador->BuscarIdUser($_SESSION["LOGIN_ADMIN"]["User"]);
        
        $Proyecto = new MProyectos();
        $Moderacion = new MModeracion();
        
        $P60 = $Moderacion->SQL_SELECT("Proyecto","Estado = 5");
        $FaltanCarg = $this->TopeP2 - count($P60);
        
        if (count(POST::take('id')) <= $FaltanCarg AND POST::take('id')){
            foreach (POST::take('id') as $_Id){
                $Proyecto->Abrir($_Id);
                $Proyecto->setEstado(5);
                $Proyecto->Grabar();
                
                $Moderacion->setAdministrador($IdUser[0]["Id"]);
                $Moderacion->setProyecto($_Id);
                $Moderacion->setEstado(5);
                $Moderacion->Grabar();
                
                $Responsable = new MResponsables();
                $Responsable->Abrir($Proyecto->getResponsable());
                
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                $headers .= 'From: Open APP <info@openapp.com>' . "\r\n";

                ob_start();

                    $Mail = new VMails();
                    $Mail->agregarFunVars(get_defined_vars());
                    $Mail->Top60();

                $HTML = ob_get_contents();

                ob_end_clean();

                mail($Responsable->getEmail(), "Noticias sobre tu proyecto #".$Proyecto->getKey(), $HTML, $headers);
                
                $Proyecto->Clear();
                $Moderacion->Clear();
            }
            $P60 = $Moderacion->SQL_SELECT("Proyecto","Estado = 5");
            $FaltanCarg = $this->TopeP2 - count($P60);
            if($FaltanCarg > 0){
                Funciones::Location(CESIS::$ROOT."admin/Proyectos/Listado/4");
            }else{
                Funciones::Location(CESIS::$ROOT."admin/Proyectos/Listado/4");    
            }  
        }else{
            if(POST::take('id')){$_SESSION["Reporte"]= "La cantidad de proyectos aceptados superan el cupo de 60";}
            Funciones::Location(CESIS::$ROOT."admin/Proyectos/Listado/4");
        }
    }
    
    public function AceptarGanadores(){
        if(!admin_openapp::isLogin()){
            Funciones::Location("/admin/");
        }
        
        $_SESSION["Reporte"]= "";
        
        $Administrador = new MAdministradores();
        $IdUser = $Administrador->BuscarIdUser($_SESSION["LOGIN_ADMIN"]["User"]);
        
        $Proyecto = new MProyectos();
        $Moderacion = new MModeracion();
        
        $PGanadores = $Moderacion->SQL_SELECT("Proyecto","Estado = 6");
        $FaltanCarg = $this->TopeP3 - count($PGanadores);
        
        if (count(POST::take('id')) <= $FaltanCarg AND POST::take('id')){
            foreach (POST::take('id') as $_Id){
                $Proyecto->Abrir($_Id);
                $Proyecto->setEstado(6);
                $Proyecto->Grabar();

                $Moderacion->setAdministrador($IdUser[0]["Id"]);
                $Moderacion->setProyecto($_Id);
                $Moderacion->setEstado(6);
                $Moderacion->Grabar();
                
                $Responsable = new MResponsables();
                $Responsable->Abrir($Proyecto->getResponsable());

                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                $headers .= 'From: Open APP <info@openapp.com>' . "\r\n";

                ob_start();

                    $Mail = new VMails();
                    $Mail->agregarFunVars(get_defined_vars());
                    $Mail->TopGan();

                $HTML = ob_get_contents();

                ob_end_clean();

                mail($Responsable->getEmail(), "Noticias sobre tu proyecto #".$Proyecto->getKey(), $HTML, $headers);

                $Proyecto->Clear();
                $Moderacion->Clear();
            }
            $PGanadores = $Moderacion->SQL_SELECT("Proyecto","Estado = 6");
            $FaltanCarg = $this->TopeP3 - count($PGanadores);
            if($FaltanCarg > 0){
                Funciones::Location(CESIS::$ROOT."admin/Proyectos/Listado/5");
            }else{
                Funciones::Location(CESIS::$ROOT."admin/Proyectos/Listado/5");    
            }  
        }else{
            if(POST::take('id')){$_SESSION["Reporte"]= "La cantidad de proyectos aceptados superan el cupo de 15";}
            Funciones::Location(CESIS::$ROOT."admin/Proyectos/Listado/5");
        }
    }
    
    
    public function ResumentProyecto(){
        if(!admin_openapp::isLogin()){
            Funciones::Location("/admin/");
        }
        
        $Proyecto = new MProyectos();
        $MResponsable = new MResponsables();
        $MProvincia = new MProvincias();
        $MCategoria = new MCategorias();
        $MScatproyecto = new MScatproyecto();
        $MEstado = new MEstadoproyecto();
        $MGradoAvance = new MGradosDeAvances;
        $MSocios = new MSocios();
        
        $Resumen = $Proyecto->SQL_SELECT("*","id = ".GET::take("Id"));
        $Resumen = $Resumen[0];
        
        $MResponsable->Abrir($Resumen["Responsable"]);
        $MProvincia->Abrir($MResponsable->getProvincia());
        $MEstado->Abrir($Resumen["Estado"]);
        $MCategoria->Abrir($Resumen["Categoria"]);
        $MGradoAvance->Abrir($Resumen["gradoavance"]);
        $SubCategorias = $MScatproyecto->SQL_SELECT("*"," Proyecto = ".$Resumen["id"]);
        
        $IdSubCat = "";
        foreach($SubCategorias as $SubCategoria){
            $IdSubCat .= $SubCategoria["SubCategoria"].",";
        }
        $IdSubCat = substr($IdSubCat, 0, (strlen($IdSubCat)-1));
        
        $SubCategorias = $MCategoria->SQL_SELECT("*","id in (".$IdSubCat.")");
        
        $Socios = $MSocios->SQL_SELECT("*","Proyecto = ".$Resumen["id"]);
        
        unset($Resumen["id"]);
        
        $Vista = new VHome();
        $Vista->agregarFunVars(get_defined_vars());
        $Vista->VerProy();
    }
    
    public function SendMailPdf(){
        if(!admin_openapp::isLogin()){
            Funciones::Location("/admin/");
        }
        
        $Ids = explode(",",POST::take("Param"));
        
        $Proyecto = new MProyectos();
        $MSendMail = new MSendmails();
        
                
        if (POST::take("Tipo") == 1){
            $Instancia = "PC";
        }else{
            $Instancia = "SC";
        }
        
        foreach ($Ids as $Id){
            $Proyecto->Abrir($Id);
            $MSendMail->setProyecto($Id);
            $MSendMail->setTipo(POST::take("Tipo"));
            
            $MSendMail->Grabar();
            $MSendMail->Clear();
            
            $Responsable = new MResponsables();
            
            $Responsable->Abrir($Proyecto->getResponsable());

            $Key = md5($Responsable->getEmail()).md5($Proyecto->getId())."".md5(md5($Responsable->getEmail()).md5($Proyecto->getId())).$Instancia;

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            $headers .= 'From: Open APP <info@openapp.com>' . "\r\n";

            ob_start();

                $Mail = new VMails();
                $Mail->agregarFunVars(get_defined_vars());
                $Mail->CargaPdf();

            $HTML = ob_get_contents();

            ob_end_clean();

            $RESPUESTA = mail($Responsable->getEmail(), "Noticias sobre tu proyecto #".$Proyecto->getKey(), $HTML, $headers);
            //var_dump($RESPUESTA);die();
            $Proyecto->Clear();
            $Responsable->Clear();
        }
        
    }
    
    public function VerPdf(){
        if(!admin_openapp::isLogin()){
            Funciones::Location("/admin/");
        }
        
        $Vista = new VHome();
        $Vista->agregarFunVars(get_defined_vars());
        $Vista->VerPdf();
    }

   public function VerPdfAprov(){
        if(!admin_openapp::isLogin()){
            Funciones::Location("/admin/");
        }
        
        $Vista = new VHome();
        $Vista->agregarFunVars(get_defined_vars());
        $Vista->VerPdfAprov();
    }
    
   public function ExaminarArchivo(){
        if(!admin_openapp::isLogin()){
            Funciones::Location("/admin/");
        }
        
        $Vista = new VHome();
        $Vista->agregarFunVars(get_defined_vars());
        $Vista->ExaminarArchivo();
    }
    
    public function Cargarfileapro(){
        $IdProy = GET::take('Id');
        
        $Proyecto = new MProyectos();
        
        $Proyecto->Abrir($IdProy);
        if($Proyecto->getArchivoAprobado() == "" OR is_null($Proyecto->getArchivoAprobado())){
                //echo "si";
                $Archivo = $_FILES["CargaArchivo"];
                $Extencion = explode(".", $Archivo["name"]);
                $Extencion = $Extencion[count($Extencion)-1];
                $newName = $Proyecto->getKey().".".$Extencion;
                if(!move_uploaded_file($Archivo['tmp_name'], "archivos/Aprobacion/".$newName)){
                    echo "<script> window.alert('Hubo un erro al cargar el archivo intentelo nuevamente'); </script>";
                    Funciones::PageBack(-1);
                    die();
                }         
                $Proyecto->setArchivoAprobado($newName);
                $Proyecto->Grabar();
        }
       
        Funciones::Location("/admin/Proyectos/Listado/3");
        
    }
    
    protected function make_bitly_url($url,$login,$appkey,$format = 'xml',$version = '2.0.1'){
      //create the URL
          $bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;

          //get the url
          //could also use cURL here
          $response = file_get_contents($bitly);
          //parse depending on desired format
          if(strtolower($format) == 'json'){
            $json = @json_decode($response,true);
            $return = $json['results'][$url]['shortUrl'];
          }else{ //xml
            $xml = simplexml_load_string($response);
            $return = 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
          }
          return $return;
    }
    
    public function FiltroCatego(){
        if(!admin_openapp::isLogin()){
            Funciones::Location("/admin/");
        }
        $Proyecto = new MProyectos();
        $MResponsable = new MResponsables();
        $MCategoria = new MCategorias();
        $MEstado = new MEstadoproyecto();
        
        $Resumen = $Proyecto->SQL_SELECT("*","Categoria = ".GET::take("Id"));
        
        
    }
}