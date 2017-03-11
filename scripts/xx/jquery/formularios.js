$(document).ready(function() {
            
	//CAPTURA DE EVENTOS DE LOS FORMULARIOS
	$('form[name=FDatos]').live('submit',function (event){
	   if (event.keyCode==13){  return false;
            event.cancelBubble = true;
		    event.returnValue = false;
       }      	   	   
		$('input[type=submit]').attr('disabled','true');
		$.ajax({			
        	type: "POST",
	        url: $(this).attr('action'),    	    
			data:  $(this).serialize(),
            //dataType: "json",
			beforeSend: function() { 
            },
	        success: function(datos){ 
			   //alert(datos);
               //return false;
			   if (datos.error==1){	
					$("#InfoMensaje").html(datos.men);
					$( "#Modal" ).fadeIn(500);
			   }else if (datos.error==0)
			   {

					    $("#InfoMensaje").html(datos.men);				   					
						$( "#Modal" ).fadeIn(500).delay(1000).fadeOut(300,function (){
							$(this).delay(2000);							
							if ($("#idform").val()=='personasNI'){
						        $("#Integrante").append(FilaPersona(datos.cedula,datos.nombres,datos.correo,datos.celular,'CedInt'));
                                $("#PersonasInfoMensaje").empty();
                                $("#PersonasModal").fadeOut(200);
                                $("#InfoMensaje").html(""); 
							}
                            else if ($("#idform").val()=='personasNR')
							{
								$("#Representante").append(FilaPersona(datos.cedula,datos.nombres,datos.correo,datos.celular,'CedRep'));
                                $("#PersonasInfoMensaje").empty();
                                $("#PersonasModal" ).fadeOut(200);
                                $("#InfoMensaje").html("");                                                                
							}else {
							    //alert($("#npage").val());
							    window.location.href =($("#npage").val()); 
							}
						});					                 			   
			   }               
            },                                    
			error: function(valor1,valor2,valor3)
			{
			     AjaxError(valor2,"InfoMensaje");
                 $("#Modal" ).fadeIn(500);
			},
			complete: function(valor1,valor2){ $('input[type=submit]').removeAttr('disabled'); }
			
		});	
		return false;
							
	});


	$("#RN").live('click', function (event){
	    $("#PersonasInfoMensaje").load("personas.php?idform=NR #Formulario");				   					
		$( "#PersonasModal" ).fadeIn(500).delay(1000);	
	});
    
   	$("#IN").live('click', function (event){
	    $("#PersonasInfoMensaje").load("personas.php?idform=NI #Formulario");				   					
		$( "#PersonasModal" ).fadeIn(500).delay(1000);	
	});
    
    
//################################################################################################
//FUNCION PARA AGREGAR UN REPRESENTANTE CUANDO ESTE YA SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS
//################################################################################################
    
   	$("#RR").live('click', function (event){        
        var cedula=null;
        do {
            var cedula=prompt("Ingrese Numero de Cedula");
        }while ((cedula.length<6) || (cedula==null));
        
        if ($("div").find("[capa="+cedula+"]").length){
            alert("Ya Esta En Lista, No Puede Ser Agregado");
            return false;
        }
		
		var conta=0;
		$("input[id^=CedRep]").each(function() {
			conta=conta+1;
        });
		if (conta>=2){
			alert("Maximo 2 Representantes Por familia");
			return false;
		}
			
        $.ajax({			
        	type: "POST",
	        url: 'ProceForms.php',    	    
			data:  {idform: 'BuscarPersona',ced: cedula,familiaid:$("#id").val()},
            dataType: "json",
			beforeSend: function() { 
            },
	        success: function(datos){                 
			   if (datos.error==1){	
					$("#InfoMensaje").html(datos.men);
					$("#Modal" ).fadeIn(500);
			   }else if (datos.error==0)
			   {
    				$("#Representante").append(FilaPersona(datos.cedula,datos.nombres,datos.correo,datos.celular,'CedRep'));
                    $("#PersonasInfoMensaje").empty();
                    $( "#PersonasModal" ).fadeOut(200);                                
			   }               
            },                                    
			error: function(valor1,valor2,valor3)
			{
			     AjaxError(valor2,"InfoMensaje");
                 $("#Modal" ).fadeIn(500);
			},
			complete: function(valor1,valor2){ }			
		});                
	});
    
//#############################################################################################
//FUNCION PARA AGREGAR UN INTEGRANTE CUANDO ESTE YA SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS
//#############################################################################################
    
   	$("#IR").live('click', function (event){        
        var cedula=null;
        do {
            var cedula=prompt("Ingrese Numero de Cedula");
        }while ((cedula.length<6) || (cedula==null));
        
        if ($("div").find("[capa="+cedula+"]").length){
            alert("Ya Esta En Lista, No Puede Ser Agregado");
            return false;
        }
		
        $.ajax({			
        	type: "POST",
	        url: 'ProceForms.php',    	    
			data:  {idform: 'BuscarPersona',ced: cedula,familiaid:$("#id").val()},
            dataType: "json",
			beforeSend: function() { 
            },
	        success: function(datos){                 
			   if (datos.error==1){	
					$("#InfoMensaje").html(datos.men);
					$("#Modal" ).fadeIn(500);
			   }else if (datos.error==0)
			   {
    				$("#Integrante").append(FilaPersona(datos.cedula,datos.nombres,datos.correo,datos.celular,'CedInt'));
                    $("#PersonasInfoMensaje").empty();
                    $( "#PersonasModal" ).fadeOut(200);                                
			   }               
            },                                    
			error: function(valor1,valor2,valor3)
			{
			     AjaxError(valor2,"InfoMensaje");
                 $("#Modal" ).fadeIn(500);
			},
			complete: function(valor1,valor2){  }			
		});                
	});    


    $("#trepresenta").live('click',function (event){
        //alert("Hola Mundo");
        
    });


//################################################################
//FUNCION PARA BORRAR INFORMACION DE ALGUNA PERSONA EN LA PLANILLA
//################################################################
    $("#Borrar").live('click',function (event){
        var capab=$(this).attr("CedBorrar");
        $("div").remove("[capa="+capab+"]"); 
    });
   
    //CIERRA LA VENTANA DE OBSERVACIONES
	$("#Close").live('click', function (event){
		//alert("Ejecuto");
		$('#Modal').fadeOut(300,function (){
			$('#Modal').attr('style','display:none');
		});
	});
	//CIERRA LA VENTANA MODAL DE PERSONAS
	$("#PersonasClose").live('click', function (event){
	   $("#PersonasInfoMensaje").empty();
		$('#PersonasModal').fadeOut(300,function (){
			$('#PersonasModal').attr('style','display:none');
		});
	});

    
    $("input[pregunta=no]").live('click',function (event){
        //if 
        var pid =$(this).attr("idp");
		var rid =$(this).attr("value");
		var tip =$(this).attr("type");
		if (tip=="checkbox")
			var nameborrar=pid+"_"+rid;
		else
			var nameborrar=pid;
		if ($("#RPRE_"+nameborrar).length==1)
   	    	$("#RPRE_"+nameborrar).remove();    
	});

//    $("input:checked[pregunta=si]").live('click',function (event){
    $("input[pregunta=si]").live('click',function (event){

        var capa=$(this).parent();
        var rid =$(this).attr("value");
        var pid =$(this).attr("idp"); 
		var tip =$(this).attr("type");
		//alert("Tipo: ["+ tip+ "] PID: ["+ pid+"] RID: ["+rid+"]");  
		if (tip=="checkbox")
			var nameborrar=pid+"_"+rid;
		else
			var nameborrar=pid;      
		//alert(tip);
		
        if($(this).attr("checked")=="checked"){   
			//alert("#RPRE_"+nameborrar);
			if ($("#RPRE_"+nameborrar).length==1){			
				return false;                     
			}
            $.ajax({			
               type: "POST",
	           url: 'ProceForms.php',    	    
		  	   data:  {idform: 'BuscarSubPregunta',respuestap: rid,pregunta:pid,tipo:tip},
               dataType: "json",
			   beforeSend: function() { 
               },
	           success: function(datos){                 
			     if (datos.error==1){	
				    $("#InfoMensaje").html(datos.men);
					$("#Modal" ).fadeIn(500);
			     }else if (datos.error==0)
			     {
                    capa.append(datos.men);                                 
			     }               
              },                                    
			  error: function(valor1,valor2,valor3)
			  {
			     AjaxError(valor2,"InfoMensaje");
                 $("#Modal" ).fadeIn(500);
			  },
			  complete: function(valor1,valor2){  }			
		    });          
        }else{			
            if ($("#RPRE_"+nameborrar).length==1)
   	            $("#RPRE_"+nameborrar).remove(); 

        }      
    });
    
    $("input[name=items]").live('click',function (event){
        if ($(this).val()=="T"){
            $("<div id='Item' tipo='longitud'><div id='Etiqueta'>Longitud del Campo</div><div id='CampoInput'><input name='long' type='text' id='long' value='' size='5' maxlength='3' /> Max 150</div></div>").insertBefore("#Botones");                        
        }else{
            $('div[tipo=longitud]').remove();
        }
        //alert($(this).val());

    });

    
});

