/*
 *@Autor Andr�s Omar Brizuela
 *@Year 2010
*/
	var Validador = new Array();
	/**** POR DEFECTO VALIDADOR EXPREG ****/
		var PorDefectoRegExp = new Array();
		PorDefectoRegExp.push({nombre: "mail", regexp: /(^[0-9a-z]([0-9a-z_\.-]*)@([0-9a-z_\.-]*)([.][a-z]{3})$)|(^[0-9a-z]([0-9a-z_\.-]*)@([0-9a-z_\.-]*)([.][a-z]{2})$)|(^[a-z]([a-z_\.-]*)@([a-z_\.-]*)(\.[a-z]{3})(\.[a-z]{2})*$)/i });
		PorDefectoRegExp.push({nombre: "numero", regexp: /(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/ });
		PorDefectoRegExp.push({nombre: "cadena", regexp: /(^-?\D\D*\.\D*$)|(^-?\D\D*$)|(^-?\.\D\D*$)/ });
		PorDefectoRegExp.push({nombre: "entero", regexp: /(^-?\d\d*$)/ });
	/**** FIN VALIDADOR EXPREG ****/
	/**** POR DEFECTO VALIDADOR FUNCIONES ****/
		var PorDefectoFuncion = new Array();
		PorDefectoFuncion.push({nombre: "rq", funcion: "validaRQ", parametros: false, vacio: true});
		PorDefectoFuncion.push({nombre: "multiplechoice", funcion: "validaMChoice", parametros: true});
		PorDefectoFuncion.push({nombre: "repetir", funcion: "validaReplica", parametros: true});
		PorDefectoFuncion.push({nombre: "fecha", funcion: "validaFecha", parametros: false});
        PorDefectoFuncion.push({nombre: "MayorDeEdad", funcion: "ValidarMayorDeEdad", parametros: false});
        PorDefectoFuncion.push({nombre: "CodInt", funcion: "ValidarCodInt_Telefono", parametros: false});
        PorDefectoFuncion.push({nombre: "Telefono", funcion: "ValidarTelefono", parametros: false});
		PorDefectoFuncion.push({nombre: "dia", funcion: "validaDia", parametros: false});
		PorDefectoFuncion.push({nombre: "mes", funcion: "validaMes", parametros: false});
		PorDefectoFuncion.push({nombre: "ano", funcion: "validaAno", parametros: false});
	/**** FIN VALIDADOR FUNCIONES ****/
	
	var MensajesError = new Array();
	/**** POR DEFECTO MENSAJES DE ERROR ****/
		var PorDefectoMensajes = new Array();
		PorDefectoMensajes.push({nombre: "pordefecto", error: "Complete todos los campos requridos."});
		PorDefectoMensajes.push({nombre: "multiplechoice", error: "Por favor responde todas las preguntas."});
		PorDefectoMensajes.push({nombre: "repetir", error: "Los datos no coinciden."});
		PorDefectoMensajes.push({nombre: "fecha", error: "Ingrese una fecha correcta (ej.: dd/mm/aaaa)."});
		PorDefectoMensajes.push({nombre: "dia", error: "Ingrese un numero de dia correcto."});
		PorDefectoMensajes.push({nombre: "mes", error: "Ingrese un numero de mes correcto"});
		PorDefectoMensajes.push({nombre: "ano", error: "Ingrese un numero de año correcto"});
		PorDefectoMensajes.push({nombre: "mail", error: "Ingrese un mail correcto (ej.: ejemplo@ejemplo.com.ar)."});
		PorDefectoMensajes.push({nombre: "numero", error: "Tiene que ser un numero."});
		PorDefectoMensajes.push({nombre: "cadena", error: "Tiene que ser texto."});
		PorDefectoMensajes.push({nombre: "entero", error: "El dato ingresado es invalido."});
        PorDefectoMensajes.push({nombre: "MayorDeEdad", error: "Tiene que tener m�s de 18 a�os."});
        PorDefectoMensajes.push({nombre: "CodInt", error: "telincorrecto"});
        PorDefectoMensajes.push({nombre: "Telefono", error: "telincorrecto"});
	/**** FIN MENSAJES DE ERROR ****/

	var FunPrintError = "PrintErrorDF";
	/**** FUNCIONES GENERAL ****/
	/*
	 *	Esta funcion elimina el espacio en blanco que hay adelante o atras del string enviado.
	 *	
	 *	@parametro	STRING
	 *	@return		STRING
	*/
	function trimAll(strValue){
		
		var objRegExp = /^(\s*)$/
		
		//Elimino espacios en blanco
		if(objRegExp.test(strValue)) {
		   strValue = strValue.replace(objRegExp, '')
		   if( strValue.length == 0){
		      return strValue
		   }
		}
		
		objRegExp = /^(\s*)([\W\w]*)(\b\s*$)/
		if(objRegExp.test(strValue)) {
		   strValue = strValue.replace(objRegExp, '$2')
		}
		
		return strValue
	
	}
	
	/*
	 *	Esta funcion busca un dato dentro de un array
	 *	
	 *	@parametro	needle STRING/INT
	 *	@parametro	haystack ARRAY
	 *	@parametro	argStrict BOOL
     *
	 *	@return		BOOL
	*/
    function in_array (needle, haystack, argStrict) {
        var key = '', strict = !!argStrict; 
        if (strict) {
            for (key in haystack) {
                if (haystack[key] === needle) {
                    return true;            }
            }
        } else {
            for (key in haystack) {
                if (haystack[key] == needle) {                return true;
                }
            }
        }
         return false;
    }
/**** FIN FUNCIONES GENERAL ****/
/**** FUNCIONES VALIDA ****/
	var RegExpFecha = /^\d{1,2}(\-|\/|\.)\d{1,2}\1\d{4}$/
	var RegExpEntero = /(^-?\d\d*$)/

	function validaRQ (input, form){
		input.value = trimAll(input.value);
		Value = input.value;
		
		switch (input.type){
			case "checkbox":
            case "radio":
				
				if(input.checked != true){
					return false
				}
				
				break;
			default:
				
				if(Value.length < 1){
					return false;
				}
				
				break;
				
		}

		return true;
	}
	
	function validaMChoice (input, form, parametro){
		var CantOption;
		var GrupoNombre;
		var TypeInput;
		
		/**/
		if(parametro != undefined && parametro != ""){
			CantOption = parseFloat(parametro); 
		}else{
			CantOption = parseFloat("1");
		}
		
		if(input.name != undefined && input.name != ""){
			GrupoNombre = input.name;
		}else{
			return false;
		}
		
		TypeInput = input.type;
		/**/
				
		OpcionElegida = 0;
		InputBusqueda = document.getElementsByName(GrupoNombre);
		
		for (contAux=0;contAux<InputBusqueda.length;contAux++){

			if(InputBusqueda[contAux].disabled == false && InputBusqueda[contAux].type == TypeInput){
				
				Checked = InputBusqueda[contAux].checked;				
				if(Checked == true){
					OpcionElegida++;
				}
								
			}
			
		}
			
		if(OpcionElegida >= CantOption){
			return true;
		}else{
			return false;
		}
	}

	function validaReplica(input, form, parametro){
		var idComparar;
		var Value;
		/**/
		if(parametro == undefined || parametro == ""){
			return false;
		}
		
		idComparar = parametro;
		Value = input.value;
		/**/
		
		InputCompara = document.getElementById(idComparar);
		if(InputCompara.value == Value){
			return true;
		}
		
		return false;
	}

	function validaFecha(input, form){
		/**/

        var valorRetornado = false;
        
		var Value = input.value;
        var strFecha = Value;

		if(!RegExpFecha.test(Value)){		
			valorRetornado = false;		
		}else{
			var strSeparator = strFecha.substring(2,3) 
			var arrayDate = strFecha.split(strSeparator) 

			var arrayValidaMes = {  '01' : 31,'03' : 31, 
			                    	'04' : 30,'05' : 31,
			                    	'06' : 30,'07' : 31,
			                    	'08' : 31,'09' : 30,
			                    	'10' : 31,'11' : 30,
									'12' : 31}

			var intDia = parseInt(arrayDate[0],10) 			
			var intMes = parseInt(arrayDate[1],10)
			var intAno = parseInt(arrayDate[2])
            
			if(arrayValidaMes[arrayDate[1]] != null){
				if(intDia <= arrayValidaMes[arrayDate[1]] && intDia != 0){
			    	valorRetornado = true;
  				}
			}
            
			if (intMes == 2) {
   				if (intDia > 0 && intDia < 29) {
			    	valorRetornado = true;
			   	}else if (intDia == 29) {
			    	if ((intAno % 4 == 0) && (intAno % 100 != 0) || (intAno % 400 == 0)) {
			        	valorRetornado = true;
			     	}
			   	}
			}
		}  
            
		return valorRetornado;
	}
    
    function ValidarCodInt_Telefono(input, form){
         var codint = new Array(11,2345,2317,2225,2337,2478,2296,2281,291,2266,2292,2314,2342,3489,2226,2936,2395,2274,2357,2273,2352,2241,2346,2473,2223,2921,2922,2926,2265,2316,2924,2245,3488,2353,2243,2286,2267,2356,3388,2224,2202,2929,2933,2320,2229,2362,2264,221,2285,2244,2242,2355,2261,2227,3327,2358,2323,2221,2268,2257,223,2927,2324,220,2291,2271,237,2272,2262,2343,2284,2982,2928,2396,2477,2923,2322,2254,2932,3407,2297,2935,2475,2344,2393,2474,2325,2326,2252,3461,3329,2246,2293,2283,2392,2983,2394,2354,2255,2925,3487,3832,3833,3835,3837,3838,3721,3722,3725,3731,3732,3734,3735,2903,2945,2965,297,2336,3385,3387,3463,3467,3468,3472,351,3521,3522,3524,3525,353,3532,3533,3534,3541,3542,3543,3544,3546,3547,3548,3549,3562,3563,3564,3571,3572,3573,3574,3575,3576,358,3582,3583,3584,3585,3756,3772,3773,3774,3775,3777,3781,3782,3783,3786,343,3435,3436,3437,3438,3442,3444,3445,3446,3447,345,3454,3455,3456,3458,3711,3715,3716,3717,3718,388,3884,3885,3886,3887,2302,2331,2333,2334,2335,2338,2952,2953,2954,3821,3822,3825,3826,3827,261,2622,2623,2624,2625,2626,2627,3741,3743,3751,3752,3754,3755,3757,3758,2942,2948,2972,299,2920,2931,2934,2940,2941,2944,2946,3868,387,3875,3876,3877,3878,264,2646,2647,2648,2651,2652,2655,2656,2657,2658,2902,2962,2963,2966,3382,3400,3401,3402,3404,3405,3406,3408,3409,341,342,3460,3462,3464,3465,3466,3469,3471,3476,3482,3483,3491,3492,3493,3496,3497,3498,3841,3843,3844,3845,3846,385,3854,3855,3856,3857,3858,3861,2901,2964,381,3862,3863,3865,3867,3869,3891,3892,3894);
         var strCodInt =  input.value;

        if(in_array(strCodInt, codint, false)){
            return true;
        }else{
            return false;
        }
    }
    function ValidarTelefono(input, form){
        var strTelefono =  input.value;
        
        Resultado = true;
        
        arrTelefono = strTelefono.split("");
        cantNumeros = arrTelefono.length;
        
        if(parseInt(arrTelefono[0]) == 0){
            Resultado = false;
        }
        if(Resultado){
            var Repetidos = 0;
            var Consecutivos = 0;
            var NumeroComp = parseInt(arrTelefono[0]);
            var Opera = "";
            
            for(x=1; x < cantNumeros; x++){
                NumeroSel =  parseInt(arrTelefono[x]);
                
                if((Opera == "" || Opera == "i") && ((NumeroComp+1) == NumeroSel)){
                    Consecutivos++;                    
                    Opera = "i";
                }else{
                    if(Opera == "i"){
                        Consecutivos = 0;
                        Opera = "";
                    }
                }
                if((Opera == "" || Opera == "d") && ((NumeroComp-1) == NumeroSel)){
                    Consecutivos++;                    
                    Opera = "d";
                }else{
                    if(Opera == "d"){
                        Consecutivos = 0;
                        Opera = "";
                    }
                }
                if((Opera == "" || Opera == "m") && (NumeroComp == NumeroSel)){
                    Repetidos++;                    
                    Opera = "m";
                }else{
                    if(Opera == "m"){
                        Repetidos = 0;
                        Opera = "";
                    }
                }
                
                NumeroComp = NumeroSel;
            }
            
            if(Repetidos == (cantNumeros-1)){
                Resultado = false;    
            }
            if(Consecutivos == (cantNumeros-1)){
                Resultado = false;    
            }
        }
        
        if(Resultado){
            return true;
        }else{
            return false;
        }
    }
    
    function ValidarMayorDeEdad(input, form){
         var mayorde = 17;
         var strFecha =  input.value;

        if(mayorde > 0){
            var FechaHoy = new Date();

            var strSeparator = strFecha.substring(2,3) 
			var aFechaNac = strFecha.split(strSeparator) 

            var Edad = 0;

            Edad = (FechaHoy.getFullYear() - aFechaNac[2]) - 1; //-1 porque no se si ha cumplido a�os ya este a�o
            if(Edad < 0){
                valorRetornado = false;
            }
            
            if((FechaHoy.getMonth()+1) >= aFechaNac[1]){
                if((FechaHoy.getMonth()+1) == aFechaNac[1]){
                   if(FechaHoy.getDate() >= aFechaNac[0]){
                        Edad++;
                   } 
                }else{
                   Edad++; 
                }
            }
        }
        
        if(Edad > mayorde){
            return true;
        }else{
            return false;
        }
    }
	
	function validaDia(input, form){		
		var Value = input.value;
		
		if(!RegExpEntero.test(Value)){
			return false;
		}else{
			if(Value.length > 2 || Value < 1 || Value > 31){
				return false;
			}
		}
		
		return true;
	}
	
	function validaMes(input, form){		
		var Value = input.value;
		
		if(!RegExpEntero.test(Value)){
			return false;
		}else{
			if(Value.length > 2 || Value < 1 || Value > 12){
				return false;
			}
		}
		
		return true;
	}
	
	function validaAno(input, form){		
		var Value = input.value;
		
		if(!RegExpEntero.test(Value)){
			return false;
		}else{
			if(Value.length != 4){
				return false;
			}
		}
		
		return true;
	}
/**** END FUNCIONES ****/

function Validar(objForm){
		
	for (cont=0;cont<objForm.length;cont++){
		var PasarNoRequerido = false;
        
		ElementoForm = objForm.elements[cont];
		
		if(ElementoForm.disabled == false){
			Condiciones = ElementoForm.getAttribute("validar");
            
			if(Condiciones != null){
				
				arrCondiciones = Condiciones.split("::");
				
				for(idCondicion in arrCondiciones){
                                    /**/
                                    Condicion = arrCondiciones[idCondicion];

                                    if(trimAll(Condicion) != "" && (!Object.isFunction(Condicion))){
    					/**/
    					arrCondicion = Condicion.split("_");
                                        /**/
    					for(idConValida in Validador){
    						/**/
    						ConValida = Validador[idConValida];
    						/**/
    						if(arrCondicion[0] == ConValida.nombre){
                                                    if(ElementoForm.value == ""){
                                                        if(ConValida.vacio != true){
                                                            PasarNoRequerido = true;
                                                        }
                                                    }

                                                    if(!PasarNoRequerido){
        							if(ConValida.funcion != null){
                                                                    ExecFun = ConValida.funcion+"(ElementoForm, objForm";
                                                                    if(ConValida.parametros){
                                                                        strParametros = "";
                                                                            for(idparametro in arrCondicion){
                                                                            /**/
                                                                            parametro = arrCondicion[idparametro];
                                                                            /**/
                                                                                if(parametro != arrCondicion[0] && !Object.isFunction(parametro)){
                                                                                        if(strParametros != ""){
                                                                                                strParametros = strParametros+",";
                                                                                        }
                                                                                        strParametros = strParametros+parametro;
                                                                                }
                                                                            }
                                                                            ExecFun = ExecFun+",'"+strParametros+"'";
                                                                    }
                                                                    ExecFun = ExecFun+")";

                                                                    if(!eval(ExecFun)){
                                                                        eval(FunPrintError+'("'+ConValida.nombre+'", ElementoForm)');
                                                                        return false;
                                                                    }
        							}else if(ConValida.regexp != null){
                                                                    if(!ConValida.regexp.test(ElementoForm.value)){
                                                                            eval(FunPrintError+'("'+ConValida.nombre+'", ElementoForm)');
                                                                            return false;									
                                                                    }
        							}
                                                            }
                                                    }
                                            }
					}
				}
				
			}
		}
		
	}
	
	return true;
}

function PrintErrorDF(nombreError, Elemento){
	
	for (idMensaje = 0; idMensaje < MensajesError.length; idMensaje++ ){
		/**/
		Mensaje = MensajesError[idMensaje];
		/**/
		if(Mensaje.nombre == nombreError){
			window.alert(Mensaje.error);
			Elemento.focus();
			return true;
		}		
	}
	
	for (idMensaje = 0; idMensaje < MensajesError.length; idMensaje++ ){
		/**/
		Mensaje = MensajesError[idMensaje];
		/**/
		if(Mensaje.nombre == "pordefecto"){
			window.alert(Mensaje.error);
			Elemento.focus();
			return true;
		}		

        }

        return false;
}

function iniValidador(){
	for (idValoresValida in PorDefectoRegExp){
		/**/
		ValoresValida = PorDefectoRegExp[idValoresValida];
		/**/
		Validador.push(ValoresValida);       
	}

	for (idValoresValida in PorDefectoFuncion){
		/**/
		ValoresValida = PorDefectoFuncion[idValoresValida];
		/**/
		Validador.push(ValoresValida);	
	}

	for (idMensaje in PorDefectoMensajes){
		/**/
		Mensaje = PorDefectoMensajes[idMensaje];
		/**/
		MensajesError.push(Mensaje);
	}
}
iniValidador();