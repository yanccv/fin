$(document).ready(function() {
	var destino="../listado/procelis.php"

//	##########################################################
//		 EVENTO SUBMIT PARA BOTON DE BUSCAR EN LOS LISTADOS
//	##########################################################
	$('#fBuscar').live('submit',function (event){
	    var reg=$("#nro").val();
		$.ajax({
        	type: "POST",
	        url: destino,
			data:  $(this).serialize()+"&fid="+$("#fid").val()+"&nro="+$("#nro").val(),
			beforeSend: function( ) {  },
	        success: function(datos){  $("#listado").html(datos);  $("#nro").val(reg); },
			error: function(valor1,valor2,valor3)
			{
				switch(valor2){
					case 'error':	alert( "Disculpe Destino No Encontrado");	break;
					case 'timeout':	alert( "Disculpe Se Excedio el Tiempo de Espera");	break;
					case 'notmodified':	alert("Error, El Archivo de Destino No Se Puede Leer");	break;
					case 'parsererror':	alert("Error, Retorno Invalido de Datos [XML/JSON]");	break;
				}
			}
		});
		return false;
	});

//	##########################################################
//		 EVENTO CLICK PARA BOTON DE MOSTRAR TODO EN LOS LISTADOS
//	##########################################################

    $('#fTodo').live('click',function (event){
		$.ajax({
        	type: "POST",
	        url: destino,
			data:  "fid="+$("#fid").val()+"&nro=30",
			beforeSend: function( ) {  },
	        success: function(datos){
                $("#listado").html(datos);
                $("#nro").val(30);
                $("#valor").val("");
            },
			error: function(valor1,valor2,valor3)
			{
				switch(valor2){
					case 'error':	alert( "Disculpe Destino No Encontrado");	break;
					case 'timeout':	alert( "Disculpe Se Excedio el Tiempo de Espera");	break;
					case 'notmodified':	alert("Error, El Archivo de Destino No Se Puede Leer");	break;
					case 'parsererror':	alert("Error, Retorno Invalido de Datos [XML/JSON]");	break;
				}
			}
		});
		return false;
	});

//	##########################################################
//		 EVENTO CLICK PARA BOTON DE  EN LOS LISTADOS
//	##########################################################
/*
    $('#fActLis').live('click',function (event){
		$.ajax({
        	type: "POST",
	        url: "procelis.php",
			data:  "fid="+$("#fid").val()+"&campo="+$("#campo").val()+"&valor="+$("#valor").val()+"&ordenar="+$("#orden").val(),
			beforeSend: function( ) {  },
	        success: function(datos){  $("#listado").html(datos);    },
			error: function(valor1,valor2,valor3)
			{
				switch(valor2){
					case 'error':	alert( "Disculpe Destino No Encontrado");	break;
					case 'timeout':	alert( "Disculpe Se Excedio el Tiempo de Espera");	break;
					case 'notmodified':	alert("Error, El Archivo de Destino No Se Puede Leer");	break;
					case 'parsererror':	alert("Error, Retorno Invalido de Datos [XML/JSON]");	break;
				}
			}
		});
		return false;
	});
*/
//	################################################################
//		 EVENTO CLICK PARA LAS FLECHAS DE NAVEGACION EN LOS LISTADOS
//	################################################################
    $('#paginas a').live('click',function (){
        var reg=$("#nro").val();
        var pag=$(this).attr("page");
        $.ajax({
        	type: "POST",
	        url: destino,
			data:  "fid="+$("#fid").val()+"&nro="+$("#nro").val()+"&campotb="+$("#campotb").val()+"&valor="+$("#valor").val()+"&ordenar="+$("#orden").val()+"&pages="+pag,
			beforeSend: function( ) {  },
	        success: function(datos){  $("#listado").html(datos);  $("#nro").val(reg);  },
			error: function(valor1,valor2,valor3)
			{
				switch(valor2){
					case 'error':	alert( "Disculpe Destino No Encontrado");	break;
					case 'timeout':	alert( "Disculpe Se Excedio el Tiempo de Espera");	break;
					case 'notmodified':	alert("Error, El Archivo de Destino No Se Puede Leer");	break;
					case 'parsererror':	alert("Error, Retorno Invalido de Datos [XML/JSON]");	break;
				}
			}
		});
		return false;
	});

//	################################################################
//		 EVENTO KEYUP PARA LOS TEXT DE NROPAG Y NRO  EN LOS LISTADOS
//	################################################################
    $('#nropag,#nro').live('keyup',function (event){

        if (event.keyCode==13){
            var reg=$("#nro").val();
            var pag=$("#nropag").val();
			//alert("Registros: "+reg+" Pagina:" + pag);
            $.ajax({
        	   type: "POST",
	           url: destino,
               data:  "fid="+$("#fid").val()+"&nro="+reg+"&campotb="+$("#campotb").val()+"&valor="+$("#valor").val()+"&ordenar="+$("#orden").val()+"&pages="+pag,
			   beforeSend: function( ) { $("#listado").html("<center>Procesando Informacion</center>"); },
	           success: function(datos){  $("#listado").html(datos);  $("#nro").val(reg); },
	           error: function(valor1,valor2,valor3)
	           {
				switch(valor2){
					case 'error':	alert( "Disculpe Destino No Encontrado");	break;
					case 'timeout':	alert( "Disculpe Se Excedio el Tiempo de Espera");	break;
					case 'notmodified':	alert("Error, El Archivo de Destino No Se Puede Leer");	break;
					case 'parsererror':	alert("Error, Retorno Invalido de Datos [XML/JSON]");	break;
				}
			   }
            });
            return false;
        }
	});
});

