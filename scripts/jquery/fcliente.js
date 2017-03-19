$(document).ready(function() {
	//CAPTURA DE EVENTOS DE LOS FORMULARIOS
    $('#Cuerpo').on('submit','form[name=FormCliente]',function (event){
	   if (event.keyCode==13){  return false;
            event.cancelBubble = true;
		    event.returnValue = false;
        }
        $('input[type=submit]').attr('disabled','true');
        if ($("#idform").val()!="bannareas"){
  	       $.ajax({
       	     type: "POST",
  	           url: $(this).attr('action'),
  			     data:  $(this).serialize(),
              dataType: "json",
	           beforeSend: function() {
	              $('input[type=submit]').attr("value","Procesando Información");
              },
  	           success: function(datos){
               if (datos.error==1){
                     Mensaje("error",datos.men)
  			       }else if (datos.error==0)
  			       {
  				      $(".Articulo").empty();
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
  			       $('input[type=submit]').attr("value","Activar Participación");
  			       $('input[type=submit]').removeAttr('disabled');
               }
  		   });
        } else {
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

    //Valida Que Solo Ingresen Numero y Puntos en el Campo Donde Se Ingresa el Monto Depositado
    $("#MontoDep").on("keypress",function (e){
        if (!((e.which>=48 && e.which<58) || e.which==8 || e.which==46 )){
            return false;
        }
    });
    //Calcula el Monto en la Moneda Base y lo Muestra
    $("#MontoDep").on("keyup",function (e){
        var monto=$("#MontoDep").val();
        if (isNaN(monto))   monto=0;
        var cambio=$("#cambio").val();
        if (isNaN(cambio))  cambio=0;
        var montobase=Math.round((monto/cambio)*100)/100;
        $("#MontoBase").html(montobase);
        $("#MonBase").val(montobase);
        //alert($("#MonBase").val());
    });

   //Carga Las Cuentas Correspondientes al Banco Seleccionado
    $("#CBanco").on("change",function (){
      if ($(this).val()==0)
         return false;

      $.ajax({
         url:  "pcliente.php",
         type: 'POST',
         data: {idform: "BCuentas",banco:$(this).val()},
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

    $("input[name='radio-set']").on('click', function (){
        if ($(this).attr('id')=='tab-2') {
            $("#CBanco").val('0');
            $("#Cuenta option").remove();
            $("#Cuenta").append('<option>Seleccione Banco</option>');
            $("#nroref").val('');
            $("#fecha").val('');
            $("#MontoDep").val(0);
            $("#MontoBase").text('0.00');
        } else if($(this).attr('id')=='tab-1') {
            $("#nomCard").val('');
            $("#numCard").val('');
            $("#mDateCard").val(0);
            $("#yDateCard").val(0);
            $("input[name='typeCard']").removeAttr('checked', false);
            $("#security").val('');
        }
    })

/*
    $('#MontoDep').keyup(function (){
            this.value = (this.value + '').replace(/[^0-9.+\-Ee.]/g, '');
          });
*/
/*
    $("#MontoDep").on("keyup",function (e){

        if (!((e.which>=48 && e.which<58) || e.which==8 || e.which==46)){
            //alert(e.which);
            //e.preventDefault();
            e.returnValue=false;
            return false;
           //
        }else{
            var monto=$("#MontoDep").val();
            var cambio=$("#cambio").val();
            $("#MontoBase").html((monto/cambio));
        }
        //alert(e.which);
    });
*/

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
