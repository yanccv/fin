$(document).ready(function() {
	//CAPTURA DE EVENTOS DE LOS FORMULARIOS
    $('#Cuerpo').on('submit','form[name=FormFranquiciado]',function (event) {
       var formulario=$(this).parent().attr("id");
       LimpiarMensaje(formulario);
	   if (event.keyCode==13){  return false;
            event.cancelBubble = true;
		    event.returnValue = false;
        }

        $('input[type=submit]').attr('disabled','true');
        if ($("#idform").val()!="bannareas") {
            $.ajax({
           	    type: "POST",
      	        url: $(this).attr('action'),
      			data:  $(this).serialize(),
                dataType: "json",
      			beforeSend: function() {
                },
      	        success: function(datos) {
      	            $("#info").fadeIn();
                   if (datos.error==1){
                        Mensaje("error",datos.men,formulario);
                   } else if (datos.error==0) {
                        Mensaje("info",datos.men,formulario);
                        if ($("#idform").val()=="CCuenta") {
                            if ($("#tform").val()=="E"){ $("#"+$("#id").val()).replaceWith(datos.fila);  }
                            else{   $("#TCuentas").append(datos.fila);  }
                            $("#info").fadeOut(3000,function (){
                               $("#tform").val('A')
                               $("#id").val("");
                               $("#cuenta").val("");
                               $('#Banco').val(0);
                               $("input:radio").prop('checked', false);
                            });
                        } else if ($("#idform").val()=="CRetiros") {
                            //Formulario de Retiros
                            if ($("#tform").val()=="E"){ $("#"+$("#id").val()).replaceWith(datos.fila);  }
                            else{   $("#TRetiros").append(datos.fila);  }
                            $("#info").fadeOut(3000,function (){
                               $("#tform").val('A')
                               $("#id").val("");
                               $("#cuenta").val("");
                               $("#NewSaldo").html('0.00');
                               $("#MontoRet").val('0.00');
                               $('#MontoBase').html('0.00');
                               $("#SaldoDis").val(datos.saldo);
                               $("#SaldoDifOficial").html(parseFloat(datos.diferido*parseFloat($("#cambio").val()).toFixed(2)).toFixed(2));
                               $("#SaldoDifBase").html((datos.diferido).toFixed(2));
                               $("select > option").removeAttr("selected");
                               $("#Cuenta").html("<option value='0'>Seleccion Banco</option>");
                            });
                        } else if ($("#idform").val()=="CRecapitalizar") {
                            $("#info").delay(1000);
                            $("#info").fadeOut(300,function (){
                               window.location='recapitalizar.php';
                            });
                        } else if ($("#idform").val()=="CRenovar") {
                            $("#info").delay(1000);
                            $("#info").fadeOut(300,function (){
                               window.location='renovar.php';
                            });
                        } else if ($("#idform").val()=="FPMAfiliarse") {
                            $("#info").delay(1000);
                            $("#info").fadeOut(300,function (){
                               window.location='publicidad.php';
                            });
                        } else {
                            Mensaje("info",datos.men,formulario)
                            $("#"+formulario+" > form[name=FormFranquiciado]").remove();
                        }
                    }
                }, error: function(valor1,valor2,valor3) {
                       Mensaje("error",AjaxError(valor2));
                },  complete: function(valor1,valor2){
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
			    error: function(valor1, valor2, valor3){
				    alert("Error, No Se Cargo El Archivo ");
                    return false;
                },
                complete: function(valor1,valor2){  $('input[type=submit]').removeAttr('disabled'); }
            });
        }
		return false;
	});

    $('#Cuerpo').on('submit','form[name=RegClasificado]',function (event){
        $('input[type=submit]').attr('disabled','true');
        var formulario=$(this).parent().attr("id");

        var formData = new FormData($("#RegClasificado")[0]);
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
                    $("#RegClasificado").empty();
                    Mensaje("info",datos.men,formulario)
                }
            },
            error: function(valor1, valor2, valor3){
                alert("Error, No Se Cargo El Archivo X"+valor1+'|'+valor2+'|'+valor3);
                return false;
            },
            complete: function(valor1,valor2){  $('input[type=submit]').removeAttr('disabled'); }
        });
		return false;
	});

    /*
    $('#CampoCorto').on('keydown','input[name=MontoDep]',function (event){
        alert("Hola Mundo");

    });
    */
    /*
    $("#CampoCorto").on("keyup click","#MontoDep",function(){
        alert("Hola Mundo");
    });
    */

    $("#MontoRet").on("keypress",function (e){
        if (!((e.which>=48 && e.which<58) || e.which==8 || e.which==46 )){
            return false;
        }
    });
    $("#MontoDep").on("keypress",function (e){
        if (!((e.which>=48 && e.which<58) || e.which==8 || e.which==46 )){
            return false;
        }
    });


    $("#MontoRet").on("click",function (e){
      $(this).select();
    });

    $("#MontoDep").on("click",function (e){
      $(this).select();
    });


    $("#MontoRet").on("keyup",function (e){
        //alert($(this).val());
        var monto=$("#MontoRet").val()*1;
        if (isNaN(monto))   monto=0;
        var cambio=$("#cambio").val()*1;
        if (isNaN(cambio))  cambio=0;
        var montobase=Math.round((monto*cambio)*100)/100;
        $("#MontoBase").html(montobase);
        $("#MonBase").val(montobase);
        var Saldo=$("#SaldoDis").val()*1;
        var NSaldo=0;
        if (isNaN(Saldo))  Saldo=0;
        if (monto>Saldo){
            alert("Saldo Insuficiente");
            $("#MontoRet").val(0.00);
            $("#MontoRet").select();
            $("#MontoBase").html("0.00");
         NSaldo=0;
        }
        else
         NSaldo=Saldo-monto;
        $("#NewSaldo").html(Math.round(NSaldo*100)/100);
    });

    $("#cupones").on("keyup",function (e){
        var cupones = $("#cupones").val();
        var preciocupon = $("#preciocupon").val();
        var porcecupon = $("#porcecupon").val();
        if (isNaN(cupones)) {
            cupones = 0;
        }
        if (isNaN(preciocupon)) {
            preciocupon = 0;
        }
        if (isNaN(porcecupon)) {
            porcecupon = 0;
        }
        var total = Math.round((cupones * preciocupon)*(porcecupon/100));
        alert(cupones +'*'+ preciocupon+'='+total);
        $("#total").val(total);
    });

    $("#MontoDep").on("keyup",function (e){
        var monto=$("#MontoDep").val();
        if (isNaN(monto))   monto=0;
        var cambio=$("#cambio").val();
        if (isNaN(cambio))  cambio=0;
        var montobase=Math.round((monto/cambio)*100)/100;
        if (montobase>$("#capital").val()){
            alert("Disculpe Excede el Maximo de Inversion de la Franquicia");
            $("#MontoBase").html('0.00');
            $("#MonBase").val('0.00');
            $(this).val('0.00');
        }else{
           $("#MontoBase").html(montobase);
           $("#MonBase").val(montobase);
        }
    });

    $("#CPlan").on("change",function (){
      if ($(this).val()==0)
         return false;

      $.ajax({
         url:  "pfranquiciados.php",
         type: 'POST',
         data: {idform: "BDPlan",idplan:$(this).val()},
         dataType: "json",
         success: function(datos){
            if (datos.error==0){
               if (datos.foto=="S"){
                $("#LoadFotos").css("visibility","visible");
                $("#LoadFotos").css("display","block");
               }else{
                $("#LoadFotos").css("visibility","hidden");
                $("#LoadFotos").css("display","none");
               }
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


    $("#Cuerpo").on("change","#CPais",function (){
    //$("#CPais").on("change",function (){
        alert("Hola Mundo");
        return false;
      if ($(this).val()==0)
         return false;

      $.ajax({
         url:  "pfranquiciados.php",
         type: 'POST',
         data: {idform: "BDPlan",idplan:$(this).val()},
         dataType: "json",
         success: function(datos){
            if (datos.error==0){
               if (datos.foto=="S"){
                $("#LoadFotos").css("visibility","visible");
                $("#LoadFotos").css("display","block");
               }else{
                $("#LoadFotos").css("visibility","hidden");
                $("#LoadFotos").css("display","none");
               }
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

    $("#Cuerpo").on("click","#EditarCue",function (){
      var id=$(this).attr("rel");
      //Limpio
      $('#Banco > option').removeAttr('selected').find('option:first').prop('selected', 'selected');

      $.ajax({
         url:  "pfranquiciados.php",
         type: 'POST',
         data: {idform: "CargarCuenta",idcuenta:id},
         dataType: "json",
         success: function(datos){
            if (datos.error==0){
                //$('#Banco> option').removeAttr("selected");
                $("#id").val(id);
                $("#tform").val("E");
                $("#cuenta").val(datos.cuenta);

                if (datos.tipo=="A"){   $("input:radio[value=A]").prop('checked', true);    }else{  $("input:radio[value=C]").prop('checked', true);    }
                $("#Banco> option[value='"+datos.banco+"']").prop('selected', 'selected');

                $("#Banco").trigger("change");
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


    //Borrado de Cuentas########################################
    $("#Cuerpo").on("click","#BorrarCue",function (){
      if(!confirm("Desea Eliminar Esta Cuenta ?")){
         return false;
      }
      //$(this).parent().remove();

      var id=$(this).attr("rel");
      $("#"+id).remove();
      $.ajax({
         url:  "pfranquiciados.php",
         type: 'POST',
         data: {idform: "BorrarCuenta",idcuenta:id},
         dataType: "json",
         success: function(datos){
            if (datos.error==0){
               $(this).parent().remove();
               alert(datos.men);
            }else if (datos.error==1){
               alert(datos.men);
            }
         },
         error: function(valor1,valor2,valor3){
            //alert(AjaxError(valor2));
            alert("Error");
            //Mensaje("error",AjaxError(valor2));
         },
         complete: function(valor1,valor2){
         }
      });
      return false;
    });

    //Borrado de Retiros########################################
    $("#Cuerpo").on("click","#BorrarRet",function (){
      if(!confirm("Desea Eliminar Este Retiro ?")){
         return false;
      }
      //$(this).parent().remove();

      var id=$(this).attr("rel");
      $("#"+id).remove();
      $.ajax({
         url:  "pfranquiciados.php",
         type: 'POST',
         data: {idform: "BorrarRetiro",idcuenta:id},
         dataType: "json",
         success: function(datos){
            if (datos.error==0){
               //$("table > #"+id).remove();
               $("#NewSaldo").html('0.00');
               $("#SaldoDis").val(datos.saldo);
               $("#SaldoDifOficial").html(parseFloat(datos.diferido*parseFloat($("#cambio").val())).toFixed(2));
               $("#SaldoDifBase").html((datos.diferido).toFixed(2));

               $(this).parent().remove();
               alert(datos.men);
            }else if (datos.error==1){
               alert(datos.men);
            }
         },
         error: function(valor1,valor2,valor3){
            //alert(AjaxError(valor2));
            alert("Error");
            //Mensaje("error",AjaxError(valor2));
         },
         complete: function(valor1,valor2){
         }
      });
      return false;
    });

    //Borrado de Depositos de Recapitalizaciones ########################################
    $("#Cuerpo").on("click","#BorrarRec",function (){
      if(!confirm("Desea Eliminar Este Deposito ?")){
         return false;
      }

      var id=$(this).attr("rel");
      $("#"+id).remove();
      $.ajax({
         url:  "pfranquiciados.php",
         type: 'POST',
         data: {idform: "BorrarRecapi",idcuenta:id},
         dataType: "json",
         success: function(datos){
            if (datos.error==0){
               alert(datos.men);
               $(this).delay(300);
               window.location='recapitalizar.php';
            }else if (datos.error==1){
               alert(datos.men);
            }
         },
         error: function(valor1,valor2,valor3){
            //alert(AjaxError(valor2));
            alert("Error");
            //Mensaje("error",AjaxError(valor2));
         },
         complete: function(valor1,valor2){
         }
      });
      return false;
    });


    //Borrado de Depositos de Recapitalizaciones ########################################
    $("#Cuerpo").on("click","#BorrarRen",function (){
      if(!confirm("Desea Eliminar Este Deposito ?")){
         return false;
      }

      var id=$(this).attr("rel");
      $("#"+id).remove();
      $.ajax({
         url:  "pfranquiciados.php",
         type: 'POST',
         data: {idform: "BorrarRenova",idcuenta:id},
         dataType: "json",
         success: function(datos){
            if (datos.error==0){
               alert(datos.men);
               $(this).delay(300);
               window.location='renovar.php';
            }else if (datos.error==1){
               alert(datos.men);
            }
         },
         error: function(valor1,valor2,valor3){
            //alert(AjaxError(valor2));
            alert("Error");
            //Mensaje("error",AjaxError(valor2));
         },
         complete: function(valor1,valor2){
         }
      });
      return false;
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

   //Carga Los Banner Disponibles para el Area Seleccionada
    $("#CArea").on("change",function (){
        //alert("Hola Mundo");
      if ($(this).val()==0)
         return false;

      $.ajax({
         url:  "pfranquiciados.php",
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

    $("#CBanner").on("change",function (){
      if ($(this).val()==0)
         return false;

      $.ajax({
         url:  "pfranquiciados.php",
         type: 'POST',
         data: {idform: "BDBanner",idbanner:$(this).val()},
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
function Mensaje(tipo,men,base){
   $("#"+base+" #info").addClass(tipo);
   $("#"+base+" #info").html(men);
}
function LimpiarMensaje(base){
    $("#"+base+" #info").removeClass("error");
    $("#"+base+" #info").removeClass("info");
    $("#"+base+" #info").empty();
}