//	################################################################
//		 FUNCION ENCARGADA DE ORDENAR EL LISTADO DE ACUERDO A CAMPO
//	################################################################
function ordenar(orden){
	var destino="../paginas/procelis.php"
    var reg=$("#nro").val();
    $.ajax({
        	type: "POST",
	        url: destino,
			data:  "fid="+$("#fid").val()+"&nro="+$("#nro").val()+"&campotb="+$("#campotb").val()+"&valor="+$("#valor").val()+"&ordenar="+orden+"&pages=1",
			beforeSend: function( ) {  },
	        success: function(datos){  $("#listado").html(datos);  $("#nro").val(reg); $("#orden").val(orden); },
			error: function(valor1,valor2,valor3)
			{
				switch(valor2){
					case 'error':	alert( "Disculpe Destino No Encontrado");	break;
					case 'timeout':	alert( "Disculpe Se Excedio el Tiempo de Espera");	break;
					case 'notmodified':	alert("Error, El Archivo de Destino No Se Puede Leer");	break;
					case 'parsererror':	alert("Error, Retorno Invalido de Datos [XML/JSON]");	break;
				}
			}
    });
	return false;
}

function LoadEditar(archivo,params){
	var url="../administrador/";
    var form = url+archivo+".php?"+params;
	$(location).attr('href',form);
    //alert(form);
	/*
	$("#Centro").load( form, function(response, status, xhr) {
  	    if (status == "error") {  var msg = "Disculpe Ocurrio Un Error : ";	  $("#Centro").html(msg + xhr.status + " " + xhr.statusText + " "+ form); }
        $("#Centro").fadeIn();
    });
	*/
}
function LoadEditarCliente(archivo,params){
	var url="../paginas/";
    var form = url+archivo+".php?"+params;
	$(location).attr('href',form);
    //alert(form);
	/*
	$("#Centro").load( form, function(response, status, xhr) {
  	    if (status == "error") {  var msg = "Disculpe Ocurrio Un Error : ";	  $("#Centro").html(msg + xhr.status + " " + xhr.statusText + " "+ form); }
        $("#Centro").fadeIn();
    });
	*/
}

