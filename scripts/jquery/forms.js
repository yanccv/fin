$(function() {
    var dialog,titulo;
    if ($( "#dialog-form" ).length>0) {
        var dialog = $( "#dialog-form" ).dialog({
          autoOpen: false,
          height: 500,
          width: 880,
          modal: true,
          draggable: false,
          buttons: {
            Cerrar: function() {
              dialog.dialog( "close" );
            }
          }
        });
    }

    $(".show-details" ).on( "click", function() {
        var idcliente=$(this).attr('rel');
        titulo='Clientes Asociados Por '+$(this).attr('names');
        $.ajax({
            type: "POST",
            url: 'procesar.php',
            data:  {cliente:idcliente, idform: 'VerHijos'},
            dataType: "json",
            beforeSend: function() {},
            success: function(datos){
                if (datos.error==1){
                    alert('Error No Se Obtuvo la Informacion');
                }else{
                    $("#Childs").html(datos.men);
                    dialog.dialog({title:titulo});
                    dialog.dialog( "open");

                }
            },
            error: function(valor1,valor2,valor3){  Mensaje("error",AjaxError(valor2));},
            complete: function(valor1,valor2){      }
  		});
    });
});

$(document).ready(function() {
    var dialog;

    //CAPTURA DE EVENTOS DE LOS FORMULARIOS
    $('#Cuerpo').on('submit','form[name=fdatos]',function (event){
	   if (event.keyCode==13){  return false;
            event.cancelBubble = true;
		    event.returnValue = false;
        }
        console.debug($( this ).serialize());
        $("input[type=submit]").attr('disabled','true');
        //$('input[type=submit]').attr('disabled','disabled');
        if ($("#idform").val()!="bannareas"){
  	       $.ajax({
       	    type: "POST",
  	        url: $(this).attr('action'),
  			data:  $(this).serializeArray(),
            dataType: "json",
  			beforeSend: function() {
              },
  	        success: function(datos){
               if (datos.error==1){
                     Mensaje("error",datos.men)
  			   }else if (datos.error==0)
  			   {
  				   $("#fdatos").empty();
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
                 if ($("#idform").val()!="Liquidez")
  			       $('input[type=submit]').removeAttr('disabled');
              }
  		   });
        }else{
            var formData = new FormData($("#fdatos")[0]);
	       	$.ajax({
	       	    url:  $(this).attr('action'),
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

//Accion de Quitar Clientes Inactivos de la Red de la Franquicia
   $(".QuitarInactivo").on( "click", function() {
      var idcliente=$(this).attr('rel');
      var res=confirm('Desea Retirar Este Cliente de la Red');
      if (res){
         $.ajax({
            type: "POST",
            url: 'procesar.php',
            data:  {cliente:idcliente, idform: 'RetirarAsociado'},
            dataType: "json",
            beforeSend: function() {},
            success: function(datos){
               if (datos.error==1){
                 alert(datos.men);
               }else{
                  alert(datos.men);
                  $('#'+idcliente).remove();
               }
            },
            error: function(valor1,valor2,valor3){  Mensaje("error",AjaxError(valor2));},
            complete: function(valor1,valor2){      }
         });
      }
      return false;
   });

   //Accion de Quitar Clientes Inactivos de la Red de la Franquicia
      $(".EliminarInactivo").on( "click", function() {
         var idcliente=$(this).attr('rel');
         var res=confirm('Desea Retirar Este Cliente de la Red');
         if (res){
            $.ajax({
               type: "POST",
               url: 'procesar.php',
               data:  {cliente:idcliente, idform: 'EliminarAsociado'},
               dataType: "json",
               beforeSend: function() {},
               success: function(datos){
                  if (datos.error==1){
                    alert(datos.men);
                  }else{
                     alert(datos.men);
                     $('#'+idcliente).remove();
                  }
               },
               error: function(valor1,valor2,valor3){  Mensaje("error",AjaxError(valor2));},
               complete: function(valor1,valor2){      }
            });
         }
         return false;
      });

   //Logueo de Usuario Administrador
    //CAPTURA DE EVENTOS DE LOS FORMULARIOS
    $('#FormLogin').on('submit','form[name=Logueo]',function (event){
      $.ajax({
         type: "POST",
         url: $(this).attr('action'),
  			data:  $(this).serialize(),
         dataType: "json",
  			beforeSend: function() {   },
         success: function(datos){
            if (datos.error==1){
               Mensaje("error",datos.men)
  			   }else if (datos.error==0)
  			   {
               window.location="panel.php";
  			   }
        },
	     error: function(valor1,valor2,valor3)
	     {
            Mensaje("error",AjaxError(valor2));
        },
  		  complete: function(valor1,valor2){
            if ($("#idform").val()!="Liquidez")
               $('input[type=submit]').removeAttr('disabled');
        }
	   });
      return false;
	});

    //CAPTURA DE CLICK EN ADD BAREMOS
    $('#AddBaremo').on('click',function (event){
         var html="<div class='CampoCompleto'>"+
            	"<div class='Etiqueta'><input type='hidden' id='idn[]' name='idn[]' value='' />Monto:</div>"+
                "<div class='CampoCorto'><input type='text' id='monton[]' name='monton[]' size='5' maxlength='7' value='' /></div>"+
            	 "<div class='Etiqueta'>% de Participación:</div>"+
                "<div class='CampoCorto'><input type='text' id='porcen[]' name='porcen[]' size='5' maxlength='5' value='' /></div>"+
                "<div class='EtiquetaCorta'><a class='DelBaremo' href='#'>Eliminar</a></div>"+
            	 "<div class='Limpiador'></div>"+
            "</div>";
      $(html).insertBefore("#fdatos > .FormFin");
	});

   $('.DelBaremo').on('click',function (event){
         $(this).parent().parent().remove();
         //return true;
	});
   $('#Cuerpo').on('click','.DelBaremo',function (event){
         $(this).parent().parent().remove();
         return true;
	});


    $('#Cuerpo').on('submit','form[name=ActClasificado]',function (event){
        //alert("Hola Act Clasificado");

        $('input[type=submit]').attr('disabled','true');
        var formulario=$(this).parent().attr("id");

        var formData = new FormData($("#ActClasificado")[0]);
       	$.ajax({
   	        url:  $(this).attr('action'),
            type: 'POST',
            data: formData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function(datos){
                if (datos.error==1){

                    Mensaje("error",datos.men,formulario)
                }else if (datos.error==0)
                {
                    $("#ActClasificado").empty();
                    Mensaje("info",datos.men,formulario)
                }
            },
            error: function(){
                alert("Error, No Se Cargo El Archivo");
                return false;
            },
            complete: function(valor1,valor2){  $('input[type=submit]').removeAttr('disabled'); }
        });
		return false;
	});

    $('#Cuerpo').on('submit','form[name=FBanner]',function (event){
/*
        alert("Hola Banner Clasificado");
        return false;
*/
        $('input[type=submit]').attr('disabled','true');
        var formulario=$(this).parent().attr("id");

        var formData = new FormData($("#FBanner")[0]);
       	$.ajax({
   	      url:  $(this).attr('action'),
            type: 'POST',
            data: formData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function(datos){
                if (datos.error==1){

                    Mensaje("error",datos.men,formulario)
                }else if (datos.error==0)
                {
                    $("#FBanner").empty();
                    Mensaje("info",datos.men,formulario)
                }
            },
            error: function(){
                alert("Error, No Se Cargo El Archivo");
                return false;
            },
            complete: function(valor1,valor2){  $('input[type=submit]').removeAttr('disabled'); }
        });
		return false;
	});

    $('#Cuerpo').on('submit','form[name=ActBanner]',function (event){
        //alert("Hola Act Clasificado");

        $('input[type=submit]').attr('disabled','true');
        var formulario=$(this).parent().attr("id");

        var formData = new FormData($("#ActBanner")[0]);
       	$.ajax({
   	        url:  $(this).attr('action'),
            type: 'POST',
            data: formData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function(datos){
                if (datos.error==1){

                    Mensaje("error",datos.men,formulario)
                }else if (datos.error==0)
                {
                    $("#ActBanner").empty();
                    Mensaje("info",datos.men,formulario)
                }
            },
            error: function(){
                alert("Error, No Se Cargo El Archivo");
                return false;
            },
            complete: function(valor1,valor2){  $('input[type=submit]').removeAttr('disabled'); }
        });
		return false;
	});



	//BOTON DE AGREGAR BOTON PARA CARGAR UNA IMAGEN MAS EN FORMULARIO DE AREAS
	$("#BAddImg").on("click",function(){
		var newimg='<div class="CampoCompleto"><div class="EtiquetaLarga">Imagen de Banner: </div><div class="CampoLargo"><input name="img[]" type="file" id="img[]"  size="20" maxlength="150" /></div><a href="#" class="DelButton">Eliminar</a><div class="Limpiador"></div></div>';
		$(newimg).insertBefore(".FormFin");
	});

	$("#Cuerpo").on("click",".DelButton",function(){
	   $(this).parent().remove();
	   //alert("Hola Mundo");
	});

    $("#Cuerpo").on("click","input[type=radio][value=B]",function(){
       $("#ListadoBanners").css("display","block");
       $("#ListadoBanners").css("visibility","visible");
       $("#ClasificadoFoto").css("display","none");
       $("#ClasificadoFoto").css("visibility","hidden");
    });
    $("#Cuerpo").on("click","input[type=radio][value=C]",function(){
       $("#ListadoBanners").css("display","none");
       $("#ListadoBanners").css("visibility","hidden");
       $("#ClasificadoFoto").css("display","block");
       $("#ClasificadoFoto").css("visibility","visible");

    });

   //Carga Los Banner Disponibles para el Area Seleccionada
    $("#CArea").on("change",function (){
        //alert("Hola Mundo");
      if ($(this).val()==0)
         return false;

      $.ajax({
         url:  "procesar.php",
         type: 'POST',
         data: {idform: "BAreas",idarea:$(this).val()},
         dataType: "json",
         success: function(datos){
            if (datos.error==0){
               $("#CBanner").html(datos.men);
            }else if (datos.error==1){
               Mensaje("error",datos.men)
            }

         },
         error: function(valor1,valor2,valor3){
            Mensaje("error",AjaxError(valor2));
         },
         complete: function(valor1,valor2){
            $('input[type=submit]').removeAttr('disabled');
         }
      });
    });

    //Muestra la informacion y estructura de cada banner
    $("#CBanner").on("change",function (){
      if ($(this).val()==0)
         return false;

      $.ajax({
         url:  "procesar.php",
         type: 'POST',
         data: {idform: "BDBanner",idbanner:$(this).val(),idpais:$("#idpais").val()    },
         dataType: "json",
         success: function(datos){
            if (datos.error==0){
               $("#CPlanBanner").html(datos.men);
               $("#Dimenciones").html(datos.dimenciones);
               $("#ancho").val(datos.ancho);
               $("#alto").val(datos.alto);
            }else if (datos.error==1){
               Mensaje("error",datos.men)
            }

         },
         error: function(valor1,valor2,valor3){
            Mensaje("error",AjaxError(valor2));
         },
         complete: function(valor1,valor2){
            $('input[type=submit]').removeAttr('disabled');
         }
      });
    });
    $("#Cuerpo").on("click","#DelImg",function (){
        var res=confirm("Confirma Que Desea Elinar Esta Imagen");
        var img=$(this).attr("rel");
        if (res){
       	    $.ajax({
	       	    url:  "procesar.php",
                type: 'POST',
			    data: {idform: "borrarimg",area:$("#id").val(), imagen:img},
                dataType: "json",
			    success: function(datos){
			     alert(datos.men);
			    },
                error: function(valor1,valor2,valor3){  Mensaje("error",AjaxError(valor2)); },
                complete: function(valor1,valor2){
                    //$(this).parent().parent().remove();
                }
            });
            $(this).parent().parent().parent().remove();
        }
    });

    $("#Cuerpo").on("change","#BArea",function (){
       	    $.ajax({
	       	    url:  "procesar.php",
                type: 'POST',
			    data: {idform: "BanArticulos",area:$(this).val()},
                dataType: "json",
			    success: function(datos){
			     if (datos.error==0){
			         $("#BAArticulos").html(datos.men);
			     }
                 else if (datos.error==1){
                    alert(datos.men);
                 }
			    },
                error: function(valor1,valor2,valor3){  Mensaje("error",AjaxError(valor2)); },
                complete: function(valor1,valor2){
                }
            });
    });

    $("#Cuerpo").on("change","#BPais",function (){
       	    $.ajax({
	       	    url:  "procesar.php",
                type: 'POST',
			    data: {idform: "BDPais",Pais:$(this).val()},
                dataType: "json",
			    success: function(datos){
			     if (datos.error==0){
			         $("#Estado").html(datos.men);
			     }
                 else if (datos.error==1){
                    alert(datos.men);
                 }
			    },
                error: function(valor1,valor2,valor3){  Mensaje("error",AjaxError(valor2)); },
                complete: function(valor1,valor2){
                }
            });
    });

    //Eliminar Imagenes del Panel Administrativo de los Clasificados
    $(".vpEliminarClasificado").on("click",function (){
         var res=confirm("¿Desea Eliminar La Foto Seleccionada [Aceptar/Cancelar]?")
         if (!res)
            return false;
        var cla=$(this).attr("rel");
        var img=$(this).attr("img");
        var id =$(this).parent().attr("id");

        /*alert("idClasificado: "+cla+" Imagen: "+imagen+" id: "+id);*/
        $.ajax({
	       	    url:  "procesar.php",
                type: 'POST',
			    data: {idform: "EliminarIMGClas",clasificado:cla,imagen:img},
                dataType: "json",
			    success: function(datos){
			     if (datos.error==0){

			      $("#"+id).remove();
                  $("#ICentrada").append("<div class='CampoCorto'><div class='botonInputFileModificado'><input type='file' class='inputImagenOculto' id='imagen[]' name='imagen[]''/><div class='boton''>Buscar Imagen</div></div></div>");
                  alert(datos.men);
			     }
                 else if (datos.error==1){
                    alert("Error "+datos.men);
                 }
			    },
                error: function(valor1,valor2,valor3){  Mensaje("error",AjaxError(valor2)); },
                complete: function(valor1,valor2){
                }
            });
    });
    //Eliminar Imagenes del Panel Administrativo de los Banners
    $(".vpEliminarBanner").on("click",function (){
         var res=confirm("¿Desea Eliminar La Imagen Seleccionada [Aceptar/Cancelar]?")
         if (!res)
            return false;
        var cla=$(this).attr("rel");
        var img=$(this).attr("img");
        var id =$(this).parent().attr("id");

        /*alert("idClasificado: "+cla+" Imagen: "+imagen+" id: "+id);*/
        $.ajax({
	       	    url:  "procesar.php",
                type: 'POST',
			    data: {idform: "EliminarIMGBann",idbanner:cla,imagen:img},
                dataType: "json",
			    success: function(datos){
			     if (datos.error==0){

			      $("#"+id).remove();
                  $("#ICentrada").append("<div class='botonInputFileModificado'><input type='file' class='inputImagenOculto' id='imagen' name='imagen'/><div class='boton'>Buscar Imagen</div></div>");
                  alert(datos.men);
			     }
                 else if (datos.error==1){
                    alert("Error "+datos.men);
                 }
			    },
                error: function(valor1,valor2,valor3){  Mensaje("error",AjaxError(valor2)); },
                complete: function(valor1,valor2){
                }
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
