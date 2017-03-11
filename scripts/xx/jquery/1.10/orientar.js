function Listado(archivo,titulo,listado){            
    var ruta ="../listado/";    
    //alert("Hola Mundo");
    var form = ruta+archivo+".php"+"?tipolis="+listado+"&titulo="+titulo;
    
    //$("#Otros").hide();
    /*
    $("#Nuevo").attr("link",archivo);          
    $("#Listado").attr("link","listados.php");
    $("#Listado").attr("title",titulo);
    $("#Listado").attr("title",titulo);
    if (tipo=="listado")    var form =rutal+archivo+"?tipolis="+listado+"&titulo="+titulo;
    else if  (tipo=="oficio") form = rutaf+archivo;
    
    */
	//var form=ruta+fcall;
    //alert(form);
	$("#Centro").load( form, function(response, status, xhr) {		   
  	    if (status == "error") {  var msg = "Disculpe Ocurrio Un Error : ";	  $("#Centro").html(msg + xhr.status + " " + xhr.statusText + " "+ form); }
        $("#Centro").fadeIn();                
    });
                    
}

    //CARGA LOS FORMULARIOS PARA INGRESAR UN REGISTRO NUEVO
function LoadForm(archivo){            
    var form = archivo+".php";
	$("#Centro").load( form, function(response, status, xhr) {		   
  	    if (status == "error") {  var msg = "Disculpe Ocurrio Un Error : ";	  $("#Centro").html(msg + xhr.status + " " + xhr.statusText + " "+ form); }
        $("#Centro").fadeIn();                
    });                    
}
    //CARGA LOS FORMULARIOS PARA EDITAR
function LoadEditar(archivo,params){
    //alert("Hola Mundo");
    
    var form = archivo+".php?"+params;
    //alert(form);
	$("#Centro").load( form, function(response, status, xhr) {		   
  	    if (status == "error") {  var msg = "Disculpe Ocurrio Un Error : ";	  $("#Centro").html(msg + xhr.status + " " + xhr.statusText + " "+ form); }
        $("#Centro").fadeIn();                
    });
                        
}