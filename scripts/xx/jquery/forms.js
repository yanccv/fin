$(document).ready(function() {
    
    //CAPTURA DE EVENTOS DE LOS FORMULARIOS
    $('#Cuerpo').on('submit','form[name=fdatos]',function (event){                
	   if (event.keyCode==13){  return false;
            event.cancelBubble = true;
		    event.returnValue = false;
        }
          	   	   	   
        $('input[type=submit]').attr('disabled','true');
        if ($("#idform").val()!="alumnos"){
  	       $.ajax({			
       	    type: "POST",
  	        url: $(this).attr('action'),    	    
  			data:  $(this).serialize(),
            dataType: "json",
  			beforeSend: function() { 
              },
  	        success: function(datos){ 
                 if (datos.error==1){	
                     Mensaje("error",datos.men)
  			   }else if (datos.error==0)
  			   {  			       
  				   $(".FormDatos").empty();
                   Mensaje("info",datos.men);
                   if ($("#idform").val()=="login")
  			           window.location="paginas/index.php";  			                                               
  			   }                    
              },                                    
  			error: function(valor1,valor2,valor3)
  			{			     
                   Mensaje("error",AjaxError(valor2));                 
  			},
  			complete: function(valor1,valor2){
                  
  			     $('input[type=submit]').removeAttr('disabled'); 
              }			
  		   });	                     
        }else{
      		
            if ($("#tipo").length>0){
                if ($("#tipo").val()=="masivo")
                    $("#idform").val("alumnosm");
            }
            var formData = new FormData($("#fdatos")[0]);
	       	$.ajax({
	       	    url: 'procesar.php',  
                type: 'POST',
			    data: formData,
                dataType: "json",
			    cache: false,
                contentType: false,
                processData: false,
			    success: function(datos){
	               if (datos.error==1){	
                        Mensaje("error",datos.men)
                    }else if (datos.error==0)
                    {  			       
                        $(".FormDatos").empty();
                        Mensaje("info",datos.men);
                        if ($("#idform").val()=="login")
	                       window.location="paginas/index.php";  			                                               
  			       }
			    },
			    error: function(){
				    alert("Error, No Se Cargo El Archivo");
                    return false;
                },
                complete: function(valor1,valor2){  $('input[type=submit]').removeAttr('disabled'); }
            });
        }
		return false;							
	});
	
	
	//BOTON DE AGREGAR AUTOR EN FORMULARIO DE LIBRO	
	$("#AAutor").on("click",function(){
        if ($("#autors").val().length==0){
            alert("Indique Nombre del Autor");
            return false;
        }else if ((($("#autors").val().split(' ').length-1))==$("#autors").val().length){
            alert("Indique Nombre del Autor");
            return false;
        }
        var autor = $("#autors").val();
        if($("input[type='text'][value='"+autor+"']").length>0){
            alert("Autor Ya Incluido");
            return false;
        }              		
		$(".Listados").append("<div id='Nombre' class='ItemAutor'><input type='text' readonly='true' id='Autores[]' name='Autores[]' value='"+autor+"'><span class='Eliminar'>X</span></div>").fadeIn(500);		
	});
    
    //Para Agregar Libro en Prestamo
  	$("#ALibro").on("click",function(){
  	     
  	    if ($("#ids").val().length==0){        
            alert("Indique Titulo del Libro");
            return false;
        }        
        if ($("#ejemplar").val().length==0){
            alert("Seleccione el Ejemplar a Prestar");
            return false;
        }
        
        var id    = $("#ids").val();
        var libro = $("#titulos").val();
        var ejemplar = $("#ejemplar").val();        
        //alert(ejemplar);
        if($("input[name^=Libro][type='hidden'][value='"+id+"']").length>0){
            alert("Libro Ya Incluido");
            return false;
        }
        if ($(".ItemAutor").length>=$("#disponible").val()){
            alert("No Puede Prestar Mas Libros");
            return false;
        }
        //alert($(".ItemAutor").length);
        $("#titulos").val("");
        $("#ids").val("");
        $("#ejemplar").html("<option>Seleccione</option>");
		$(".Listados").append("<div id='Nombre' class='ItemAutor'><input type='hidden' readonly='true' id='Libros[]' name='Libros[]' value='"+id+"'><input type='hidden' readonly='true' id='Ejemplar[]' name='Ejemplar[]' value='"+ejemplar+"'>"+libro+"<span class='Eliminar'>X</span></div>").fadeIn(500);		
	});

	//Eliminar de los Listados
	$(".Listados").on("click",".Eliminar",function(){
		$(this).parent().remove();
		//alert("Hola Mundo");
	});
    
	$(".Listados").on("click",".Entregar",function(){
		$(this).parent().remove();
		//alert("Hola Mundo");
	});


    //Buscar Si el Alumno Esta Solvente
    $("#DivCedula").on("keyup click","#Cedula",function(){
        if ($("#Cedula").val().length>=7){
            var ftipo=$("#idform").val();
            //alert(ftipo);
            $.ajax({			
                type: "POST",
                url: "procesar.php",    	    
                data:  {idform: "BAlumno",cedula:$("#Cedula").val()},
                dataType: "json",
                beforeSend: function() { },
                success: function(datos){ 
                    if (datos.error==1){	
                        Mensaje("error",datos.men);
                        $("#NombreAlumno").val('');
                    }else if (datos.error==0)
  			        {  			           
                        $("#NombreAlumno").val(datos.nombre);
                        $("#disponible").val(datos.disponible);
                        LimpiarMensaje();                                                
                    }                    
                },                                    
                error: function(valor1,valor2,valor3){  Mensaje("error",AjaxError(valor2)); },
                complete: function(valor1,valor2){   }			
  		    });                        
        }else{
            LimpiarMensaje();
        }
	});
    
    
    $("#DivCedula").on("keyup click","#CedPres",function(){
        if ($("#CedPres").val().length>=7){
            $.ajax({			
                type: "POST",
                url: "procesar.php",    	    
                data:  {idform: "BPrestamos",cedula:$("#CedPres").val()},
                dataType: "json",
                beforeSend: function() { },
                success: function(datos){ 
                    if (datos.error==1){	
                        Mensaje("error",datos.men);
                        $("#NombreAlumno").val('');
                    }else if (datos.error==0)
  			        {
                        $("#NombreAlumno").val(datos.nombre);
                        if ($("#Prestamos").length>0)
                            $("#Prestamos").html(datos.prestamos);
                        LimpiarMensaje()                                                
                    }                    
                },                                    
                error: function(valor1,valor2,valor3){  Mensaje("error",AjaxError(valor2)); },
                complete: function(valor1,valor2){   }			
  		    });                        
        }
	});

    $("div").on("change","#Prestamos",function(){
        if ($("#Prestamos").val()!=''){                        
            //if (("#P"+$("#Prestamos").val()).length==0){                                            
            $("#Prestamos").attr("disabled","true");
            //alert($("#Prestamos").val());
            $.ajax({			
                type: "POST",
                url: "procesar.php",    	    
                data:  {idform: "BPrestamo",cedula:$("#CedPres").val(),prestamo:$("#Prestamos").val()},
                dataType: "json",
                beforeSend: function() { },
                success: function(datos){ 
                    $("#divprestamos").html(datos.prestamo);
                },                                    
                error: function(valor1,valor2,valor3){  Mensaje("error",AjaxError(valor2)); },
                complete: function(valor1,valor2){  $("#Prestamos").removeAttr("disabled");   }			
  		    });
            //}                                
        }else{
            $("#divprestamos").html("");
        }
	}); 
    
    $("#divprestamos").on("click",".Entregar",function(){
        var idlib=$(this).attr("ref");
        $.ajax({			
                type: "POST",
                url: "procesar.php",    	    
                data:  {idform: "ELibro",prestamo:$("#Prestamos").val(),libro:$(this).attr("ref")},
                dataType: "json",
                beforeSend: function() { },
                success: function(datos){ 
                    if (datos.error==1){	
                        Mensaje("error",datos.men);                        
                    }else if (datos.error==0)
  			        {                        
                        Mensaje("info",datos.men);  
                        $("span[ref="+idlib+"]").remove();
                    }                    
                },                                    
                error: function(valor1,valor2,valor3){  Mensaje("error",AjaxError(valor2)); },
                complete: function(valor1,valor2){  $("#Prestamos").removeAttr("disabled");   }			
        });
    });                    
});

function AjaxError(valor){
    switch(valor){
        case 'error':	return "Disculpe Destino No Encontrado";	break;
		case 'timeout':	return "Disculpe Se Excedio el Tiempo de Espera";	break;
		case 'notmodified':	return "Error, El Archivo de Destino No Se Puede Leer";	break;
		case 'parsererror':	return "Error, Retorno Invalido de Datos [XML/JSON]";	break;																				
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

