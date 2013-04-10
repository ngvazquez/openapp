var ROOT = "/";


function ResetFiltro(Ides){
    Redirec = ROOT+"admin/Proyectos/Listado/"+Ides;
    window.location.href = Redirec;
}

function PaginaList(Pag,url){
    new Ajax.Request(url, {
        method: 'post',
        parameters: {Pagina:Pag},
        onSuccess: function(respuesta){
            document.body.innerHTML = respuesta.responseText;
            //window.location.reload();
        },
        onFailure: function() {alert('Se ha producido un error');}
    });
}


function PopUp(url, trasparent){
    //var trasparent = trasparent;
    //var url = url;
    new Ajax.Request(url, {
        method: 'post',
        parameters: {trasparent:trasparent},
        onSuccess: AccionRespuestaPopUp(trasparent),
        onFailure: function() {alert('Se ha producido un error');}
    });   
}

function AccionRespuestaPopUp(trasparent){
    return function(response){ 
       var pop_up = document.body;
       var contMensaje = document.createElement("div");
       var mensaje = document.createElement("div");
       var TextMensajeInt = document.createElement("div");
       var ContImgCerrar = document.createElement("div");
       var ImgCerrar = document.createElement("img");
       ImgCerrar.src = ROOT+"img/x.png";

       ImgCerrar.onclick = function () {EliminarElementos()}; 

       contMensaje.setAttribute("id","contMensaje");
       mensaje.setAttribute("id","mensaje");

        if(trasparent){
             mensaje.setAttribute("style","background: none");
         }else{
             mensaje.setAttribute("style","background: #fff");
         }

       TextMensajeInt.setAttribute("id","TextMensajeInt");
       ContImgCerrar.setAttribute("id","ContImgCerrar");

       pop_up.appendChild(contMensaje);
       contMensaje.appendChild(mensaje);
       mensaje.appendChild(ContImgCerrar);
       mensaje.appendChild(TextMensajeInt);
       ContImgCerrar.appendChild(ImgCerrar);

     TextMensajeInt.innerHTML = response.responseText;
    }   
}

function EliminarElementos(){

var contMensaje = document.getElementById("contMensaje");

var pop_up = contMensaje.parentNode;
pop_up.removeChild(contMensaje); 
}

/*
function VerProyecto(Id){
    url = "ResumentProyecto/"
    var DivContenedor = document.
    new Ajax.Request(url, {
        method: 'post',
        parameters: {Pagina:Id},
        onSuccess: function(respuesta){
            document.getElementById("tab1").innerHTML = respuesta.responseText;
        },
        onFailure: function() {alert('Se ha producido un error');}
    });
}*/

function SelectAllCheck(ControlCheck, NombreC){
    Check = document.getElementsByName(NombreC);

    for(x in Check){
        
        if(ControlCheck.checked == true){
            Check[x].checked = true;
            Check[x].onclick = function(event){ControladorCheck(this, ControlCheck.id)};
        }else{
            Check[x].checked = false;
            Check[x].onclick = function(event){ControladorCheck(this, ControlCheck.id)};
        }
    }
}

function ControladorCheck(objCheck, IdCCheck){
    Checks = document.getElementsByName(objCheck.name);

    AllCheck = true;
    for(x in Checks){
        if(Checks[x].checked == false){
            AllCheck = false;
        }
    }

    document.getElementById(IdCCheck).checked = AllCheck;
}


function SendMail(Instancia,NombreC){
    if (window.confirm('Esta seguro que desea enviarle el mail al responsable para que cargue el instructivo?')){
        Check = document.getElementsByName(NombreC);
        Checkeados = false;
        var ids = "";
        for(x in Check){
            if(Check[x].checked == true){
                ids += Check[x].value+"," ;
            }
        }
        var Cant =ids.length;
        if (Cant > 0){
            ids = ids.substr(0,(Cant-1));
            var url = "SendMailPdf";
            new Ajax.Request(url, {
                method: 'post',
                parameters: {Param:ids,Tipo:Instancia},
                onSuccess: function(respuesta){
                    window.alert("Se envio exitosamente");
                    window.location.reload();
                },
                onFailure: function() {window.alert('Se ha producido un error');}
            });
        }else{
            window.alert("Seleccione algun proyecto");
        }
        
    }
    
    return false;
}