function AjaxError(valor,destino){
    switch(valor){
        case 'error':	$("#"+destino).html("Disculpe Destino No Encontrado");	break;
		case 'timeout':	$("#"+destino).html("Disculpe Se Excedio el Tiempo de Espera");	break;
		case 'notmodified':	$("#"+destino).html("Error, El Archivo de Destino No Se Puede Leer");	break;
		case 'parsererror':	$("#"+destino).html("Error, Retorno Invalido de Datos [XML/JSON]");	break;																				
    }
}

function inArray(arreglo,valor){
    var lon,i=0,enc=0;
    lon=arreglo.length;
    do{                
        if (arreglo[i]==valor)  enc=1;
        i++;        
    }while((enc==0) && (i<lon)); 
    if (enc==1)    return (i-1);
    else    return -1;
}

function FechaActual(){
    var fecha = new Date();
    var dia   = fecha.getDate();
    if (dia<10) dia="0"+dia;
    var mes   =fecha.getMonth() +1;
    if (mes<10) mes="0"+mes;
    var ano   =fecha.getFullYear();
    return dia+"/"+mes+"/"+ano;
}

function HoraActual(){
    var fecha = new Date();
    var hora   = fecha.getHours();
    if (hora<10) hora="0"+hora;
    var minuto   =fecha.getMinutes();
    if (minuto<10) minuto="0"+minuto;
    var segundo   =fecha.getSeconds();
    if (segundo<10) segundo="0"+segundo;
    return hora+":"+minuto+":"+segundo;
}
function Mensaje(tipo,men){
   $("#info").addClass(tipo);
   $("#info").html(men); 
}
function LimpiarMensaje(){
    $('#info').removeClass("error");
    $('#info').empty();
}
function FilaPersona(ced,nom,correo,cel,tipo){
    var Fila="<div id='FilaR' Capa='"+ced+"'>"+"<div id='Cedulas'>"+ced+" &nbsp; <input name='"+tipo+"[]' type='hidden' id='"+tipo+"[]' value='"+ced+"'/></div>"+"<div id='Nombres'>"+nom+"&nbsp;</div>"+"<div id='Correo' >"+correo+"&nbsp;</div>"+"<div id='Telefono'>"+cel+"&nbsp;</div><div id='BorrarFila'><a href='#' id='Borrar' CedBorrar='"+ced+"' >X</a></div>"+"</div>";
    
    return Fila;
}

