var ROOT = "/"

function cambiarimg(objImg, ROOT){
    Img = objImg.src;
    Img = Img.split("/");
    Img = Img[(Img.length-1)];
    
    if(Img != 'Speakers_top.png'){
       objImg.src = 'http://www.openapp.com.ar/img/Speakers_top.png' 
    }else{
       objImg.src = 'http://www.openapp.com.ar/img/Speakers_bottom.png' 
    }
}
function Ocultar(Id){
    Effect.SlideUp('Muro', {duration: 3.0});
    document.getElementById(Id).setAttribute("onclick","Mostrar(this.id);return false;");
}

function Mostrar(Id){    
    Effect.SlideDown('Muro', {duration: 3.0});
    document.getElementById(Id).setAttribute("onclick","Ocultar(this.id);return false;");
}


var ListObjCartelitos = new Object();
function ControlaCartelitos(Id, generarGaleria){
    if(ListObjCartelitos[Id] == 0 || ListObjCartelitos[Id] == undefined){
        AbrirLighBox(undefined, Id, generarGaleria)
    }else{
        CerrarLighBox(undefined,Id)
    }
}

function AbrirLighBox(Obj, Id, generarGaleria){
    if(ListObjCartelitos[Id] == 0 || ListObjCartelitos[Id] == undefined){
        ListObjCartelitos[Id] = 1;
        
        if(Obj != undefined){
            Obj.setAttribute("onclick","CerrarLighBox(this,'"+Id+"');");
        }
        Effect.BlindDown(Id, { duration: 2.0, scaleX: true});

        ElementCerrar = document.getElementById(Id+"-Cerrar");
        if(ElementCerrar != undefined){
            ElementCerrar.setAttribute("onclick","CerrarLighBox(this,'"+Id+"');");
        }

        if(generarGaleria != undefined){
            setTimeout("CreateGaleria('"+generarGaleria+"', 11)", 100);
        }
    }else{
        CerrarLighBox(Obj,Id);
    }
}

function CerrarLighBox(Obj,Id){
    if(ListObjCartelitos[Id] == 1 || ListObjCartelitos[Id] == undefined){
        ListObjCartelitos[Id] = 0;
        if(Obj != undefined){
            Obj.setAttribute("onclick","AbrirLighBox(this,'"+Id+"');");
        }
        Effect.Squish(Id);
    }else{
        AbrirLighBox(Obj, Id);
    }
}

/*Carga de los formularios*/