function Valickeck(objform){
    var checkboxes = document.getElementById("FormCkack").checkbox;
    for (var x=0; x < checkboxes.length; x++) {
        if (checkboxes[x].checked) {
            Rechazar(checkboxes[x].value);    
        }
    }
}

function Rechazar(valueCheck){
    if(window.confirm('Esta seguro que desea rechazar este proyecto?')) {
        url = "/admin/Proyectos/Rechazar/"+valueCheck;
        new Ajax.Request(url, {
            method: 'get',
            onSuccess: function(respuesta){
                 window.location.href = ROOT+"admin/Proyectos/Listado/2";
            },
            onFailure: function() {alert('Se ha producido un error');}
        });
    }
}

function ValickeckRecha(objform){
    
    var checkboxes = document.getElementById("FormMode").checkboxRacha;
    for (var x=0; x < checkboxes.length; x++) {
        if (checkboxes[x].checked) {
            RechazarMode(checkboxes[x].value);    
        }
    }
}

function RechazarMode(valueCheck){
    if(window.confirm('Esta seguro que desea pasar a moderar este proyecto?')) {
        url = "/admin/Proyectos/RechazarMode/"+valueCheck;
        new Ajax.Request(url, {
            method: 'get',
            onSuccess: function(respuesta){
                 window.location.href = ROOT+"admin/Proyectos/Listado/0";
            },
            onFailure: function() {alert('Se ha producido un error');}
        });
    }
}



function VerificarExtencion(Obj, Typo){
   var archivo = Obj.value;
   var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase(); 
   if (Typo == "Logo"){
        if ( (extension != ".jpg") && (extension != ".png") && (extension != ".gif")){
           alert("Solo podes subir imagenes con extension jpg, png y gif");
           Obj.value = "";
        }
   }else{
       if ( (extension != ".pdf") && (extension != ".doc") && (extension != ".docx") && (extension != ".ppt") && (extension != ".pptx") && (extension != ".pps") && (extension != ".ppsx")){
           alert("Solo podes subir archivos con extension pdf, doc, docx, ppt, pptx, pps");
           Obj.value = "";
        }
   }
   
}


function CambiarA180(EstadoActual, IdProy){
    if(EstadoActual == 10){
        if(window.confirm('Esta seguro que desea que estos proyectos pasen a la siguiente etapa?\n(Tenga en cuenta que se enviara un mail a los responsables avisando el cambio de estado del proyecto)')){
            EnviarForm180(IdProy)
        }
    }else{
        window.alert("No puede aceptar el proyecto")
    }
}

function CambiarDeEstadoProy(IdProy,estado,numero,instacia,Rows, EstadoActual){
    
    ValorEst = document.getElementById(estado).innerHTML;
    
    if(ValorEst.search("si") != -1){
       alert("No puede cambiar el estado a "+(instacia-6))
    }else{
        if((EstadoActual < (instacia-1)) && (!(instacia == 7 && EstadoActual == 3))){
            VentanaAler = "Imposible cambiar el estado a "+(instacia-6);
            window.alert(VentanaAler);
        }else{
            if (window.confirm('Esta seguro que desea aprobar este proyecto?')){
                url = "/admin/Proyectos/CambiarDeEstado/"+IdProy+"/"+instacia;
                new Ajax.Request(url, {
                    method: 'get',

                    onSuccess: function(respuesta){

                        document.getElementById(estado).innerHTML = 'si';
                        window.location.reload();

                    },
                    onFailure: function() {alert('Se ha producido un error');}
                });
                //parameters: {IdProy:IdProy,estado:estado,numero:numero,instacia:instacia},
           }
       }
   }
}

function EnviarForm180(IdProy){
    url = "/admin/Proyectos/Aceptar180/"+IdProy;
    new Ajax.Request(url, {
        method: 'get',
        onSuccess: function(respuesta){
            window.location.reload();
        },
        onFailure: function() {alert('Se ha producido un error');}
    }); 
    
}

/*window.onload = Seteo(estado,numero,instacia,Rows);

function Seteo(estado,numero,instacia,Rows){
    estadofilas = estado;
    estadofilas = estadofilas.substring(7);

    estadofilas  = estadofilas - (Rows-1) *4;
    estadofilas = parseInt(estadofilas);
    Id = document.getElementById(estado).innerHTML;
    Id = Id.split('>');
    Id = Id[1].split('<');
    Id = Id[0];
    
    if (estadofilas == 1 && Id == 'si'){
        IdSiguiente = estado.substring(7);
        for(i = estadofilas; i<=3; i++){
            IdSiguiente = parseInt(IdSiguiente)+1;
            document.getElementById('Estado_'+IdSiguiente).blur();
        }
    } 
}*/

