// JavaScript Document
$(document).ready(function() {
    
/*    
    $.datepicker.setDefaults({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd/mm/yy',
		showOn:'focus'
	});
  
*/    

    

   	$("#Centro").on("focus","input[tipo=fechahora]",function (){
        $(this).datetimepicker({
            changeMonth: true,
            changeYear: true,
            timeFormat: 'HH:mm:ss',
            dateFormat: 'dd/mm/yy'	        
        });        
    });
    /*
    $("#THisImp").toggle(
        function (){
            $("#DHisImp").show();
        },function (){
            $("#DHisImp").hide();
        }
    );
    
    */
    
    $("body").on("click","div #THisImp",function (){
        //alert("Hola Mundo");
        $("#DHisImp").slideToggle();
        //alert("Hola Mundo");
    });
    
    $("body").on("click","div #THisDes",function (){
        //alert("Hola Mundo");
        $("#DHisDes").slideToggle();
        //alert("Hola Mundo");
    });
    
	//CAPTURA DE EVENTOS DE LOS FORMULARIOS
    $('#Centro').on('submit','form[name=formazul]',function (event){
	   if (event.keyCode==13){  return false;
            event.cancelBubble = true;
		    event.returnValue = false;
        }
          	   	   
	   $('input[type=submit]').attr('disabled','true');
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
				   $("#formazul").empty();
                   Mensaje("info",datos.men)
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
        
		return false;							
	});
    /*
	$('form[name=formazul]').live('submit',function (event){
	   if (event.keyCode==13){  return false;
            event.cancelBubble = true;
		    event.returnValue = false;
       }      	   	   
		$('input[type=submit]').attr('disabled','true');
		$.ajax({			
        	type: "POST",
	        url: $(this).attr('action'),    	    
			data:  $(this).serialize(),
            dataType: "json",
			beforeSend: function() { 
            },
	        success: function(datos){ 
	           //alert(datos);
			   if (datos.error==1){	
                   Mensaje("error",datos.men)
			   }else if (datos.error==0)
			   {
				   $("#formazul").empty();
                   Mensaje("info",datos.men)
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
			},
			complete: function(valor1,valor2){ $('input[type=submit]').removeAttr('disabled'); }
			
		});	
		return false;
							
	});
    
    */
    //PARA CARGAS LOS DESCUENTOS DESDE 0 - 75 DE MANERA AUTOMATICA SIEMPRE Y CUANDO NO EXISTAN YA REGISTROS
    /*
    $("body div").on('click',"#CDescuento", function (){
        //alert("Hola Mundo");	   
		$("#TDescuento").addClass("Oculto");
        $("#EDescuento").removeClass("Oculto");
        $("#EDescuento").removeClass("Visible");
	});
    */
    /*
    $('#LDescuentos').live('click',function (event){
        $(this).attr("disabled",true);
        $.ajax({			
        	type: "POST",
	        url: "procesar.php",    	    
			data:  { nameform: "LoadDescuento"  },
            dataType: "json",
			beforeSend: function() { },
	        success: function(datos){
	           if (datos.error==0){    
	               $('#ldescuentos').empty();
                   $('#ldescuentos').html("Descuentos Cargados Correctamente");                   
	           }                  
            },                                    
			error: function(valor1,valor2,valor3)
			{
			    AjaxError(valor2,"info"); 
			},
			complete: function(valor1,valor2){  }
			
		});
        
                
        alert("Hola Mundo");         
        $(this).removeAttr("disabled");                   
    }); 
    */

/*
    //PARA LOS PROVEEDORES EN EL FORMULARIO DE COMPRAS, CAPTURA LOS EVENTOS CUANDO SE PULSA O CUANDO SE CAMBIA SU VALOR	
	$('#rifprove').live('keyup',function (event){  LimpiarMensaje();  if ($("#rifprove").val().length>9) BuscarProveedorCompra();    });    
  	$('#rifprove').live('change',function (event){ LimpiarMensaje();  if ($("#rifprove").val().length>9) BuscarProveedorCompra();    });
    
    //PARA LOS PRODUCTOS EN EL FORMULARIO DE COMPRAS, CAPTURA LOS EVENTOS CUANDO SE PULSA O CUANDO SE CAMBIA SU VALOR
    $('#codprodu').live('keyup',function (event){   LimpiarMensaje();	BuscarProductoCompra();	   });    
  	$('#codprodu').live('change',function (event){  LimpiarMensaje();   BuscarProductoCompra();   });
    
    //AGREGA LOS TEXT PARA AGREGAR EL PRODUCTO NUEVO
    $('#NuevoPC').live('click',function (event){
        LimpiarMensaje();
        $("#unidad option").attr("selected",false);
        $("#marca option").attr("selected",false);
        $("#descuento option").attr("selected",false);
        $("#impuesto option").attr("selected",false);
        $("#datosprodu").removeClass("Oculto");
        $("#datosprodu").addClass("Visible");
        $("#AgregarPC").val("Guardar");
        $("#estproducto").val("Nuevo");
        $(this).attr("disabled",true);
    });    
    
    //AGREGAR EL PRODUCTO A LA LISTA DE PRODUCTOS A COMPRAR
    $('#AgregarPC').live('click',function (event){
        LimpiarMensaje();
        if ($("#estproducto").val()=="Nuevo"){
            $("#AgregarPC").val("Agregar");
            GuardarProductoCompra();                                                                  
        }else{
            BuscarProductoCompra($("#desprodu").val(),"bus_producto_des");    
            $("#desprodu").val(""); 
        }                            
    });        

    $('#CancelarPC').live('click',function (event){
        LimpiarMensaje();
        $("#datosprodu").removeClass("Visible");
        $("#datosprodu").addClass("Oculto");
        $("#estproducto").val("");                                
        $("#desprodu").val("");
        $('#NuevoPC').removeAttr("disabled");  
        $("#AgregarPC").val("Agregar");                    
    });

    $("#Delete").live("click",function (){
        $(this).parent('td').parent("tr").remove();
        CalculaIvas();		
		//calcularFactura();    
    });    
    
    $("#CAlicuota").live("click",function (){
        $("#alicuota").empty();
        $("#monto").removeAttr("readonly");
        var text='<input type="text" size="19" maxlength="19" name="fecha" id="fecha" value="'+FechaActual()+ " "+ HoraActual()+'" />';
        var copi='<input type="hidden" size="10" maxlength="10" name="copi" id="copi" value="'+$("#monto").val()+'" />';
        var bott='<input type="button" name="NAlicuota" id="NAlicuota" value="Guardar Alicuota" />';
        $("#alicuota").html(text+bott);
    });
    
    $("#NAlicuota").live("click",function (){
        var valor=parseInt($("#monto").val());
        var copia=parseInt($("#copi").val());
        if (isNaN(valor)){   valor=0.00; }
        if (isNaN(valor)){   copia=0.00;}
        if (valor==copia)   alert("No Hay Cambio");
        
        $.ajax({			
        	type: "POST",
	        url: "procesar.php",    	    
			data:  {monto:$('#monto').val(),fecha:$('#fecha').val(),fecdesde:$('#fecdesde').val(),idimpuesto:$('#idimpuesto').val(), nameform: "CambiarI"  },
            dataType: "json",
			beforeSend: function() { },
	        success: function(datos){
	           if (datos.error==1){	               
	               Mensaje("error","<center>"+datos.men+"<center>");    
                }
               else 
               {
                    $("#formazul").empty();
                    Mensaje("info","<center>"+datos.men+"<center>");    
               }   
            },                                    
			error: function(valor1,valor2,valor3)
			{
			    AjaxError(valor2,"info"); 
			},
			complete: function(valor1,valor2){  }
			
		});
        
    });
    */
    
    
    //  ACTIVA LOS BOTONES PARA QUE SE LES PUEDA CAMBIAR EL IMPUESTO EN LOS PRODUCTOS   
    $("div").on('click',"#CImpuesto", function (){     
        $("#TIva").addClass("Oculto");
        $("#EIva").removeClass("Oculto");
        $("#EIva").removeClass("Visible");
    });
    
    /*
    //  GUARDA EL APLICA EL CAMBIO DE IMPUESTO EN LOS PRODUCTOS
    $("#GImpuesto").live("click",function (){
        //var impuesto=parseInt($("#impuesto").val());
        //var copia=parseInt($("#copi").val());
        if ($("#impuesto").val()==$("#impactivo").val()){
            alert("Sin Cambios en El Impuesto");
            return false;
        }
                        
        if ($('#impuesto').val()==0)    alert("Seleccione Un Impuesto");
        else        
        $.ajax({			
        	type: "POST",
	        url: "procesar.php",    	    
			data:  {idimpuesto:$('#impuesto').val(),fecha:$('#fechaI').val(),fdesde:$("#fecdesdeimp").val(),producto: $('#codprod').val(), nameform: "AplicarI"  },
            dataType: "json",
			beforeSend: function() { },
	        success: function(datos){
	           //alert(datos.des);
	           $("#EIva").removeClass("Visible");
               $("#EIva").addClass("Oculto");
               $("#DIva").html(datos.des);
               $("#TIva").removeClass("Oculto");
               //$("#TIva").addClass("Visible");
               if (datos.error==1){    Mensaje("error","<center>"+datos.men+"<center>");    }
               else {   Mensaje("info","<center>"+datos.men+"<center>");    }   
	           //$("#info").html("<center>"+datos.men+"<center>");   
            },                                    
			error: function(valor1,valor2,valor3)
			{
			    AjaxError(valor2,"info"); 
			},
			complete: function(valor1,valor2){  }
			
		});
    });
*/
    //  ACTIVA LOS BOTONES PARA QUE SE LES PUEDA CAMBIAR EL IMPUESTO EN LOS PRODUCTOS   
    $("body div").on('click',"#CDescuento", function (){
        //alert("Hola Mundo");	   
		$("#TDescuento").addClass("Oculto");
        $("#EDescuento").removeClass("Oculto");
        $("#EDescuento").removeClass("Visible");
	});        

    //  GUARDA EL APLICA EL CAMBIO DE IMPUESTO EN LOS PRODUCTOS
    $("body div").on('click',"#GDescuento", function (){        
        if ($("#descuento").val()==$("#desactivo").val()){
            alert("Sin Cambios en El Descuento");
            return false;
        }
                        
        if ($('#descuento').val()==0)    alert("Seleccione Un Descuento");
        else        
        $.ajax({			
        	type: "POST",
	        url: "procesar.php",    	    
			data:  {iddes:$('#descuento').val(),fecha:$('#fechaD').val(),fdesde:$("#fecdesdedes").val(),producto: $('#codprod').val(), nameform: "AplicarD"  },
            dataType: "json",
			beforeSend: function() { },
	        success: function(datos){
	           if (datos.error==1){    Mensaje("error","<center>"+datos.men+"<center>");    }
               else
               {
                   $("#formazul").empty();
                   Mensaje("info","<center>"+datos.men+"<center>");
                        
                }    
            },                                    
			error: function(valor1,valor2,valor3)
			{
			    AjaxError(valor2,"info"); 
			},
			complete: function(valor1,valor2){  }
			
		});
    });
    
    /*   
    //SE ANULAN LOS CARACTERES QUE NO SEAN NUMEROS
    $(':input[rel=calcula]').live('keypress',function (event){
        if (!((event.which>47 && event.which<58) || (event.which==8) || (event.which==0) || (event.which==46)))
            //alert(event.which);
            event.preventDefault();    
    });
    
    //SE RECALCULA CADA VEZ QUE SE MODIFICA LA CANTIDAD O PRECION DE ALGUN PRODUCTO
    $(':input[rel=calcula]').live('keyup',function (event){        
        var fila=$(this).attr("fila");
        var precio=parseFloat($(":input[name^=pproducto][fila="+fila+"]").val()).toFixed(2);
        var cantidad=parseInt($(":input[name^=cproducto][fila="+fila+"]").val());
        var porceiva=parseFloat($(":input[name^=piva][fila="+fila+"]").val()).toFixed(2);
        //alert(porceiva);
        if (isNaN(cantidad) || (isNaN(precio))){ 
            stotal=0.00;   
            piva=0.00;  
        }
        else{    
            var stotal=parseFloat(precio*cantidad).toFixed(2); 
            var piva=parseFloat(stotal*(porceiva/100)).toFixed(2);
        }
        $(":input[name^=stotal][fila="+fila+"]").val(stotal);
        $(":input[name^=stiva][fila="+fila+"]").val(piva);
        CalculaIvas();
    });
    
    //ASIGNA LOS VALORES EN CERO CUANDO EL TEXT ESTA EN BLANCO
    $(':input[rel=calcula]').live('blur',function (event){		           
        if (isNaN(parseInt($(this).val()))){   $(this).val("0.00");    }
    });

*/
    
});




