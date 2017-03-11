<?php
    //echo $tipolis;    
    session_start();
    //print_r($_SESSION['usuario']);	
    switch($tipolis){  
        case "usuarios":
            $tabla  ="usuarios";
            $campos ="login,nombre, case nivel when 'A' then 'Administrador' when 'O' then 'Operador' when 'U' then 'Usuario' end";
            $orden  ="nombre asc";

	       #Configuracion de Tabla
	       $titulos   		="Login, Nombre, Nivel";
		   $lis->Columnas=array("login","Nombre","Nivel");
	       $lis->ClaseCSS  ="listadoverde";
	       $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran
	

	       $lis->sizeTd	=array(150,600,100);
	       $lis->Aling   	=array('center','center','center');
	       $lis->setSizeTb(890);
	       //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
    
           #Configuración de Busqueda
	       // if (!empty($_POST['valor'])){
	       $lis->LFiltro="";
	       //}
    
	       #Configuracion de los Iconos 
           $editar=array("URL","usuarios","login,tipoform","0,E","E","Editar Registro");
           $camcla=array("URL","camcla","login,tipoform","0,E","W","Cambiar Clave");
           $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,id","empleado,0","D","Eliminar Empleado");
           $lis->Iconos=array($editar,$camcla,$borrar);
        
           #Configuracion de paginación
           if (empty($maxpage)) $maxpage=30;	
	       $limit=array('0'=>1,'1'=>$maxpage);
        break;
        case "roles":
            $tabla  ="roles";
            $campos ="id,rol";
            $orden  ="rol asc";

	       #Configuracion de Tabla
	       $titulos   		="ID, ROL";
		   $lis->Columnas=array("id","rol");
	       $lis->ClaseCSS  ="listadoverde";
	       $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran
	

	       $lis->sizeTd	=array(180,700);
	       $lis->Aling   	=array('','center','center');
	       $lis->setSizeTb(890);
	       //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
    
           #Configuración de Busqueda
	       // if (!empty($_POST['valor'])){
	       $lis->LFiltro="";
	       //}
    
	       #Configuracion de los Iconos 
           $editar=array("URL","rol","id,tipoform","0,E","E","Editar Registro");
           $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,id","empleado,0","D","Eliminar Empleado");
           $lis->Iconos=array($editar,$borrar);
        
           #Configuracion de paginación
           if (empty($maxpage)) $maxpage=30;	
	       $limit=array('0'=>1,'1'=>$maxpage);
        break;    
        case "profesiones":
            $tabla  ="profesiones";
			$campos ="id,profesion";
            //$campos ="id,descripcion,ubicacion,case tipo	WHEN '1' then 'Almacen de Compras'	WHEN '2' then 'Almacen de Paso'	WHEN '3' then 'Sala de Ventas' end as tipo";
            
            //id,descripcion,ubicacion,case tipo	WHEN '1' then 'Almacen de Compras'	WHEN '2' then 'Almacen'	WHEN '3' then 'Sala de Ventas' end as tipo
            $orden  ="profesion desc";

	       #Configuracion de Tabla
	       $titulos   		="#ID, PROFESION";
		   $lis->Columnas=array("id","profesion");
	       $lis->ClaseCSS  ="listadoverde";
	       $lis->RutaImg="../listado/imagenes";
           $ids=array(3);   //Contiene los Indices de los Campos que no se mostraran
	

	       $lis->sizeTd	=array(200,650);
	       $lis->Aling   	=array('center','left');
	       $lis->setSizeTb(890);
	       //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
    
           #Configuración de Busqueda
	       // if (!empty($_POST['valor'])){
	       $lis->LFiltro="";
	       //}
    
	       #Configuracion de los Iconos 
           $editar=array("URL","profesion","id,tipoform","0,E","E","Editar Registro");
           $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,id","profesiones,0","D","Eliminar Profesion");
           $lis->Iconos=array($editar,$borrar);
        
           #Configuracion de paginación
           if (empty($maxpage)) $maxpage=30;	
	       $limit=array('0'=>1,'1'=>$maxpage);
        break;    
                   
        case "grados":
            $tabla  ="grados";
            $campos ="id,grado";
            $orden  ="grado asc";

	       #Configuracion de Tabla
	       $titulos   		="#ID, GRADO";
		   $lis->Columnas=array("id","grado");
	       $lis->ClaseCSS  ="listadoverde";
	       $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran
	

	       $lis->sizeTd	=array(200,650);
	       $lis->Aling   	=array('center','center');
	       $lis->setSizeTb(890);
	       //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
    
           #Configuración de Busqueda
	       // if (!empty($_POST['valor'])){
	       $lis->LFiltro="";
	       //}
    
	       #Configuracion de los Iconos 
           $editar=array("URL","grado","id,tipoform","0,E","E","Editar Registro");
           $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
           $lis->Iconos=array($editar,$borrar);
        
           #Configuracion de paginación
           if (empty($maxpage)) $maxpage=30;	
	       $limit=array('0'=>1,'1'=>$maxpage);
        break;
        
        case "personas":
            $tabla  ="personas";
            $campos ="cedula,concat(pnombre,' ',snombre), concat(papellido,' ',sapellido),date_format(fecnac,'%d/%m/%Y'),celular,telefono";
            $orden  ="cedula asc";

	       #Configuracion de Tabla
	       $titulos   		="#Cedula, Nombres, Apellidos, Fec Nac, Celular, Telefono";
		   $lis->Columnas=array("cedula","pnombre","papellido","fecnac","celular","telefono");
	       $lis->ClaseCSS  ="listadoverde";
	       $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran
	

	       $lis->sizeTd	=array(80,250,250,80,90,80);
	       $lis->Aling   	=array('center','center','center','center','center','center');
	       $lis->setSizeTb(890);
	       //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
    
           #Configuración de Busqueda
	       // if (!empty($_POST['valor'])){
	       $lis->LFiltro="";
	       //}
    
	       #Configuracion de los Iconos 
           $editar=array("URL","personas","cedula,tipoform","0,E","E","Editar Registro");
           $printe=array("URLN","residencia.php","cedula","0","P","Imprimir Constancia de Residencia");
           $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
           $lis->Iconos=array($editar,$printe,$borrar);
        
           #Configuracion de paginación
           if (empty($maxpage)) $maxpage=30;	
	       $limit=array('0'=>1,'1'=>$maxpage);
        break;
        
        case "tpreguntas":
            $tabla  ="tpreguntas";
            $campos ="id,tipo";
            $orden  ="tipo asc";

	       #Configuracion de Tabla
	       $titulos   		="#ID, Tipo";
		   $lis->Columnas=array("id","tipo");
	       $lis->ClaseCSS  ="listadoverde";
	       $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran
	

	       $lis->sizeTd	=array(200,650);
	       $lis->Aling   	=array('center','center');
	       $lis->setSizeTb(890);
	       //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
    
           #Configuración de Busqueda
	       // if (!empty($_POST['valor'])){
	       $lis->LFiltro="";
	       //}
    
	       #Configuracion de los Iconos 
           $editar=array("URL","tpreguntas","id,tipoform","0,E","E","Editar Registro");
           $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
           $lis->Iconos=array($editar,$borrar);
        
           #Configuracion de paginación
           if (empty($maxpage)) $maxpage=30;	
	       $limit=array('0'=>1,'1'=>$maxpage);
        break;
        
        case "preguntas":
            $tabla  ="preguntas";
            $campos ="id, pregunta,ftpregunta(tipo)";
            $orden  ="pregunta asc";

	       #Configuracion de Tabla
	       $titulos   		="#ID,Pregunta,Tipo";
		   $lis->Columnas=array("id","pregunta","tipo");
	       $lis->ClaseCSS  ="listadoverde";
	       $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran
	

	       $lis->sizeTd	=array(100,500,250);
	       $lis->Aling   	=array('center','center','center');
	       $lis->setSizeTb(890);
	       //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
    
           #Configuración de Busqueda
	       // if (!empty($_POST['valor'])){
	       $lis->LFiltro="";
	       //}
    
	       #Configuracion de los Iconos 
           $editar=array("URL","preguntas","id,tipoform","0,E","E","Editar Registro");
           $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
           $lis->Iconos=array($editar,$borrar);
        
           #Configuracion de paginación
           if (empty($maxpage)) $maxpage=30;	
	       $limit=array('0'=>1,'1'=>$maxpage);
        break;
        
        case "respuestas":
            $tabla  ="respuestasp";
            $campos ="id,fpregunta(pregunta),respuestap,case tipo_item when 'S' then 'Simple' when 'M' then 'Multiple' end, case tipo_respuesta when 'r' then 'Respuesta' when 'P' then 'Subpregunta' end";
            $orden  ="pregunta asc";

	       #Configuracion de Tabla
	       $titulos   		="#ID, Pregunta, Respuesta, Item, Tipo Resp";
		   $lis->Columnas=array("id","pregunta","respuestap","tipo_item","tipo_respuesta");
	       $lis->ClaseCSS  ="listadoverde";
	       $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran
	

	       $lis->sizeTd	=array(80,300,300,80,80);
	       $lis->Aling   	=array('center','center','center','center','center');
	       $lis->setSizeTb(890);
	       //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
    
           #Configuración de Busqueda
	       // if (!empty($_POST['valor'])){
	       $lis->LFiltro="";
	       //}
    
	       #Configuracion de los Iconos 
           $editar=array("URL","respuestas","id,tipoform","0,E","E","Editar Registro");
           $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
           $lis->Iconos=array($editar,$borrar);
        
           #Configuracion de paginación
           if (empty($maxpage)) $maxpage=30;	
	       $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "srespuestas":
            $tabla  ="respuestass";
            $campos ="id,frespuestap(respuestap),respuestas,case item when 'S' then 'Simple' when 'M' then 'Multiple' when 'T' then 'Campo de Texto' end";
            $orden  ="respuestap asc";

	       #Configuracion de Tabla
	       $titulos   		="#ID, Pregunta -> SubPregunta, Respuesta, Seleccion";
		   $lis->Columnas=array("id","respuestap","respuestas","item");
	       $lis->ClaseCSS  ="listadoverde";
	       $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran
	

	       $lis->sizeTd	=array(50,425,275,100);
	       $lis->Aling   	=array('center','center','center','center');
	       $lis->setSizeTb(890);
	       //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
    
           #Configuración de Busqueda
	       // if (!empty($_POST['valor'])){
	       $lis->LFiltro="";
	       //}
    
	       #Configuracion de los Iconos 
           $editar=array("URL","srespuestas","id,tipoform","0,E","E","Editar Registro");
           $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
           $lis->Iconos=array($editar,$borrar);
        
           #Configuracion de paginación
           if (empty($maxpage)) $maxpage=30;	
	       $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "planillas":
            $tabla  ="personas AS p INNER JOIN planillas AS pl ON p.planilla = pl.id";
            $campos ="pl.id,date_format(pl.fecha,'%d-%m-%Y'),pl.direccion,pl.telcasa,p.cedula,CONCAT(p.pnombre,' ',p.snombre,' ',p.papellido,' ',p.sapellido)";
            $orden  ="pl.id desc";

	       #Configuracion de Tabla
	       $titulos   		="#ID, Fecha, Direccion, Telefono, Cedula, Representante";
           $lis->Grupo="group by pl.id";
           $lis->Columnas=array("pl.id","pl.fecha","pl.direccion","pl.telcasa","p.cedula","p.pnombre");
	       $lis->ClaseCSS  ="listadoverde";
	       $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran
	

	       $lis->sizeTd	=array(50,70,300,80,60,240);
	       $lis->Aling   	=array('center','center','center','center','center','left');
	       $lis->setSizeTb(890);
	       //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
    
           #Configuración de Busqueda
	       // if (!empty($_POST['valor'])){
	       $lis->LFiltro=" p.tipo='R'";
	       //}
    
	       #Configuracion de los Iconos 
           $editar=array("URL","familia","id,tipoform","0,E","E","Editar Registro");
           $planilla=array("URL","planilla","id,tipoform","0,E","E","Editar Estudio Demografico y Socieconomico");
           $printe=array("URLN","familiap.php","id","0","P","Imprimir Estudio Demografico y Socioeconomico");
           $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
           $lis->Iconos=array($planilla,$printe,$editar,$borrar);
        
           #Configuracion de paginación
           if (empty($maxpage)) $maxpage=30;	
	       $limit=array('0'=>1,'1'=>$maxpage);
        break;
        
        case "lproductos":
            $tabla  ="productos";
            $campos ="id,descripcion";
            $orden  ="descripcion asc";

	       #Configuracion de Tabla
	       $titulos   		="#ID, Producto";
	       $lis->ClaseCSS  ="listadoverde";
	       $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran
	

	       $lis->sizeTd	=array(50,270,90,90,90,90);
	       $lis->Aling   	=array('center','left','center','center');
	       $lis->setSizeTb(720);
	       //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
    
           #Configuración de Busqueda
	       // if (!empty($_POST['valor'])){
	       $lis->LFiltro=" estado='A' ";
	       //}
    
	       #Configuracion de los Iconos 
           $editar=array("URL","producto","codigo,tipof","0,E","E","Editar Registro");
           $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
           $lis->Iconos=array($editar,$borrar);
        
           #Configuracion de paginación
           if (empty($maxpage)) $maxpage=30;	
	       $limit=array('0'=>1,'1'=>$maxpage);
        break;
        
        case "lproveedores":
            $tabla  ="proveedores";
            $campos ="rif,proveedor,cedrep,nomrep,telrep";
            $orden  ="rif asc";

	       #Configuracion de Tabla
	       $titulos   		="#Rif, Proveedor, Cedula, Representante, Telf. Rep.";
	       $lis->ClaseCSS  ="listadoverde";
	       $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran
	

	       $lis->sizeTd	=array(80,280,70,160,90);
	       $lis->Aling   	=array('center','left','center','left','center','center');
	       $lis->setSizeTb(720);
	       //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
    
           #Configuración de Busqueda
	       // if (!empty($_POST['valor'])){
	       $lis->LFiltro=" estado='A' ";
	       //}
    
	       #Configuracion de los Iconos 
           $editar=array("URL","proveedor","rif,tipof","0,E","E","Editar Registro");
           $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
           $lis->Iconos=array($editar,$borrar);
        
           #Configuracion de paginación
           if (empty($maxpage)) $maxpage=30;	
	       $limit=array('0'=>1,'1'=>$maxpage);
        break;
    }

?>