// JavaScript Document

$(document).ready(function() {
	var rcss='../css/';
	var rfor='';
	var vedi="";
    $("#CapaLoad").hide();
    $("#Otros").hide();
    //var proceso="";
	
    
    
    
//	##########################################################
//		    CARGANDO FORMULARIO SEGUN ENLACE DEL LINK
//	##########################################################

/*	
	$('#menu a').click(function (){
	   $("#Otros").hide();
       $("#CapaForms").empty();
        switch($(this).attr('type')){
            case "form":
                var ruta="../forms/"; 
            break;            
        }                
        
		var form=ruta +$(this).attr('link');
        $("#Nuevo").attr("link",$(this).attr('link'));  
        $("#Listado").attr("link","lis_"+$(this).attr('link'));
                
               
		$("#CapaForms").load(form, function(response, status, xhr) {		   
  		    if (status == "error") {  var msg = "Disculpe Ocurrio Un Error : ";	  $("#CapaForms").html(msg + xhr.status + " " + xhr.statusText); }
            $("#CapaForms").fadeIn();    
        });       		
	});	
*/


//	##########################################################
//		    CARGANDO FORMULARIO SEGUN ENLACE DE LOS BOTONES AUXILIARES
//	##########################################################	
    
    $('#obotones a').click(function (){
        //$("#CapaForms").empty();
        switch($(this).attr('type')){
            case "form":    var ruta="../forms/";   break;            
        }                
        var archivo = $(this).attr('link');
		var form=ruta+archivo;  
        
        //Asignacion de Nuevo a los Botones Auxiliares
        $("#Nuevo").attr("link",archivo);          
        $("#Listado").attr("link","lis_"+ archivo);              
          
        //Mostrando Formulario en Capa Correcspondiente     
		$("#CapaForms").load(form, function(response, status, xhr) {		   
  		    if (status == "error") {  var msg = "Disculpe Ocurrio Un Error : ";	  $("#CapaForms").html(msg + xhr.status + " " + xhr.statusText +" "+ form); }
            $("#CapaForms").show("slow",function () {  if($.browser.msie){  this.style.removeAttribute('filter');   } });             
            $("#Otros").hide();   
        });       		
	});


/*    
    $('textarea.ck').ckeditor(function(){

        // callback code  
        }
        ,         
        {
    	  extraPlugins : 'autogrow',							
    	  toolbar :
	   	  [
		  ['Save','Preview','-','Cut','Copy','Paste','PasteFromWord','PasteText','SelectAll','RemoveFormat','-','Undo','Redo','-','Table','Image','Flash','-','Link','Unlink','Anchor','HorizontalRule'],
		  ['Bold', 'Italic','Underline','-','Subscript','Superscript','-','NumberedList','BulletedList','-','Indent', 'Outdent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','SwitchBar','Maximize','SpecialChar','-','TextColor','BGColor',],
		  '/',
		  ['Styles','Format','Font','FontSize','Iframe','PageBreak','Print','Templates'],
		  ],					
		  filebrowserBrowseUrl : '../scripts/ckfinder/ckfinder.html',
		  filebrowserImageBrowseUrl : '../scripts/ckfinder/ckfinder.html?type=Images',
		  filebrowserFlashBrowseUrl : '../scripts/ckfinder/ckfinder.html?type=Flash',
		  filebrowserUploadUrl : '../scripts/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
		  filebrowserImageUploadUrl : '../scripts/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
		  filebrowserFlashUploadUrl : '../scripts/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
		  filebrowserWindowWidth : '500',
 		  filebrowserWindowHeight : '500'
	   }
    );
*/


/*	 		
	$('#lformulario').click(function (){
		var fcss=	$('#lformulario').attr('css');
		$('#lforms').attr('href',rcss+fcss);
		var form=$('#lformulario').attr('form');
		$("#Mformulario").fadeOut('slow');
		$("#Mformulario").hide();
		$("#Mformulario").load(rfor + form,{ idarea:2,tipo:'E'}, function(response, status, xhr) {
			$("input[type=text]").tooltip({
				position: "center right",
				offset: [-2, 10],
				effect: "fade",
				opacity: 0.7
			});
  		if (status == "error") {
    		var msg = "Disculpe Ocurrio Un Error : ";
    		$("#Mformulario").html(msg + xhr.status + " " + xhr.statusText);
  		}
		$("#Mformulario").fadeIn('slow');		
	});
		
	});	
*/	
	
//	##########################################################
//		 CREANDO EVENTO SUMBIT PARA TODOS LOS FORMULARIOS
//	##########################################################		 		
	$('form[name=formazul]').live('submit',function (event){
	   if (event.keyCode==13){  return false;
            event.cancelBubble = true;
		    event.returnValue = false;
       }
       //alert("Hola");
		$('input[type=submit]').attr('disabled','true');
		$.ajax({			
        	type: "POST",
	        url: $(this).attr('action'),    	    
			data:  $(this).serialize(),
            //dataType: "json",
			beforeSend: function() { 
		       $("#CapaLoad").slideToggle("slow",function () { if($.browser.msie){    this.style.removeAttribute('filter'); } });
               $("#light").css("display","block");
               $("#fade").css("display","block");                
            },
	        success: function(datos){ 
	           //$(".appriseOverlay").hide();
	           $("#CapaLoad").slideToggle("slow",function () { if($.browser.msie){   this.style.removeAttribute('filter'); } });  
               
               
               //var json=eval(datos);
               $("#resumen").html(datos+"<center><strong>"+datos.titulo+"</strong><br />"+datos.men+"<br /></center>");
               //$("#resumen").html("Oficina: "+datos.oficina+"<br /> Cargo: "+datos.cargo+"<br />Estatus: "+datos.estatus);
               if (datos.error==0){
                    $("#CapaForms").slideUp("slow",function () { if($.browser.msie){ this.style.removeAttribute('filter'); } });
                    $("#CapaForms").empty();
                    $("#CapaForms").hide();
                    $("#Otros").show("slow",function () {  if($.browser.msie){  this.style.removeAttribute('filter'); } });
               }
               //apprise('Informacion Devuelta ' + datos); 
            },
            
            
            //apprise('Enviando Formulario');
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
	

    
    $('form').live("keypress",function(e){   
    if(e == 13){
        e.cancelBubble = true;
		e.returnValue = false;
      return false;
    }
  });
  
  
  $("input[name=toficina]").live("click",function(e){
    if ($(":checked").val()=="I"){
        $("div[name=lrif]").html("ID");
        $("#id").attr("readonly","true");
        $("#id").val($("#nativo").val());
        //alert("Interno")
    }else{
        //alert("Externo");
        $("div[name=lrif]").html("RIF");
        $("#id").removeAttr("readonly");        
        $("#id").val("");
    }
  });
			
});

function loadSeccion(tipo,archivo,titulo,listado){    
    var rutaf ="../forms/";    
    var rutal ="../listado/";
    $("#Otros").hide();
    $("#Nuevo").attr("link",archivo);          
    $("#Listado").attr("link","listados.php");
    $("#Listado").attr("title",titulo);
    $("#Listado").attr("title",titulo);
    if (tipo=="listado")    var form =rutal+archivo+"?tipolis="+listado+"&titulo="+titulo;
    else if  (tipo=="oficio") form = rutaf+archivo;
    else var form = rutaf+archivo;
    
	//var form=ruta+fcall;
	$("#CapaForms").load( form, function(response, status, xhr) {		   
  	    if (status == "error") {  var msg = "Disculpe Ocurrio Un Error : ";	  $("#CapaForms").html(msg + xhr.status + " " + xhr.statusText + " "+ form); }
        $("#CapaForms").fadeIn();                
    });                
}

function oficinas(option){
    
}