function Filtro(Ides, objSelect){
    IdEnviar = document.getElementById('EnviarDatos');
    Id = document.getElementById('FormFiltro');
    indice = objSelect.selectedIndex;
    ValorTomado = objSelect.options[indice].value;
    
    if(ValorTomado == ""){
       window.location.href = ROOT+"admin/Proyectos/Listado/"+Ides; 
    }
    
    
    if(ValorTomado == 'Categoria'){
        IdCate = document.getElementById('FormFiltroCate');
        IdCate.style.display = 'block';
        /*
        indice = IdCate.selectedIndex;
        ValorTomadoCat = IdCate.options[indice].value;
        */
        IdEnviar.style.display = 'block';        
        //url = ROOT+"admin/Proyectos/Listado/"+Ides+"/"+ValorTomado;

    }else if(ValorTomado == 'NumeroProyecto'){
        Idnumproy = document.getElementById('SelectFiltroCod');
        Idnumproy.style.display = 'block';
        //ValorTomadoVal = Idnumproy.value;
        IdEnviar.style.display = 'block';

        //url = ROOT+"admin/Proyectos/Listado/"+Ides+"/"+ValorTomado;

    }else if(ValorTomado == 'Estados'){
        IdEStados = document.getElementById('FormFiltroEstado');
        IdEStados.style.display = 'block';
        //indice = IdEStados.selectedIndex;
        //ValorTomadoEsta = IdEStados.options[indice].value;
        IdEnviar.style.display = 'block';


        //url = ROOT+"admin/Proyectos/Listado/"+Ides+"/"+ValorTomado;
    }
    

    //FiltroEnviar(jua,ValorTomadoCat,ValorTomadoVal,ValorTomadoEsta);
}

function FiltroEnviar(IdEsta){
    
    IdEStados = document.getElementById('FormFiltroEstado');
    if(IdEStados != undefined){
        indice = IdEStados.selectedIndex;
        ValorTomadoEsta = IdEStados.options[indice].value;
        if (ValorTomadoEsta == ""){
            ValorTomadoEsta = "Indistinto";
        }
    }else{
        ValorTomadoEsta = "Indistinto";
    }    
    
    Idnumproy = document.getElementById('SelectFiltroCod');
    ValorTomadoVal = Idnumproy.value;
    
    if (ValorTomadoVal == ""){
        ValorTomadoVal = "Indistinto";
    }

    IdCate = document.getElementById('FormFiltroCate');
    indice = IdCate.selectedIndex;
    ValorTomadoCat = IdCate.options[indice].value;
   
    
    if (ValorTomadoCat == ""){
        ValorTomadoCat = "Indistinto";
    }
    
    url = ROOT+"admin/Proyectos/Listado/"+IdEsta+"/"+ValorTomadoCat+"/"+ValorTomadoVal+"/"+ValorTomadoEsta;
    
    if(IdEsta == 2){
       url = ROOT+"admin/Proyectos/Listado/"+IdEsta+"/"+ValorTomadoCat+"/"+ValorTomadoVal; 
    }
    
    if(IdEsta == 0){
       url = ROOT+"admin/Proyectos/Listado/"+IdEsta+"/"+ValorTomadoCat+"/"+ValorTomadoVal; 
    }
    
    if(IdEsta == 4){
       url = ROOT+"admin/Proyectos/Listado/"+IdEsta+"/"+ValorTomadoCat+"/"+ValorTomadoVal; 
    }
    
    if(IdEsta == 5){
       url = ROOT+"admin/Proyectos/Listado/"+IdEsta+"/"+ValorTomadoCat+"/"+ValorTomadoVal; 
    }
    
    if(IdEsta == 6){
       url = ROOT+"admin/Proyectos/Listado/"+IdEsta+"/"+ValorTomadoCat+"/"+ValorTomadoVal; 
    }
    
    if(IdEsta == 1){
       url = ROOT+"admin/Proyectos/Listado/"+IdEsta+"/"+ValorTomadoCat+"/"+ValorTomadoVal; 
    }
    

    FormFiltro = document.getElementById('FormFiltro');
    FormFiltro.action = url;
    FormFiltro.submit();

}
