$(document).ready(function() {
	//CAPTURA DE EVENTOS DE LOS FORMULARIOS

    $("#Cuerpo").on("change","#CPais",function (){
        //alert("Hola Mundo");
        //return false;
      if ($(this).val()==0)
         return false;

      $.ajax({
         url:  "pinternauta.php",
         type: 'POST',
         data: {idform: "BDPais",Pais:$(this).val()},
         dataType: "json",
         success: function(datos){
            if (datos.error==0){
                $("#Estado").html(datos.men);
            }else if (datos.error==1){
               alert(datos.men)
            }

         },
         error: function(valor1,valor2,valor3){
            Mensaje("error",AjaxError(valor2));
         },
         complete: function(valor1,valor2){
            //$('input[type=submit]').removeAttr('disabled');
         }
      });
    });

    $("#Cuerpo").on("change","#CPais2",function (){
        //alert("Hola Mundo");
        //return false;
      if ($(this).val()==0)
         return false;

      $.ajax({
         url:  "pinternauta.php",
         type: 'POST',
         data: {idform: "BDPais",Pais:$(this).val()},
         dataType: "json",
         success: function(datos){
            if (datos.error==0){
                $("#CEstado").html(datos.men);
            }else if (datos.error==1){
               alert(datos.men)
            }

         },
         error: function(valor1,valor2,valor3){
            Mensaje("error",AjaxError(valor2));
         },
         complete: function(valor1,valor2){
            //$('input[type=submit]').removeAttr('disabled');
         }
      });
    });

   //Carga Las Cuentas Correspondientes al Banco Seleccionado
    $("#CBanco").on("change",function (){
      if ($(this).val()==0)
         return false;

      $.ajax({
         url:  "pfranquiciados.php",
         type: 'POST',
         data: {idform: "BCuentas",banco:$(this).val(),idf:$("#idform").val()},
         dataType: "json",
         success: function(datos){
            if (datos.error==0){
               $("#Cuenta").html(datos.men);
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

   //Captura Cuando se clickea en enviar el formulario de contacto
    $('#CorreoWeb').on('submit',function (event){
      $('input[type=submit]').attr('disabled','true');
      $.ajax({
         url:  "pinternauta.php",
         type: 'POST',
         data: $(this).serialize(),
         dataType: "json",
         success: function(datos){
            if (datos.error==0){
               alert("Formulario Enviado Correctamente");
               window.location='index.php?op=Contacto';
            }else if (datos.error==1){
               alert(datos.men);
            }
         },
         error: function(valor1,valor2,valor3){
            Mensaje("error",AjaxError(valor2));
         },
         complete: function(valor1,valor2){
            $('input[type=submit]').removeAttr('disabled');
         }
      });
      return false;
    });
    //Registro de Clasificado Gratuito
    $('#RegClasificado').on('submit',function (event){
      $('input[type=submit]').attr('disabled','true');
      $.ajax({
         url:  "pinternauta.php",
         type: 'POST',
         data: $(this).serialize(),
         dataType: "json",
         success: function(datos){
            if (datos.error==0){
                $('#RegClasificado').hide();
                $('#info').removeClass('error');
                $('#info').addClass('info');
                $('#info').html(datos.men);

            }else if (datos.error==1){
                $('#info').addClass('error');
                $('#info').html(datos.men);
               //alert(datos.men);
            }
         },
         error: function(valor1,valor2,valor3){
            Mensaje("error",AjaxError(valor2));
         },
         complete: function(valor1,valor2){
            $('input[type=submit]').removeAttr('disabled');
         }
      });
      return false;
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
function Mensaje(tipo,men,base){
   $("#"+base+" #info").addClass(tipo);
   $("#"+base+" #info").html(men);
}
function LimpiarMensaje(base){
    $("#"+base+" #info").removeClass("error");
    $("#"+base+" #info").removeClass("info");
    $("#"+base+" #info").empty();
}