function AbrirForm(IdenForm,PosicionL){
    var formulario = document.getElementById("Form");
    formulario.style.display = "none";
    formulario.style.left = PosicionL;
    formulario.innerHTML = '<div class="Right"><img class="PunteroCursor" src="'+ROOT+'img/x.png" onclick="javascript:Effect.Squish(\'Form\');"/></div>';
    formulario.innerHTML += '<div class="Clr"></div>';
    
    
    setTimeout("Effect.BlindDown('Form', { duration: 2.0, scaleX: true})",60);
    var url = "home/FormProy/"+IdenForm;
    new Ajax.Request(url, {
        method: 'post',
        onLoading:function(){
            formulario.innerHTML += "<img src=\""+ROOT+"img/preload.gif\" />";
        },
        onSuccess: function(Respuesta){
            formulario.innerHTML = '<div class="Right"><img class="PunteroCursor" src="'+ROOT+'img/x.png" onclick="javascript:Effect.Squish(\'Form\');"/></div>';
            formulario.innerHTML += '<div class="Clr"></div>';
            formulario.innerHTML += "<p style=\"color:white;font-size:12px;\">"+Respuesta.responseText+"</p>";
            formulario.innerHTML += '<div><img class="PunteroCursor" src="'+ROOT+'img/x.png" onclick="PopUp(\'Home/FormReg\',false);"/></div>';
        },
        onFailure: function() {
            alert('Se ha producido un error');
            Effect.Squish("Form");
        }
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



function SubmitForm(Action, ObjF, FOk, FError, FExiste){  
        var longitudFormulario = ObjF.elements.length;
        var cadenaFormulario = "";
        var sepCampos = "";
        for (var i=0; i <= longitudFormulario-1;i++) {
            switch(ObjF.elements[i].type){
                case "checkbox":
                            if(ObjF.elements[i].checked == true){
                                cadenaFormulario += sepCampos + ObjF.elements[i].name + '=' + encodeURI(ObjF.elements[i].value);
                                sepCampos="&";
                            }
                            break;
                default:
                        cadenaFormulario += sepCampos + ObjF.elements[i].name + '=' + encodeURI(ObjF.elements[i].value);
                        sepCampos="&"; 
                        break;
            }
            
        }
        
        new Ajax.Request(Action, {
            method: 'post',
            postBody:cadenaFormulario,
            onSuccess:function(Respuesta){
                if (Respuesta.responseText == "OK"){
                    FOk();
                }else{
                    if( Respuesta.responseText == "USUARIOC"){
                        FExiste();
                    }else{
                        FError();
                    }
                }
            },
            onFailure: function() {alert('Se ha producido un error');}
        });  
}

function Logueado(url){
    window.location.href = url;
}

function MensOKReg(){
    PopUp(ROOT+"Home/MensOKReg",true);
    window.alert("Se registro correctamente");
    Logueado();
    EliminarElementos();
}
function MensErrorReg(){
     PopUp(ROOT+"Home/MensErrorReg",true);
}
function MensUsuarioExiste(){
    PopUp(ROOT+"Home/MensUsuarioExiste",true);
}

function MensOKLogin(){
    EliminarElementos();
    Logueado(ROOT+"Proyectos/Inscripcion/");
    
}
function MensErrorLogin(){
    PopUp(ROOT+"Home/MensErrorLogin",true);
    EliminarElementos();
    window.setTimeout("PopUp("+ROOT+"'/Home/FormLogin/', true);EliminarElementos();", 3000);
}

function MensOKProy(){
    PopUp(ROOT+"Home/MensOKProy",true);
    Effect.Squish("Form");
}
function MensErrorProy(){
    PopUp(ROOT+"Home/MensErrorProy",true);
}

function MensOKOClave(){
    EliminarElementos();
    PopUp(ROOT+"Home/MensOKOClave",true);
    EliminarElementos();
}
function MensErrorOClave(){
    EliminarElementos();
    EliminarElementos();
    PopUp(ROOT+"Home/MensErrorOClave",true);
    window.setTimeout("PopUp("+ROOT+"'Home/FormOClave',true);EliminarElementos();", 4000);
}

//################################################

function MatarDiv(id){
    var ObjBody = document.body;
    var ObjEliminar = document.getElementById(id);
    ObjBody.removeChild(ObjEliminar);
}

function MensajeAuto(Cerrar, ObjBtn, Mensaje, ExtPLeft, ExtPTop){
    var DivMensaje = document.getElementById("MensajeAuto");
    
    if(DivMensaje != undefined){
        DivMensaje.id = "MensajeAutoCerrando";
        Effect.Squish(DivMensaje.id);
        if(!Cerrar){
           MensajeAuto(Cerrar, ObjBtn, Mensaje, ExtPLeft, ExtPTop);
        }
        setTimeout('MatarDiv("'+DivMensaje.id+'")', 1000);
    }else{
        var ObjBody = document.body;
        DivMensaje = document.createElement("div");
        DivMensaje.style.display = "none";
        
        PosLeft = GetLeftPos(ObjBtn);
        PosTop = GetTopPos(ObjBtn);
        
        if(ExtPLeft != undefined){
            PosLeft = PosLeft + ExtPLeft;
        }
        
        if(ExtPTop != undefined){
            PosTop = PosTop + ExtPTop;
        }
        
        DivMensaje.style.left = PosLeft+"px";
        DivMensaje.style.top = PosTop+"px";
        DivMensaje.style.position = "absolute";
        DivMensaje.style.zIndex = 5;
        DivMensaje.id = "MensajeAuto";
        
        var HTML = decodeURIComponent(Mensaje);
        HTML = HTML.replace(/\+/gi, " ");

        DivMensaje.innerHTML = decodeURIComponent(HTML);
        ObjBody.appendChild(DivMensaje);
        
        Effect.BlindDown(DivMensaje.id, {duration: 1.0, scaleX: true});
        
    }
}

function GetLeftPos(ObjElement){
    Left = 0;
    
    Position = ObjElement.positionedOffset();
    Left = Left+Position[0];
    
    Padre = ObjElement.getOffsetParent();
    while(Padre.nodeName.toLowerCase() != "body"){
        Position = Padre.positionedOffset();
        Left = Left+Position[0];
        
        Padre = Padre.getOffsetParent();
    }
    Position = Padre.positionedOffset();
    Left = Left+Position[0];
    
    return Left;
}

function GetTopPos(ObjElement){
    Top = 0;
    
    Position = ObjElement.positionedOffset();
    Top = Top+Position[1];
    
    Padre = ObjElement.getOffsetParent();

    while(Padre.nodeName.toLowerCase() != "body"){
        Position = Padre.positionedOffset();
        Top = Top+Position[1];
        
        Padre = Padre.getOffsetParent();
    }
    Position = Padre.positionedOffset();
    Top = Top+Position[1];

    Top = ObjElement.getHeight() + Top;

    return Top;
}


/*Codigo del top Inicio*/

function borrardiv(){
    var idiv=$('topcontrol');
    idiv.style.display="none";
}

function ControlTop(){
    var idiv = document.getElementById('topcontrol');
    var iscroll = 0;
    
    if(idiv != null){
        if(window.pageYOffset){
            iscroll = window.pageYOffset;
        }else{
            iscroll = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
        }

        if (iscroll > 0){
            //idiv.setOpacity(1);
            if(idiv.style.display != "block"){
                idiv.style.display = "block";
                new Effect.Opacity("topcontrol", {duration:0.4, from:0.0, to:1.0});
            }
        }else{
            new Effect.Opacity("topcontrol", {duration:1.0, from:1.0, to:0.0});
            setTimeout(borrardiv,1000);                                                    
        }
    }
}

Event.observe(window, 'load', function() {
    $$('a[href^=#]:not([href=#])').each(function(element) {
        element.observe('click', function(event) {
            new Effect.ScrollTo(this.hash.substr(1));
            Event.stop(event);
        }.bindAsEventListener(element))
    })
    $$('area[href^=#]:not([href=#])').each(function(element) {
        element.observe('click', function(event) {
            new Effect.ScrollTo(this.hash.substr(1));
            Event.stop(event);
        }.bindAsEventListener(element))
    })
    window.onscroll = ControlTop;
})

/*Codigo del top Fin*/

function ValidarFormAjax(UrlDestino, objForm, FunOk, FunError){
    if(Validar(objForm)){
        SubmitForm(UrlDestino, objForm, FunOk, FunError);
    }    
    return false;
}

/* CODIGO PARA GENERAR DINAMICAMENTE NOMBRE Y APELLIDO DE SOCIOS */

var CantSocios = 0;

function CambiarInput(Cant){
    if (Cant == 0){
        document.getElementById("Socios").innerHTML="";
    }else{
        var i;
        document.getElementById("Socios").innerHTML='<div align="left" style="width:200px;font-weight:bold;margin-bottom:5px;">Socio 1</div>';
        document.getElementById("Socios").innerHTML+='<div class="Left" style="width: 150px;">Nombre</div>';
        document.getElementById("Socios").innerHTML+='<div class="Left" style="width: 150px">Apellido</div>';
        document.getElementById("Socios").innerHTML+='<div class="Clr"></div>';
        document.getElementById("Socios").innerHTML+='<div class="Left" style="width: 150px;margin-right: 2px;"><input type="text" value="" name="NombreSocios[]" style="width: 120px"/></div>';
        document.getElementById("Socios").innerHTML+='<div class="Left" style="width: 150px"><input type="text" value="" name="ApellidoSocios[]" style="width: 120px"/></div>';
        document.getElementById("Socios").innerHTML+='<div class="Clr"></div>';
        
        for(i=2;i <= Cant; i++){
            document.getElementById("Socios").innerHTML+='<div align="left" style="width:200px;font-weight:bold;margin-bottom:5px;">Socio '+i+'</div>';
            document.getElementById("Socios").innerHTML+='<div class="Left" style="width: 150px;">Nombre</div>';
            document.getElementById("Socios").innerHTML+='<div class="Left" style="width: 150px">Apellido</div>';
            document.getElementById("Socios").innerHTML+='<div class="Clr"></div>';
            document.getElementById("Socios").innerHTML+='<div class="Left" style="width: 150px;margin-right: 2px;"><input type="text" name="NombreSocios[]" style="width: 120px"/></div>';
            document.getElementById("Socios").innerHTML+='<div class="Left" style="width: 150px"><input type="text" name="ApellidoSocios[]" style="width: 120px"/></div>';
            document.getElementById("Socios").innerHTML+='<div class="Clr"></div>';
        }
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

function PopUpConsulta(url, trasparent){
    //var trasparent = trasparent;
    //var url = url;
    new Ajax.Request(url, {
        method: 'get',
        parameters: {trasparent:trasparent},
        onSuccess: AccionRespuestaPopUpConsulta(trasparent),
        onFailure: function() {alert('Se ha producido un error');}
    });   
}

function AccionRespuestaPopUpConsulta(trasparent){
    return function(response){ 
       var pop_up = document.body;
       var contMensaje = document.createElement("div");
       var mensaje = document.createElement("div");
       var TextMensajeInt = document.createElement("div");
       var ContImgCerrar = document.createElement("div");
       var ImgCerrar = document.createElement("img");
       ImgCerrar.src = "../../img/x.png";

       ImgCerrar.onclick = function () {EliminarElementos()}; 

       contMensaje.setAttribute("id","contMensaje");
       mensaje.setAttribute("id","mensajeConsulta");

        if(trasparent){
             mensaje.setAttribute("style","background: none");
         }else{
             mensaje.setAttribute("style","background: #fff");
         }

       TextMensajeInt.setAttribute("id","TextMensajeInt");
       ContImgCerrar.setAttribute("id","ContImgCerrarConsulta");

       pop_up.appendChild(contMensaje);
       contMensaje.appendChild(mensaje);
       mensaje.appendChild(ContImgCerrar);
       mensaje.appendChild(TextMensajeInt);
       ContImgCerrar.appendChild(ImgCerrar);

     TextMensajeInt.innerHTML = response.responseText;
    }   
}

function FormConsulta(UrlDestino, objForm){
    if(Validar(objForm)){
        SubmitFormConsulta(UrlDestino, objForm);
    }    
    return false;
}

function SubmitFormConsulta(UrlDestino, objForm){
        var Url = ROOT+UrlDestino;
        new Ajax.Request(Url,{
            method: 'post',
            parameters: {Email:objForm.Email.value,Nombre:objForm.Nombre.value,Asunto:objForm.Asunto.value,Comentario:objForm.Comentario.value},
            onSuccess: function() {
               EliminarElementos();
               PopUp(ROOT+'Home/GraciasConsulta',true);
                },
            onFailure: function() {alert('Se ha producido un errorr')}   
            });
}