//	###########################################################################
//		 EVENTO CLICK PARA EDITAR LA INFORMACION DE UN REGISTRO EN LOS LISTADOS
//	###########################################################################
function editar(orden){
    alert(orden);
    return;
    var reg=$("#nro").val();
    $.ajax({
        	type: "POST",
	        url: destino,
			data:  "fid="+$("#fid").val()+"&nro="+$("#nro").val()+"&campotb="+$("#campotb").val()+"&valor="+$("#valor").val()+"&ordenar="+orden+"&pages=1",
			beforeSend: function( ) {  },
	        success: function(datos){  $("#listado").html(datos);  $("#nro").val(reg); $("#orden").val(orden); },
			error: function(valor1,valor2,valor3)
			{
				switch(valor2){
					case 'error':	alert( "Disculpe Destino No Encontrado");	break;
					case 'timeout':	alert( "Disculpe Se Excedio el Tiempo de Espera");	break;
					case 'notmodified':	alert("Error, El Archivo de Destino No Se Puede Leer");	break;
					case 'parsererror':	alert("Error, Retorno Invalido de Datos [XML/JSON]");	break;
				}
			}
    });
	return false;
}

//	###########################################################################
//		 FUNCION ENCARGADA DE CARGAR LOS FORMULARIOS PARA MODIFICARLOS
//	###########################################################################
function cargarSeccion(tipo,archivo,params){
    var ruta ="../forms/";
	var form=ruta+archivo+"?"+params;
	window.redirect(ruta);
    /*
	if ($(this).attr('id')=='listado'){
        $("#CapaForms").hide();
	}
    */
    //$("#Nuevo").attr("link",archivo);
    /*
		$("#Centro").load( form, function(response, status, xhr) {
  		    if (status == "error") {  var msg = "Disculpe Ocurrio Un Error : ";	  $("#CapaForms").html(msg + xhr.status + " " + xhr.statusText + " "+ form); }
            $("#CapaForms").fadeIn();
        });
	*/
}

function borrarRegistro(tipo,campo,tabla,valor){
    var destino = "../listado/deletelis.php";
    $.ajax({
        	type: "POST",
	        url: destino,
			data:  "fid=Borrado&tabla="+tabla+"&campo="+campo+"&valor="+valor,
            dataType: "json",
			beforeSend: function( ) {  },
	        success: function(datos){
	           if (datos.success){
	                var tablac =document.getElementById(tipo); //Obtengo la Tabla
	                var fila =document.getElementById(valor);     //Obtengo la Fila de la Tabla
                    fila.parentNode.removeChild(fila);   //Remuevo la Fila Del REgistro Seleccionado
	           }
               console.log(datos)
               alert(datos.msg);
            },
			error: function(valor1,valor2,valor3)
			{
				switch(valor2){
					case 'error':	alert( "Disculpe Destino No Encontrado");	break;
					case 'timeout':	alert( "Disculpe Se Excedio el Tiempo de Espera");	break;
					case 'notmodified':	alert("Error, El Archivo de Destino No Se Puede Leer");	break;
					case 'parsererror':	alert("Error, Retorno Invalido de Datos [XML/JSON]");	break;
				}
			}
    });
	return false;
}

function Autorizar(tipo,campo,tabla,valor){
    alert(tipo+' '+ campo+''+tabla+' '+valor);
    $.ajax({
        	type: "POST",
	        url: "../paginas/procesar.php",
			data:  "fid=Autorizar&id="+valor,
            dataType: "json",
			beforeSend: function( ) {  },
	        success: function(datos){
	           if (datos.error==0){
	               //alert(tipo);
	                var tablac =document.getElementById(tipo); //Obtengo la Tabla
	                var fila =document.getElementById(valor);     //Obtengo la Fila de la Tabla
                    fila.parentNode.removeChild(fila);   //Remuevo la Fila Del REgistro Seleccionado
                    alert(datos.men);
	           }else
               {
                    alert(datos.men);
               }
            },
			error: function(valor1,valor2,valor3)
			{
				switch(valor2){
					case 'error':	alert( "Disculpe Destino No Encontrado");	break;
					case 'timeout':	alert( "Disculpe Se Excedio el Tiempo de Espera");	break;
					case 'notmodified':	alert("Error, El Archivo de Destino No Se Puede Leer");	break;
					case 'parsererror':	alert("Error, Retorno Invalido de Datos [XML/JSON]");	break;
				}
			}
    });
	return false;
}

