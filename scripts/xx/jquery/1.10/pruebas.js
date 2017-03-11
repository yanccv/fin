$(document).ready(function() {
    $("#Boton").live("click",function (){        
        CalculaIvas();
    })   
          
});

function CalculaIvas(){
   piva=$(":input[name^=piva]").toArray();   
   tiva=$(":input[name^=tiva]").toArray();
   
   var i=0,pos=0, ivas=new Array(),tivas=new Array(),enc=0,suma=0.00;   
   for (i=0;i<piva.length;i++){ 
     if (ivas.length>0){
        pos=i;
        enc=inArray(ivas,parseFloat(piva[i].value).toFixed(2));
        
        if (enc==-1){            
            pos=ivas.length;
            ivas[pos]=piva[i].value;
            tivas[pos]=tiva[i].value;            
        }else{
            pos=enc;
            var ant=parseFloat(tivas[enc]).toFixed(2);
            var nue=parseFloat(tiva[i].value).toFixed(2);
            if (isNaN(ant)){    ant=0.00;   }else{   ant=ant*1; }
            if (isNaN(nue)){    nue=0.00;   }else{   nue=nue*1; }            
            suma=parseFloat(ant+nue).toFixed(2);
            tivas[enc]=parseFloat(suma).toFixed(2);
        }
     }else{
        ivas[i]=piva[i].value;        
        tivas[i]=tiva[i].value;
     }
     ivas[pos]=parseFloat(ivas[pos]).toFixed(2);
     tivas[pos]=parseFloat(tivas[pos]).toFixed(2);
     
   }
   var ttiva=0;
   var ttsub=0;
   for (i=0;i<ivas.length;i++){
        ttiva=ttiva+tivas[i];
        ttsub=ttsub+
    
        alert("ID ["+i+"] Porcentaje: "+ivas[i]+"% Monto: "+tivas[i]); 
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

/*
function calcularFactura(){
		var stotalfact=0;
		$(":input[name^=st]").each(function (){
			stotalfact=parseFloat(parseFloat(stotalfact)+parseFloat($(this).val()) ).toFixed(2);
            
            					
		});
		var iva=(parseFloat($('#piva').val()/100))*stotalfact;		
		var totalfact=parseFloat(stotalfact)+parseFloat(iva);
		if (isNaN(stotalfact))	stotalfact=0.00;
		if (isNaN(iva))	iva=0.00; else iva.toFixed(2)
		if (isNaN(totalfact))	totalfact=0.00;	else totalfact.toFixed(2);
		$("#subtotal").val(parseFloat(stotalfact).toFixed(2));
		$('#iva').val(iva.toFixed(2));		
		$("#tfactura").val(parseFloat(totalfact).toFixed(2));	
}
*/