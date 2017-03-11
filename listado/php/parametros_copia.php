<?php
    //echo $tipolis;    
    switch($tipolis){
        case "lareas":
            $tabla  ="areas";
            $campos ="idareas,area";
            $orden  ="idareas asc";

	       #Configuracion de Tabla
	       $titulos   		="Id, Area";
	       $lis->ClaseCSS  ="listadoverde";
	       $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran
	

	       $lis->sizeTd	=array(40,550);
	       $lis->Aling   	=array("center","left");
	       $lis->setSizeTb(650);
	       if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
    
           #Configuración de Busqueda
	       $bcampo    		=DecodeCombo($_POST['campo']);
	       $bbuscar   		=$_POST['valor'];									
	       if (!empty($bbuscar)) $filtro=" ".$bcampo." like '%".$bbuscar."%'";
    
	       #Configuracion de los Iconos 
           $editar=array("URL","articulos.php","idarti,tipo","0,E","E","Editar Registro");
           $borrar=array("FUN","if (confirm(\'Desea Eliminar Este Articulo [Aceptar/Cancelar]\')) eliminar_ajax","'+clase+',id","articulos,0","D","Eliminar Articulo");
           $lis->Iconos=array($editar,$borrar);
        
           #Configuracion de paginación
           if (empty($maxpage)) $maxpage=30;	
	       $limit=array('0'=>1,'1'=>$maxpage);                            
        break;
        
    }

?>