function CambiaDoc(tipo,cambio,valor,id){
    alert('['+tipo+' ['+ cambio+'] ['+valor+'] ['+id+']');
    $.ajax({
        	type: "POST",
	        url: "../paginas/procesar.php",
			data:  "fid="+cambio+"&valor="+valor+"&id="+id,
            dataType: "json",
			beforeSend: function( ) {  },
	        success: function(datos){
	           if (datos.error==0){
	               //alert(tipo);
	                var tablac =document.getElementById(tipo); //Obtengo la Tabla
	                var fila =document.getElementById(id);     //Obtengo la Fila de la Tabla
                    fila.parentNode.removeChild(fila);   //Remuevo la Fila Del REgistro Seleccionado
                    alert(datos.men);
	           }else
               {
                    alert(datos.men);
               }
            },
			error: function(valor1,valor2,valor3)
			{
				switch(valor2){
					case 'error':	alert( "Disculpe Destino No Encontrado");	break;
					case 'timeout':	alert( "Disculpe Se Excedio el Tiempo de Espera");	break;
					case 'notmodified':	alert("Error, El Archivo de Destino No Se Puede Leer");	break;
					case 'parsererror':	alert("Error, Retorno Invalido de Datos [XML/JSON]");	break;
				}
			}
    });
	return false;
}

function Desautorizar(tipo,campo,tabla,valor){
    $.ajax({


        	type: "POST",
	        url: "../paginas/procesar.php",
			data:  "fid=Desautorizar&id="+valor,
            dataType: "json",
			beforeSend: function( ) {  },
	        success: function(datos){
	           if (datos.error==0){
	               //alert(tipo);
	                var tablac =document.getElementById(tipo); //Obtengo la Tabla
	                var fila =document.getElementById(valor);     //Obtengo la Fila de la Tabla
                    fila.parentNode.removeChild(fila);   //Remuevo la Fila Del REgistro Seleccionado
                    alert(datos.men);
	           }else
               {
                    alert(datos.men);
               }
            },
			error: function(valor1,valor2,valor3)
			{
				switch(valor2){
					case 'error':	alert( "Disculpe Destino No Encontrado");	break;
					case 'timeout':	alert( "Disculpe Se Excedio el Tiempo de Espera");	break;
					case 'notmodified':	alert("Error, El Archivo de Destino No Se Puede Leer");	break;
					case 'parsererror':	alert("Error, Retorno Invalido de Datos [XML/JSON]");	break;
				}
			}
    });
	return false;
}

function Enviar(tipo,campo,tabla,valor){
    $.ajax({


        	type: "POST",
	        url: "../paginas/procesar.php",
			data:  "fid=Desautorizar&id="+valor,
            dataType: "json",
			beforeSend: function( ) {  },
	        success: function(datos){
	           if (datos.error==0){
	               //alert(tipo);
	                var tablac =document.getElementById(tipo); //Obtengo la Tabla
	                var fila =document.getElementById(valor);     //Obtengo la Fila de la Tabla
                    fila.parentNode.removeChild(fila);   //Remuevo la Fila Del REgistro Seleccionado
                    alert(datos.men);
	           }else
               {
                    alert(datos.men);
               }
            },
			error: function(valor1,valor2,valor3)
			{
				switch(valor2){
					case 'error':	alert( "Disculpe Destino No Encontrado");	break;
					case 'timeout':	alert( "Disculpe Se Excedio el Tiempo de Espera");	break;
					case 'notmodified':	alert("Error, El Archivo de Destino No Se Puede Leer");	break;
					case 'parsererror':	alert("Error, Retorno Invalido de Datos [XML/JSON]");	break;
				}
			}
    });
	return false;
}
