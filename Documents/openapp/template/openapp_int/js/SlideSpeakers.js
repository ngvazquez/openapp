var ROOT = "/";
function CreateGaleria(idGaleria, UnoALaVez){
    Galeria = $(idGaleria)
    
    ItemsG = Element.childElements(Galeria);

    if(UnoALaVez == true){
        EspacioOcup = ItemsG[0].getWidth();
    }else{
        EspacioOcup = ItemsG[0].getWidth() + eval(5);
    }

    EspacioTot = EspacioOcup * ItemsG.length;

    Galeria.setStyle({width: EspacioTot+'px'});

    Padre = Galeria.getOffsetParent();
    Abuelo = Padre.getOffsetParent();
    Abuelo = Abuelo.getOffsetParent();

    if(Galeria.getWidth() > Padre.getWidth()){
        FlechaD = document.createElement('div');
        Element.writeAttribute(FlechaD, "DivOrigen", idGaleria)
        Element.addClassName(FlechaD, 'FlechaGalD');
        FlechaD.innerHTML = "<img src='"+ROOT+"img/flecha_r.png' alt='>' />";

        FlechaI = document.createElement('div');
        Element.writeAttribute(FlechaI, "DivOrigen", idGaleria)
        Element.addClassName(FlechaI, 'FlechaGalI');
        FlechaI.innerHTML = "<img src='"+ROOT+"img/flecha_l.png' alt='>' />";

        if(UnoALaVez == true){
            Element.observe(FlechaD, "click", MoverDGaleriaUALV);
            Element.observe(FlechaI, "click", MoverIGaleriaUALV);
            Element.writeAttribute(FlechaI, "TamMover", ItemsG[0].getWidth());
            Element.writeAttribute(FlechaD, "TamMover", ItemsG[0].getWidth());
            Element.writeAttribute(Abuelo, "NImg", "0");
            Element.writeAttribute(Abuelo, "TImg", ItemsG.length);
        }else{
            Element.observe(FlechaD, "mouseover", MoverDGaleriaOver);
            Element.observe(FlechaD, "mouseout", MoverDGaleriaOut);
            Element.observe(FlechaI, "mouseover", MoverIGaleriaOver);
            Element.observe(FlechaI, "mouseout", MoverIGaleriaOut);
        }

        Abuelo.appendChild(FlechaD);
        Abuelo.appendChild(FlechaI);
    }
}

var MoveD = false;
var FlechaD = "";
function MoverDGaleriaOver(event){
    MoveD = true;
    FlechaD = Event.element(event);
    setTimeout("MoverDGaleria(FlechaD)", 100);
}
function MoverDGaleriaOut(event){
    MoveD = false;
}
function MoverDGaleria(Flecha){
   if(MoveD){
       var idGaleria = Element.readAttribute(Flecha, "DivOrigen");

       if(idGaleria == undefined){
           Flecha = Flecha.getOffsetParent();
           idGaleria = Element.readAttribute(Flecha, "DivOrigen");
       }

       var Left = $(idGaleria).getStyle("left");
       if(Left == null){
           Left = 0;
       }else{
           Left = Left.replace("px", "");
       }
       var newLeft = Left - 15;

       var Width = $(idGaleria).getWidth();
       var PWidth = $(idGaleria).getOffsetParent().getWidth();
       
       if((Width + eval(Left)) > PWidth){
           Galeria.setStyle({left: newLeft+'px'});

           setTimeout("MoverDGaleria(FlechaD)", 100);
       }
   }
}

var MoveI = false;
var FlechaI = "";
function MoverIGaleriaOver(event){
    MoveI = true;
    FlechaI = Event.element(event);
    setTimeout("MoverIGaleria(FlechaI)", 100);
}
function MoverIGaleriaOut(event){
    MoveI = false;
}
function MoverIGaleria(Flecha){
   if(MoveI){
       var idGaleria = Element.readAttribute(Flecha, "DivOrigen");

       if(idGaleria == undefined){
           Flecha = Flecha.getOffsetParent();
           idGaleria = Element.readAttribute(Flecha, "DivOrigen");
       }

       var Left = $(idGaleria).getStyle("left");
       if(Left == null){
           Left = 0;
       }else{
           Left = Left.replace("px", "");
       }
       var newLeft = eval(Left) + eval(15);
       if(newLeft < 0){
           Galeria.setStyle({left: newLeft+'px'});

           setTimeout("MoverIGaleria(FlechaI)", 100);
       }else{
           Galeria.setStyle({left: '0px'});
       }
   }
}

function MoverDGaleriaUALV(event){
    Flecha = Event.element(event);
    var idGaleria = Element.readAttribute(Flecha, "DivOrigen");

    if(idGaleria == undefined){
       Flecha = Flecha.getOffsetParent();
       idGaleria = Element.readAttribute(Flecha, "DivOrigen");
    }
    
    TamMover = Element.readAttribute(Flecha, "TamMover");
    Padre = Flecha.getOffsetParent();
    NImg = Element.readAttribute(Padre, "NImg");
    TImg = Element.readAttribute(Padre, "TImg");
    
    if(eval(NImg) < (eval(TImg)-1)){
        NewNImg = eval(NImg) + 1;
        Element.writeAttribute(Padre, "NImg", NewNImg);

        Galeria = document.getElementById(idGaleria);
        new Effect.Move(Galeria, {x: -(eval(TamMover)*NewNImg), mode: 'absolute'});
    }
}
function MoverIGaleriaUALV(event){
    Flecha = Event.element(event);
    var idGaleria = Element.readAttribute(Flecha, "DivOrigen");

    if(idGaleria == undefined){
       Flecha = Flecha.getOffsetParent();
       idGaleria = Element.readAttribute(Flecha, "DivOrigen");
    }

    TamMover = Element.readAttribute(Flecha, "TamMover");
    Padre = Flecha.getOffsetParent();
    NImg = Element.readAttribute(Padre, "NImg");
    TImg = Element.readAttribute(Padre, "TImg");

    if(eval(NImg) > 0){
        NewNImg = eval(NImg) - 1;
        Element.writeAttribute(Padre, "NImg", NewNImg);

        Galeria = document.getElementById(idGaleria);
        new Effect.Move(Galeria, {x: -(eval(TamMover)*NewNImg), mode: 'absolute'});
    }
}

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
    window.onscroll = ControlTop;
})