function BuscarProveedorCompra(){
    $.ajax({			
        	type: "POST",
	        url: "procesar.php",    	    
			data:  {rifprove:$('#rifprove').val(), nameform: "bus_proveedor"  },
            dataType: "json",
			beforeSend: function() { },
	        success: function(datos){  $("#compania").html(datos.compania);   },                                    
			error: function(valor1,valor2,valor3)
			{
			    AjaxError(valor2,"compania"); 
			},
			complete: function(valor1,valor2){  }
			
		});	        
}

function BuscarProductoCompra(valor,tipo){
    $.ajax({			
        	type: "POST",
	        url: "procesar.php",    	    
			data:  { codprodu: valor, nameform: tipo  },
            dataType: "json",
			beforeSend: function() { },
	        success: function(datos){

               
	           if (datos.error==0){
                   var nfila=$('#cdetalle tr').length;        
                   //var producto=utf8_encode(datos.des)           
                   var producto=datos.producto                   
	               if (($('#'+datos.codigo).length)>0) alert("Ya Esta en la Lista");
		           else{			              
                      var filas=fila(datos.codigo,datos.piva,producto,datos.precio,datos.diva);
		              $('#cdetalle').append(filas);                                          
                   }                   
	           }else{
	               Mensaje("error",datos.men);
	               //alert("Disculpe Producto No Encontrado Valor");
	           }
            },                                    
			error: function(valor1,valor2,valor3)
			{
			    AjaxError(valor2,"producto");
			},
			complete: function(valor1,valor2){  }			
		});	        
}


