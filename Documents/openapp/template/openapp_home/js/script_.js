var ROOT = "/"


function Ocultar(Id){
    Effect.SlideUp('Muro', {duration: 1.5});
    document.getElementById(Id).setAttribute("onclick","Mostrar(this.id);return false;");
}

function Mostrar(Id){    
    Effect.SlideDown('Muro', {duration: 1.5});
    document.getElementById(Id).setAttribute("onclick","Ocultar(this.id);return false;");
}

var ListObjCartelitos = new Object();

function AbrirLighBox(Obj, Id, generarGaleria){
    if(ListObjCartelitos[Id] == 0 || ListObjCartelitos[Id] == undefined){
        ListObjCartelitos[Id] = 1;

        Obj.setAttribute("onclick","CerrarLighBox(this,'"+Id+"');");
        Effect.BlindDown(Id, {duration: 2.0, scaleX: true});

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
        Obj.setAttribute("onclick","AbrirLighBox(this,'"+Id+"');");
        Effect.Squish(Id);
    }else{
        AbrirLighBox(Obj, Id);
    }
}

/*Carga de los formularios*/

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

var PLeft;
var IDENForm;
function AbrirForm(IdenForm,PosicionL){
    PLeft = PosicionL;
    IDENForm = IdenForm;
    var formulario = document.getElementById("Form");
    formulario.style.display = "none";
    formulario.style.left = PLeft;
    
    var preload = document.getElementById('preload'+IdenForm);
    var url = "home/FormProy/"+IDENForm;
    new Ajax.Request(url, {
        method: 'post',
        onInteractive:function(){
            preload.src = ROOT+"img/preloaded.png";
        },
        onSuccess: function(Respuesta){
            preload.src = ROOT+"img/btn_postulate.png";
            formulario.innerHTML = Respuesta.responseText;
            Effect.BlindDown('Form', {duration: 0.5, scaleX: true});
        },
        onFailure: function() {
            alert('Se ha producido un error');
            Effect.Squish("Form");
        }
    });
    
}

function Logueado(){
    var formulario = document.getElementById("Form");
    var url = "home/FormProy/"+IDENForm;
    new Ajax.Request(url, {
        method: 'post',
        onInteractive:function(){
            formulario.innerHTML = "<img src=\""+ROOT+"img/preload.gif\" />";
        },
        onSuccess: function(Respuesta){
            formulario.innerHTML = Respuesta.responseText;
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
                        PopUp("Home/MensUsuarioExiste",true);
                        FExiste();
                        
                    }else{
                        FError();
                    }
                }
            },
            onFailure: function() {alert('Se ha producido un error');}
        });  
}


function MensOKReg(){
    PopUp("Home/MensOKReg",true);
    Logueado();
    EliminarElementos();
}
function MensErrorReg(){
    PopUp("Home/MensErrorReg",true);
}
function MensUsuarioExiste(){
    PopUp("Home/MensUsuarioExiste",true);
}

function MensOKLogin(){
    EliminarElementos();
    Logueado();
}
function MensErrorLogin(){
    PopUp("Home/MensErrorLogin",true);
    EliminarElementos();
    window.setTimeout("PopUp('Home/FormLogin/', true)", 3000);
}

function MensOKProy(){
    PopUp("Home/MensOKProy",true);
    Effect.Squish("Form");
}
function MensErrorProy(){
    PopUp("Home/MensErrorProy",true);
}

function MensOKOClave(){
    EliminarElementos();
    PopUp("Home/MensOKOClave");
    EliminarElementos();
}
function MensErrorOClave(){
    PopUp("Home/MensErrorOClave",true);
    EliminarElementos();
    EliminarElementos();
    window.setTimeout("PopUp('Home/FormOClave',true);EliminarElementos();", 5000);
    
}

function ValidarFormAjax(UrlDestino, objForm, FunOk, FunError){
    if(Validar(objForm)){
        SubmitForm(UrlDestino, objForm, FunOk, FunError);
    }    
    return false;
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
       ImgCerrar.src = ROOT+"img/x.png";

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
        new Ajax.Request(UrlDestino,{
            method: 'post',
            parameters: {Email:objForm.Email.value,Nombre:objForm.Nombre.value,Asunto:objForm.Asunto.value,Comentario:objForm.Comentario.value},
            onSuccess: function() {
               EliminarElementos();
               PopUp('Home/GraciasConsulta',true);
                },
            onFailure: function() {alert('Se ha producido un errorr')}   
            });
}