function GuardarProductoCompra(){
    $.ajax({			
        	type: "POST",
	        url: "procesar.php",    	    
			data:  { descripcion: $('#desprodu').val(),unidad:$('#unidad').val(),marca:$('#marca').val(),descuento:$('#descuento').val(),iva:$('#impuesto').val(), nameform: "add_producto"  },
            dataType: "json",
			beforeSend: function() { },
	        success: function(datos){	           
                if (datos.error==1){ 
                    Mensaje("info",datos.men)
                    //$("#info").html(datos.men);                            
                }else{
                    $("#datosprodu").removeClass("Visible");
                    $("#datosprodu").addClass("Oculto");
                    $("#estproducto").val("");                                
                    $("#desprodu").val("");
                    $('#NuevoPC').removeAttr("disabled");  
                                      
                    BuscarProductoCompra(datos.codigo,"bus_producto_codigo"); 
                }
               },                                    
			error: function(valor1,valor2,valor3)
			{
			    AjaxError(valor2,"producto");
			},
			complete: function(valor1,valor2){  }			
		});	               
    //return ID;        
}

function fila(cod,piva,des,pre,diva){	
    var codigo=  '<td width="320"><input type="hidden" name="codprod[]" value="'+cod+'" id="codprod[]" /><input type="hidden" fila="'+cod+'" name="piva[]" value="'+piva+'" id="piva[]" /> <input type="hidden" fila="'+cod+'" name="diva[]" value="'+diva+'" id="diva[]" />'+des+'</td>';
    var precio=  '<td align="right" width="70"><input type="text" class="Numeros" rel="calcula" fila="'+cod+'" size="7" maxlength="7" name="pproducto[]" id="pproducto[]" value="'+pre+'" /></td>';
    var cantidad='<td align="right" width="70"><input type="text" class="Numeros" rel="calcula" fila="'+cod+'" size="5"  maxlength="5" name="cproducto[]" id="cproducto[]" value="0" /></td>';
    var iva     ='<td align="right"width="70"><input type="text" class="Numeros" fila="'+cod+'" readonly="readonly" size="7" maxlength="7" name="stiva[]" id="stiva[]" value="0" /></td>';
    var stotal=  '<td align="right" width="50"><input type="text" class="Numeros" fila="'+cod+'" readonly="readonly" size="10" maxlength="10" name="stotal[]" id="stotal[]" value="0" /></td>';
    var img=     '<td width="20"><a id="Delete" href="#" fila="'+cod+'"> <img border="0" src="../listado/imagenes/borrar.png" width="17" height="18" alt="Eliminar" /></a></td>';
    var fila=    '<tr id="'+cod+'">'+codigo+precio+cantidad+stotal+iva+img+'</tr>';
    return fila;
}
function AjaxError(valor){
    switch(valor){
        case 'error':	return "Disculpe Destino No Encontrado";	break;
		case 'timeout':	return "Disculpe Se Excedio el Tiempo de Espera";	break;
		case 'notmodified':	return "Error, El Archivo de Destino No Se Puede Leer";	break;
		case 'parsererror':	return "Error, Retorno Invalido de Datos [XML/JSON]";	break;																				
    }
}
/* FUNCION PARA EL CALDULO DEL IVA */
function CalculaIvas(){
   piva=$(":input[name^=piva]").toArray();   
   //tiva=$(":input[name^=stiva]").toArray();
   diva=$(":input[name^=diva]").toArray();
   subt=$(":input[name^=stotal]").toArray();
   
   var i=0,pos=0, ivas=new Array(),tivas=new Array(),ivades=new Array(),tsub=new Array(),enc=0,suma=0.00;   
   for (i=0;i<piva.length;i++){ 
     if (ivas.length>0){
        pos=i;
        enc=inArray(ivas,parseFloat(piva[i].value).toFixed(2));
        
        if (enc==-1){            
            pos=ivas.length;
            ivas[pos]=piva[i].value;
            //tivas[pos]=tiva[i].value; 
            ivades[pos]=diva[i].value;
            tsub[pos]=subt[i].value;           
        }else{
            pos=enc;
            //var ant=parseFloat(tivas[enc]).toFixed(2);
            //var nue=parseFloat(tiva[i].value).toFixed(2);
            
            var sant=parseFloat(tsub[enc]).toFixed(2);
            var snue=parseFloat(subt[i].value).toFixed(2);
            
            //if (isNaN(ant)){    ant=0.00;   }else{   ant=ant*1; }
            //if (isNaN(nue)){    nue=0.00;   }else{   nue=nue*1; }            
            
            if (isNaN(sant)){    sant=0.00;   }else{   sant=sant*1; }
            if (isNaN(snue)){    snue=0.00;   }else{   snue=snue*1; }
            //suma=parseFloat(ant+nue).toFixed(2);
            ssuma=parseFloat(sant+snue).toFixed(2);
            
            //tivas[enc]=parseFloat(suma).toFixed(2);
            tsub[enc]=parseFloat(ssuma).toFixed(2);
        }
     }else{
        ivas[i]=piva[i].value;        
        //tivas[i]=tiva[i].value;
        ivades[i]=diva[i].value;
        tsub[i]=subt[i].value;
     }
     ivas[pos]=parseFloat(ivas[pos]).toFixed(2);
     //tivas[pos]=parseFloat(tivas[pos]).toFixed(2);
     tsub[pos]=parseFloat(tsub[pos]).toFixed(2);
   }
   var subtotal=0;
   var ivatotal=0;   
   var cadena="";
   for (i=0;i<ivas.length;i++)
   {
        tivas[i]=parseFloat((tsub[i]*ivas[i]/100)).toFixed(2);
        subtotal+=(tsub[i]*1);
        ivatotal+=(tivas[i]*1);
        if (ivas[i]==0)
            cadena+="<span id='ResumenSub'>Monto Total Exento <strong>"+tsub[i]+"Bs.</strong><br>";
        else            
            cadena+="<span id='ResumenSub'>Monto Total Base ["+ivas[i]+"%] <strong>"+tsub[i]+"Bs.</strong></span><br><span id='ResumenIva'>Monto Total "+ivades[i]+" ["+ivas[i]+"%] <strong>"+tivas[i]+"Bs.</strong></span><br>";
   }
   $("#ResumenFact").html(cadena);
   //alert("Subtotal: "+ subtotal+" IvaTotal: "+ivatotal);
   $("#subtotal").val(parseFloat(subtotal).toFixed(2));
   $("#ivatotal").val(parseFloat(ivatotal).toFixed(2));
   $("#totalfact").val(parseFloat(subtotal+ivatotal).toFixed(2));
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
    var hora =fecha.getHours();
    if (hora<10) hora="0"+hora;
    var minuto=fecha.getMinutes();
    if (minuto<10) minuto="0"+minuto;
    var segundo=fecha.getSeconds();
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
/*FIN DE LAS FUNCIONES PARA EL CALCULO DEL IVA */
/*

var codigo=  '<td width="320"><input type="hidden" name="cproducto[]" value="'+cod+'" id="cproducto[]" /><input type="hidden" fila="'+index+'" name="piva[]" value="'+piva+'" id="piva[]" />'+des+'</td>';
    var precio=  '<td width="70"><input type="text" class="Numeros" rel="calcula" fila="'+index+'" size="10" maxlength="10" name="pproducto[]" id="pproducto[]" value="'+pre+'" /></td>';
    var cantidad='<td width="70"><input type="text" class="Numeros" rel="calcula" fila="'+index+'" size="5"  maxlength="5" name="cproducto[]" id="cproducto[]" value="0" /></td>';
    var iva     ='<td width="70"><input type="text" class="Numeros" fila="'+index+'" readonly="readonly" size="10" maxlength="10" name="stiva[]" id="stiva[]" value="0" /></td>';
    var stotal=  '<td width="50"><input type="text" class="Numeros" fila="'+index+'" size="10" maxlength="10" name="stotal[]" id="stotal[]" value="0" /></td>';
    var img=     '<td width="20"><a id="Delete" href="#" fila="'+cod+'"> <img border="0" src="../listado/imagenes/borrar.png" width="17" height="18" alt="Eliminar" /></a></td>';
    var fila=    '<tr id="'+cod+'">'+codigo+precio+cantidad+stotal+iva+img+'</tr>';
    
*/    

