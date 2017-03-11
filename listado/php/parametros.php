<?php
    session_start();
    switch ($tipolis) {
        case "usuarios":
            $tabla  ="personas p INNER JOIN usuarios u on u.psa_cedula=p.cedula";
            $campos ="p.cedula,p.nombres,p.apellidos,u.login,case u.nivel when 'A' then 'Administrador' when 'O' then 'Operador' when 'U' then 'Usuario' end";
            $orden  ="cedula asc";

           #Configuracion de Tabla
           $titulos        ="Cedula, Nombres, Apellidos, Login, Nivel";
           $lis->Columnas=array("p.cedula","p.nombres","p.apellidos","u.login","u.nivel");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array(4);   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(125,200,200,200,125);
           $lis->Aling    =array('center','center','center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro="";
           //}

           #Configuracion de los Iconos
           if (($_SESSION['usuario']['nivel']=='A')) {
               $editar=array("URL","usuarios","id,tipoform","0,E","E","Editar Registro");
               $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,id","empleado,0","D","Eliminar Empleado");
               $lis->Iconos=array($editar,$borrar);
           }


           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;


        case "areas":
            $tabla  ="areas";
            $campos ="id,area";
            $orden  ="id desc";

           #Configuracion de Tabla
           $titulos        ="#ID, Area";
           $lis->Columnas=array("id","area");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(200,590);
           $lis->Aling    =array('center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro="";
           //}

           #Configuracion de los Iconos
           //if (($_SESSION['usuario']['nivel']=='O') ||  ($_SESSION['usuario']['nivel']=='A')){
                $editar=array("URL","areas","id,tipoform","0,E","E","Editar Registro");
                //$banner=array("URL","bannareas","id,tipoform","0,E","I","Modificar Imagenes del Banner");
                $borrar=array("URL","eliminar","id,tipo,tipoform","0,Areas,D","D","Eliminar Registro");
                    $addart=array("URL","articulos","area,tipo,tipoform","0,Areas,N","N","Agregar Articulo");
                $articu=array("URL","listados","area,tipolis,titulo","0,AArticulos,Listado_de_Articulos","C","Ver Listado de Articulos");
                //$borrar=array("URL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
                $lis->Iconos=array($addart,$articu,$editar,$borrar);
           //}


           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;
        case "monedas":
            $tabla  ="monedas as m inner join monedas as n on n.id=m.monedabase";
            $campos ="m.id,m.moneda,concat('1 ',n.moneda,'=',m.cambio,' ',m.moneda)";
            $orden  ="m.id asc";

           #Configuracion de Tabla
           $titulos        ="#ID, Moneda, Tasa";
           $lis->Columnas=array("m.id","m.moneda","cambio");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(250,300,300);
           $lis->Aling    =array('center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro="";
           //}

           #Configuracion de los Iconos
           //if (($_SESSION['usuario']['nivel']=='O') ||  ($_SESSION['usuario']['nivel']=='A')){
            $editar=array("URL","monedas","id,tipoform","0,E","E","Editar Registro");
            $borrar=array("URL","eliminar","id,tipo,tipoform","0,Areas,D","D","Eliminar Registro");
            $lis->Iconos=array($editar,$borrar);
           //}


           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "paises":
            $tabla  ="paises";
            $campos ="id,pais";
            $orden  ="id asc";

           #Configuracion de Tabla
           $titulos        ="#ID, Pais";
           $lis->Columnas=array("id","pais");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(250,600);
           $lis->Aling    =array('center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro="";
           //}

           #Configuracion de los Iconos
           //if (($_SESSION['usuario']['nivel']=='O') ||  ($_SESSION['usuario']['nivel']=='A')){
            $editar=array("URL","paises","id,tipoform","0,E","E","Editar Registro");
            $borrar=array("URL","eliminar","id,tipo,tipoform","0,Areas,D","D","Eliminar Registro");
            $lis->Iconos=array($editar,$borrar);
           //}


           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;
        case "estados":
            $tabla  ="estados as e inner join paises as p on p.id=e.pais";
            $campos ="e.id,p.pais, e.estado";
            $orden  ="e.pais asc";

           #Configuracion de Tabla
           $titulos        ="#ID, Pais, Estado";
           $lis->Columnas=array("id","area","Estado");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(250,350,250);
           $lis->Aling    =array('center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro="";
           //}

           #Configuracion de los Iconos
           //if (($_SESSION['usuario']['nivel']=='O') ||  ($_SESSION['usuario']['nivel']=='A')){
            $editar=array("URL","estados","id,tipoform","0,E","E","Editar Registro");
            $borrar=array("URL","eliminar","id,tipo,tipoform","0,Areas,D","D","Eliminar Registro");
            $lis->Iconos=array($editar,$borrar);
           //}


           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "categorias":
            $tabla  ="categorias";
            $campos ="id,categoria";
            $orden  ="id desc";

           #Configuracion de Tabla
           $titulos        ="#ID, Categoria";
           $lis->Columnas=array("id","categoria");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(200,590);
           $lis->Aling    =array('center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro="";
           //}

           #Configuracion de los Iconos
           //if (($_SESSION['usuario']['nivel']=='O') ||  ($_SESSION['usuario']['nivel']=='A')){
                $editar=array("URL","categoria","id,tipoform","0,E","E","Editar Registro");
                $borrar=array("URL","eliminar","id,tipo,tipoform","0,Areas,D","D","Eliminar Registro");
                //$borrar=array("URL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
                $lis->Iconos=array($editar,$borrar);
           //}
           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "bancos":
            $tabla  ="bancos as b left join paises as p on p.id=b.pais";
            $campos ="b.id,p.pais,b.banco";
            $orden  ="b.id desc";

           #Configuracion de Tabla
           $titulos        ="#ID, Pais, Banco";
           $lis->Columnas=array("id","pais","banco");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(200,325,325);
           $lis->Aling    =array('center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro="";
           //}

           #Configuracion de los Iconos
           //if (($_SESSION['usuario']['nivel']=='O') ||  ($_SESSION['usuario']['nivel']=='A')){
                $editar=array("URL","bancos","id,tipoform","0,E","E","Editar Banco");
                $borrar=array("URL","eliminar","id,tipo,tipoform","0,Areas,D","D","Eliminar Registro");
                //$borrar=array("URL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
                $lis->Iconos=array($editar,$borrar);
           //}
           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "cuentas":
            $tabla  ="cuentas as c inner join bancos as b on b.id=c.banco";
            $campos ="c.id,b.banco,c.cuenta,case c.tipo when 'A' then 'Ahorro' when 'C' then 'Corriente' end";
            $orden  ="b.id asc";

           #Configuracion de Tabla
           $titulos        ="#ID, Banco, Cuenta, Pais";
            $lis->Columnas=array("c.id","b.banco","c.cuenta","c.tipo");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array(0);   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(0,325,325,200);
           $lis->Aling    =array('center','center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array(0);  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro=" c.estado='A' and cliente is null";
           //}

           #Configuracion de los Iconos
           //if (($_SESSION['usuario']['nivel']=='O') ||  ($_SESSION['usuario']['nivel']=='A')){
                $editar=array("URL","cuentas","id,tipoform","0,E","E","Editar Cuenta");
                $borrar=array("URL","eliminar","id,tipo,tipoform","0,Areas,D","D","Eliminar Registro");
                //$borrar=array("URL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
                $lis->Iconos=array($editar,$borrar);
           //}
           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "banners":
            $tabla  ="banners as b inner join areas as a on a.id=b.idarea left join articulos as ar on (ar.area=b.idarea and ar.id=b.posicion)";
            $campos ="b.id,a.area,if (ISNULL(b.posicion),'Banner Principal',concat('Despues del Articulo ',ar.titulo)) posicion,b.ancho,b.alto,b.cantidad";
            $orden  ="b.idarea asc";

           #Configuracion de Tabla
           $titulos        ="#ID, Area, Posicion, Ancho, Alto, Cantidad";
           $lis->Columnas=array("b.id","a.area","b.posicion","b.ancho","b.alto","b.cantidad");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(50,130,430,80,80,80);
           $lis->Aling    =array('center','center','center','center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro="";
           //}

           #Configuracion de los Iconos
           //if (($_SESSION['usuario']['nivel']=='O') ||  ($_SESSION['usuario']['nivel']=='A')){
                $editar=array("URL","banner","id,tipoform","0,E","E","Editar Registro");
                //$banner=array("URL","bannareas","id,tipoform","0,E","I","Modificar Imagenes del Banner");
                //$borrar=array("URL","eliminar","id,tipo,tipoform","0,Areas,D","D","Eliminar Registro");
                //$addart=array("URL","articulos","area,tipo,tipoform","0,Areas,N","N","Agregar Articulo");
                $verplan=array("URL","listados","banner,tipolis,titulo","0,PubBanners,Listado_DE_Planes","C","Ver Planes");
                $borrar=array("URL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
                $lis->Iconos=array($editar,$verplan,$borrar);
           //}


           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "publicaciones":
            $tabla  ="publicaciones as pu";
            $campos ="pu.id,pu.dias,pu.costo,pu.foto,if (pu.foto is null,'Banner','Clasificado')";
            $orden  ="pu.id asc";

           #Configuracion de Tabla
           $titulos        ="#ID, Dias, Costo, Foto, Tipo";
           $lis->Columnas=array("pu.id","pu.dias","pu.costo","pu.foto","Tipo");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array(4,5,6,7,8);   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(150,150,350,125,125);
           $lis->Aling    =array('center','center','center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro=" isnull(pu.tipo) ";
           //}

           #Configuracion de los Iconos
           //if (($_SESSION['usuario']['nivel']=='O') ||  ($_SESSION['usuario']['nivel']=='A')){
                $editar=array("URL","publicaciones","id,tipoform","0,E","E","Editar Registro");
                $borrar=array("URL","if (confirm('Desea Eliminar Este Plan [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
                $lis->Iconos=array($editar,$borrar);

           //}
           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "PubBanners":
            $tabla  ="publicaciones as pu";
            $campos ="pu.id,pu.dias,pu.costo";
            $orden  ="pu.id asc";

           #Configuracion de Tabla
           $titulos        ="#ID, Dias, Costo";
           $lis->Columnas=array("pu.id","pu.dias","pu.costo");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran

           $lis->Pagina=false;
           $lis->Busca=false;
           $lis->Morden=false;
           $lis->sizeTd    =array(250,350,350);
           $lis->Aling    =array('center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro=" tipo='".(int) $_GET['banner']."'";
           //}

           #Configuracion de los Iconos
           //if (($_SESSION['usuario']['nivel']=='O') ||  ($_SESSION['usuario']['nivel']=='A')){
                $editar=array("URL","publicaciones","id,tipoform","0,E","E","Editar Registro");
                $borrar=array("URL","if (confirm('Desea Eliminar Este Plan [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
                $lis->Iconos=array($editar,$borrar);
           //}
           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "BannersInactivos":
            $tabla  ="detabanner as d inner join publicaciones as p on p.id=d.idplan inner join banners as b on b.id=d.idbanner 	inner join areas as a on a.id=b.idarea left join articulos as ar on b.posicion=ar.id";
            $campos ="d.id,a.area,if (isnull(b.posicion),'Banner Principal',concat('Despues de ',ar.titulo)) posicion,p.dias";
            $orden  ="d.id desc";

           #Configuracion de Tabla
           $titulos        ="#ID, Area, Posicion, Dias";
            $lis->Columnas=array("d.id","a.area","posicion","p.dias");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array(2,3,4,5,6,7,8,9,10);   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(50,100,650,50);
           $lis->Aling    =array('center','center','center','center');
           $lis->setSizeTb(890);
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           $lis->LFiltro=" d.estado='I'";

           #Configuracion de los Iconos
           //if (($_SESSION['usuario']['nivel']=='O') ||  ($_SESSION['usuario']['nivel']=='A')){
                $activar=array("URL","abanner","id,tipoform","0,E","C","Activar Banner");
                $editar= array("URL","ebanner","id,tipoform","0,E","E","Editar Banner");
                $eliminar=array("URL","banner","id,tipoform","0,E","D","Eliminar Registro");
                $lis->Iconos=array($activar,$editar,$eliminar);
           //}
           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "BannersInactivos":
            $tabla  ="detabanner as d inner join publicaciones as p on p.id=d.idplan inner join banners as b on b.id=d.idbanner 	inner join areas as a on a.id=b.idarea left join articulos as ar on b.posicion=ar.id";
            $campos ="d.id,a.area,if (isnull(b.posicion),'Banner Principal',concat('Despues de ',ar.titulo)) posicion,p.dias";
            $orden  ="d.id desc";

           #Configuracion de Tabla
           $titulos        ="#ID, Area, Posicion, Dias";
            $lis->Columnas=array("d.id","a.area","posicion","p.dias");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array(2,3,4,5,6,7,8,9,10);   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(50,100,650,50);
           $lis->Aling    =array('center','center','center','center');
           $lis->setSizeTb(890);
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           $lis->LFiltro=" d.estado='I'";

           #Configuracion de los Iconos
           //if (($_SESSION['usuario']['nivel']=='O') ||  ($_SESSION['usuario']['nivel']=='A')){
                $activar=array("URL","abanner","id,tipoform","0,E","C","Activar Banner");
                $editar= array("URL","ebanner","id,tipoform","0,E","E","Editar Banner");
                $eliminar=array("URL","banner","id,tipoform","0,E","D","Eliminar Registro");
                $lis->Iconos=array($activar,$editar,$eliminar);
           //}
           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "EliminarDepositos":
            $tabla  ="movimientos a inner join clientes b on a.cliente=b.cedula";
            $campos ="a.id,a.referencia,b.cedula,b.nombre,b.apellido,a.fecha,a.monto_oficial,a.monto_base";
            $orden  ="a.id desc";

           #Configuracion de Tabla
           $titulos        ="#ID, Referencia, Cedula, Nombre, Apellido, Fecha, Monto_Oficial, Monto_Base";
           $lis->Columnas=array("a.id","a.referencia","b.cedula","b.nombre","b.apellido","a.fecha","a.monto_oficial","a.monto_base");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array(2,3,4,5,6,7,8,9,10);   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(90,90,90,140,140,100,100,100);
           $lis->Aling    =array('center','center','center','center','center','center','center','center','center','center');
           $lis->setSizeTb(890);
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           $lis->LFiltro=" a.estado='N'";

           #Configuracion de los Iconos
          $borrar=array("DEL","if (confirm('Desea Eliminar Este Deposito [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,id","movimientos,0","D","Eliminar Deposito");
          $lis->Iconos=array($borrar);
          #Configuracion de paginaci�n
          if (empty($maxpage)) {
              $maxpage=30;
          }
            $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "ClasificadosInactivos":
            $tabla  ="clasificados as c inner join publicaciones as p on p.id=c.dias";
            $campos ="c.id,c.titulo,c.descripcion,c.fregistro";
            $orden  ="c.fregistro desc";

            #Configuracion de Tabla
            $titulos        ="#ID, Titulo, Descripcion, Fec Registro";
            $lis->Columnas=array("c.id","c.titulo","c.descripcion","c.fregistro");
            $lis->ClaseCSS  ="listadoverde";
            $lis->RutaImg="../listado/imagenes";
            $ids=array(4);   //Contiene los Indices de los Campos que no se mostraran


            $lis->sizeTd    =array(50,350,350,100);
            $lis->Aling    =array('center','center','center','center','center','center');
            $lis->setSizeTb(890);
            //if (!empty($_POST['noLabel'])) $lis->NoLabel=explode(",",trim($_POST['noLabel']));
            //else $lis->NoLabel=array();
            $lis->NoLabel=array(4);  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

            #Configuraci�n de Busqueda
            $lis->LFiltro=" c.estado='I'";

            #Configuracion de los Iconos
            //if (($_SESSION['usuario']['nivel']=='O') ||  ($_SESSION['usuario']['nivel']=='A')){
            $activar=array("URL","aclasificado","id,tipoform","0,E","C","Activar Clasificado");
            $editar= array("URL","eclasificado","id,tipoform","0,E","E","Editar Clasificado");
            $eliminar=array("URL","banner","id,tipoform","0,E","D","Editar Registro");
            $lis->Iconos=array($activar,$editar,$eliminar);
            //}
            #Configuracion de paginaci�n
            if (empty($maxpage)) {
                $maxpage=30;
            }
            $limit=array('0'=>1,'1'=>$maxpage);
            break;

        case "ClasificadosCliente":
            $tabla  ="clasificados as c inner join publicaciones as p on p.id=c.dias";
            $campos ="c.id,c.titulo,c.factivo,date_add(c.factivo,interval p.dias day) hasta,p.dias,c.estado";
            $orden  ="hasta desc";

            #Configuracion de Tabla
            $titulos        ="#ID, Titulo, Desde, Hasta, Dias, Estado";
            $lis->Columnas=array("c.id","c.titulo","c.factivo","hasta","p.dias","c.estado");
            $lis->ClaseCSS  ="listadoverde";
            $lis->RutaImg="../listado/imagenes";
            $ids=array(3,4,5,6);   //Contiene los Indices de los Campos que no se mostraran

            $lis->sizeTd    =array(50,400,100,100,100,100);
            $lis->Aling    =array('center','center','center','center','center','center');
            $lis->setSizeTb(890);
            //if (!empty($_POST['noLabel'])){
            //  $lis->NoLabel=explode(",",trim($_POST['noLabel']));
            //} else {
            //  $lis->NoLabel=array()
            //};
            //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

            #Configuraci�n de Busqueda
            $lis->LFiltro="";
            $lis->LFiltro=" c.cliente='{$_SESSION['cliente']['cedula']}'";
            #Configuracion de los Iconos
            $editar=array("URLN","verclasificado.php","id,search","0,false","C","Editar Clasificado");
            $eliminar=array("DEL","if (confirm('Desea Eliminar Este Clasificado [Aceptar/Cancelar]')) borrarRegistro",
                "$lis->ClaseCSS,id","clasificados,0","D","Eliminar Clasificado");
            //$eliminar=array("URL","banner","id,tipoform","0,E","D","Editar Registro");
            $lis->Iconos=array($editar, $eliminar);
            #Configuracion de paginaci�n
            if (empty($maxpage)) {
                $maxpage=30;
            }
            $limit=array('0'=>1,'1'=>$maxpage);
            break;

        case "ClasificadosActivos":
            $tabla  ="clasificados as c inner join publicaciones as p on p.id=c.dias";
            $campos ="c.id,c.titulo,c.factivo,date_add(c.factivo,interval p.dias day) hasta,p.dias,c.estado";
            $orden  ="hasta desc";

            #Configuracion de Tabla
            $titulos        ="#ID, Titulo, Desde, Hasta, Dias, Estado";
            $lis->Columnas=array("c.id","c.titulo","c.factivo","hasta","p.dias","c.estado");
            $lis->ClaseCSS  ="listadoverde";
            $lis->RutaImg="../listado/imagenes";
            $ids=array(3,4,5,6);   //Contiene los Indices de los Campos que no se mostraran

            $lis->sizeTd    =array(50,400,100,100,100,100);
            $lis->Aling    =array('center','center','center','center','center','center');
            $lis->setSizeTb(890);
            //if (!empty($_POST['noLabel'])){
            //  $lis->NoLabel=explode(",",trim($_POST['noLabel']));
            //} else {
            //  $lis->NoLabel=array()
            //};
            //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

            #Configuraci�n de Busqueda
            $lis->LFiltro=" date_add(c.factivo, interval p.dias day)>=curdate()";
            #Configuracion de los Iconos
            $editar= array("URL","eclasificado","id,tipoform","0,E","E","Editar Clasificado");
            //$editar=array("URLN","verclasificado.php","id,search","0,false","C","Editar Clasificado");
            $eliminar=array("DEL","if (confirm('Desea Eliminar Este Clasificado [Aceptar/Cancelar]')) borrarRegistro",
                "$lis->ClaseCSS,id","clasificados,0","D","Eliminar Clasificado");
            //$eliminar=array("URL","banner","id,tipoform","0,E","D","Editar Registro");
            $lis->Iconos=array($editar, $eliminar);
            #Configuracion de paginaci�n
            if (empty($maxpage)) {
                $maxpage=30;
            }
            $limit=array('0'=>1,'1'=>$maxpage);
            break;

        case "ClasificadosVencidos":
            $tabla  ="clasificados as c inner join publicaciones as p on p.id=c.dias";
            $campos ="c.id,c.titulo,c.factivo,date_add(c.factivo,interval p.dias day) hasta,p.dias,c.estado";
            $orden  ="hasta desc";

            #Configuracion de Tabla
            $titulos        ="#ID, Titulo, Desde, Hasta, Dias, Estado";
            $lis->Columnas=array("c.id","c.titulo","c.factivo","hasta","p.dias","c.estado");
            $lis->ClaseCSS  ="listadoverde";
            $lis->RutaImg="../listado/imagenes";
            $ids=array(3,4,5,6);   //Contiene los Indices de los Campos que no se mostraran

            $lis->sizeTd    =array(50,400,100,100,100,100);
            $lis->Aling    =array('center','center','center','center','center','center');
            $lis->setSizeTb(890);
            //if (!empty($_POST['noLabel'])){
            //  $lis->NoLabel=explode(",",trim($_POST['noLabel']));
            //} else {
            //  $lis->NoLabel=array()
            //};
            //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

            #Configuraci�n de Busqueda
            $lis->LFiltro=" date_add(c.factivo, interval p.dias day)<curdate()";
            #Configuracion de los Iconos
            $eliminar=array("DEL","if (confirm('Desea Eliminar Este Clasificado [Aceptar/Cancelar]')) borrarRegistro",
                "$lis->ClaseCSS,id","clasificados,0","D","Eliminar Clasificado");
            //$eliminar=array("URL","banner","id,tipoform","0,E","D","Editar Registro");
            $lis->Iconos=array($eliminar);
            #Configuracion de paginaci�n
            if (empty($maxpage)) {
                $maxpage=30;
            }
            $limit=array('0'=>1,'1'=>$maxpage);
            break;

        case "AArticulos":
            $tabla  ="articulos";
            $campos ="id,area,orden,tmenu,titulo,fmodificacion";
            $orden  ="orden asc";

            #Configuracion de Tabla
            $titulos        ="#ID, Area, Orden,SubMenu, Titulo, F Modifica";
            $lis->Columnas=array("id","area","orden","tmenu","titulo","fmodificacion");
            $lis->ClaseCSS  ="listadoverde";
            $lis->RutaImg="../listado/imagenes";
            $lis->Morden=false;
            $lis->Busca=false;
            $lis->Pagina=false;
            $ids=array(1);   //Contiene los Indices de los Campos que no se mostraran

            $lis->sizeTd    =array(60,0,60,260,380,90);
            $lis->Aling    =array('center','','center','center','center');
            $lis->setSizeTb(890);
            //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
            $lis->NoLabel=array(1);  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

            #Configuraci�n de Busqueda
            // if (!empty($_POST['valor'])){
            $lis->LFiltro=" area='".$_GET['area']."'";
            //}

            #Configuracion de los Iconos
            if ($_SESSION['usuario']['nivel']!="U") {
                $editar=array("URL","articulos","area,id,tipoform","1,0,E","E","Editar Registro");
                $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,id","articulos,0","D","Eliminar Articulo");
                $lis->Iconos=array($editar,$borrar);
            }


            #Configuracion de paginaci�n
            if (empty($maxpage)) {
                $maxpage=30;
            }
            $limit=array('0'=>1,'1'=>$maxpage);
            break;

        case "poractivar":
            $tabla  ="clientes as c inner join movimientos as m on c.cedula=m.cliente";
            $campos ="c.cedula,c.nombre,c.apellido,c.fupdate,c.minimoap,sum(m.monto_base)";
            $orden  ="c.fupdate desc";

            #Configuracion de Tabla
            $titulos        ="#ID, Nombre, Apellido, Actualizacion, Monto AP, Monto Depositado";
            $lis->Columnas=array("cedula","nombre","apellido","fupdate","minimoap","sum(m.monto_base)");
            $lis->ClaseCSS  ="listadoverde";
            $lis->RutaImg="../listado/imagenes";
            $ids=array();   //Contiene los Indices de los Campos que no se mostraran


            $lis->sizeTd    =array(100,175,175,100,125,150);
            $lis->Aling    =array('center','center','center','center','center','center');
            $lis->setSizeTb(890);
            //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
            $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
            $lis->Group=" group by c.cedula";

            #Configuraci�n de Busqueda
            // if (!empty($_POST['valor'])){
            $lis->LFiltro=" m.estado='N'";
            //}

            #Configuracion de los Iconos
            if ($_SESSION['usuario']['nivel']!="U") {
                $editar=array("URL","depositos","cedula,clase,tipoform","0,P,E","C","Ver Depositos");
                $lis->Iconos=array($editar);
            }


            #Configuracion de paginaci�n
            if (empty($maxpage)) {
                $maxpage=30;
            }
            $limit=array('0'=>1,'1'=>$maxpage);
            break;

        case "porretirar":
            $tabla  ="clientes as c inner join movimientos as m on c.cedula=m.cliente";
            $campos ="c.cedula,c.nombre,c.apellido,sum(m.monto_oficial),sum(m.monto_base)";
            $orden  ="c.fupdate desc";

           #Configuracion de Tabla
           $titulos        ="#ID, Nombre, Apellido, Monto Total en Moneda Local, Monto Total en Moneda Base";
           $lis->Columnas=array("cedula","nombre","apellido","minimoap","sum(m.monto_oficial)","sum(m.monto_base)");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(100,175,175,200,200);
           $lis->Aling    =array('center','center','center','center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
          $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
          $lis->Group=" group by c.cedula";

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro=" m.estado='P' and m.franquicia='FCG'";
           //}

           #Configuracion de los Iconos
           if ($_SESSION['usuario']['nivel']!="U") {
               $editar=array("URL","porretirar","cedula,clase,tipoform","0,P,E","C","Ver Depositos");
               $lis->Iconos=array($editar);
           }


           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "porrecapi":
            $tabla  ="clientes as c inner join movimientos as m on c.cedula=m.cliente";
            $campos ="c.cedula,c.nombre,c.apellido,sum(m.monto_oficial),sum(m.monto_base)";
            $orden  ="c.fupdate desc";

           #Configuracion de Tabla
           $titulos        ="#ID, Nombre, Apellido, Monto Total en Moneda Local, Monto Total en Moneda Base";
           $lis->Columnas=array("cedula","nombre","apellido","minimoap","sum(m.monto_oficial)","sum(m.monto_base)");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(100,175,175,200,200);
           $lis->Aling    =array('center','center','center','center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
          $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
          $lis->Group=" group by c.cedula";

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro=" m.estado='R' and m.franquicia='REC'";
           //}

           #Configuracion de los Iconos
           if ($_SESSION['usuario']['nivel']!="U") {
               $editar=array("URL","porrecapi","cedula,clase,tipoform","0,P,E","C","Ver Depositos");
               $lis->Iconos=array($editar);
           }


           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "porrenovar":
            $tabla  ="clientes as c inner join movimientos as m on c.cedula=m.cliente";
            $campos ="c.cedula,c.nombre,c.apellido,sum(m.monto_oficial),sum(m.monto_base)";
            $orden  ="c.fupdate desc";

           #Configuracion de Tabla
           $titulos        ="#ID, Nombre, Apellido, Monto Total en Moneda Local, Monto Total en Moneda Base";
           $lis->Columnas=array("cedula","nombre","apellido","minimoap","sum(m.monto_oficial)","sum(m.monto_base)");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(100,175,175,200,200);
           $lis->Aling    =array('center','center','center','center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
          $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara
          $lis->Group=" group by c.cedula";

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro=" m.estado='V' and m.franquicia='REN'";
           //}

           #Configuracion de los Iconos
           if ($_SESSION['usuario']['nivel']!="U") {
               $editar=array("URL","porrenova","cedula,clase,tipoform","0,P,E","C","Ver Depositos");
               $lis->Iconos=array($editar);
           }


           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "editoriales":
            $tabla  ="editoriales";
            $campos ="id,editorial";
            $orden  ="id desc";

           #Configuracion de Tabla
           $titulos        ="#ID, Editoriales";
           $lis->Columnas=array("id","editorial");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(200,650);
           $lis->Aling    =array('center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro="";
           //}

           #Configuracion de los Iconos
           if ($_SESSION['usuario']['nivel']!="U") {
               $editar=array("URL","editoriales","id,tipoform","0,E","E","Editar Registro");
               $printe=array("URLN","residencia.php","cedula","0","P","Imprimir Constancia de Residencia");
               $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
               $lis->Iconos=array($editar,$printe,$borrar);
           }


           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "fuentes":
            $tabla  ="fuentes";
            $campos ="id,fuente";
            $orden  ="id desc";

           #Configuracion de Tabla
           $titulos        ="#ID, Fuentes";
           $lis->Columnas=array("id","fuente");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(200,650);
           $lis->Aling    =array('center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro="";
           //}

           #Configuracion de los Iconos
           if ($_SESSION['usuario']['nivel']!="U") {
               $editar=array("URL","fuentes","id,tipoform","0,E","E","Editar Registro");
               $borrar=array("DEL","if (confirm('Desea Eliminar Este Articulo [Aceptar/Cancelar]')) borrarRegistro","$lis->ClaseCSS,cedula","empleado,0","D","Eliminar Empleado");
               $lis->Iconos=array($editar,$printe,$borrar);
           }


           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "libros":
            $tabla  ="libros l INNER JOIN editoriales e on e.id=l.eda_id INNER JOIN areas a on a.id=l.ara_id";
            $campos ="l.id,l.cota,l.titulo,l.edicion,a.area,(SELECT GROUP_CONCAT(a.autor) from autores a INNER JOIN autores_libros al on al.atr_id=a.id where al.lbo_id=l.id)";
            $orden  ="l.titulo asc";

           #Configuracion de Tabla
           $titulos        ="#ID, Cota,Titulo, Edicion,Area,Autores";
           $lis->Columnas=array("l.id","l.cota","l.titulo","l.edicion","a.area","Autores");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(0,130,300,80,170,170);
           $lis->Aling    =array('center','center','center','center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array(0);  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro="";
           //}

           #Configuracion de los Iconos
           if ($_SESSION['usuario']['nivel']!="U") {
               $editar=array("URL","libros","id,tipoform","0,E","E","Editar Registro");
               $borrae=array("URL","librosd","id,tipo,tipoform","0,Libros,B","D","Desincorporar Ejemplar");
               $agrega=array("URL","librosi","id,tipo,tipoform","0,Libros,A","N","Incorporar Ejemplar");
               $reinco=array("URL","librosr","id,tipo,tipoform","0,Libros,R","U","Reincorporar Ejemplar");
                //$borrae=array("URL","librosd","id,tipoform","0,E","D","Desincorporar Ejemplar");
                //$agrega=array("URL","librosi","id,tipoform","0,E","N","Agregar Libro");
                //$reinco=array("URL","librosr","id,tipoform","0,E","U","Reincorporar Libro");
                $borrar=array("URL","eliminar","id,tipo,tipoform","0,Libros,D","D","Eliminar Libro y Sus Ejemplares");
               $observ=array("URL","consultar","id,tipo,tipoform","0,Libros,C","C","Ver Libro y Detalles");
               $lis->Iconos=array($editar,$borrae,$agrega,$reinco,$observ,$borrar);
           }


           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "danados":
            $tabla  ="libros as l INNER JOIN ejemplares e on e.libro=l.id";
            $campos ="l.id,l.cota,e.ejemplar,l.titulo,e.obs";
            $orden  ="l.titulo asc";

           #Configuracion de Tabla
           $titulos        ="#ID, Cota, E, Titulo, Observacion";
           $lis->Columnas=array("l.id","l.cota","e.ejemplar","l.titulo","e.obs");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array(0,2);   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(0,140,30,360,360);
           $lis->Aling    =array('center','center','center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array(0);  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           $lis->LFiltro=" e.estado='D'";

           $lis->Iconos=array();

           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "perdidos":
            $tabla  ="libros as l INNER JOIN ejemplares e on e.libro=l.id";
            $campos ="l.id,l.cota,e.ejemplar,l.titulo,e.obs";
            $orden  ="l.titulo asc";

           #Configuracion de Tabla
           $titulos        ="#ID, Cota, E, Titulo, Observacion";
           $lis->Columnas=array("l.id","l.cota","e.ejemplar","l.titulo","e.obs");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(0,140,30,360,360);
           $lis->Aling    =array('center','center','center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array(0);  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           $lis->LFiltro=" e.estado='O'";

           $lis->Iconos=array();

           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "prestados":
            $tabla  ="ejemplares e INNER JOIN libros l on l.id=e.libro INNER JOIN deta_prestamos dp on (dp.libro=l.id and dp.ejemplar=e.ejemplar) INNER JOIN prestamos po on po.id=dp.prestamo INNER JOIN personas p on p.cedula=po.psa_estudiante ";
            $campos ="l.id,l.cota,e.ejemplar,l.titulo,po.fechapre,p.nombres,p.apellidos";
            $orden  ="po.fechapre asc";

           #Configuracion de Tabla
           $titulos        ="#ID, Cota, E, Titulo,F Prestamo, Nombres, Apellidos";
           $lis->Columnas=array("l.id","l.cota","e.ejemplar","l.titulo","po.fechapre","p.nombres","p.apellidos");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array(0,2);   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(0,140,30,250,100,150,150);
           $lis->Aling    =array('center','center','center','center','center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array(0);  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           $lis->LFiltro=" e.estado='P' and dp.fecent is NULL and po.estado='P'";

           $lis->Iconos=array();

           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "morosos":
            $tabla  ="prestamos p INNER JOIN personas pe on pe.cedula=p.psa_estudiante ";
            $campos ="p.id,p.fechapre,p.psa_estudiante,pe.nombres,pe.apellidos";
            $orden  ="p.fechapre asc";

           #Configuracion de Tabla
           $titulos        ="#ID, F Prestamo, Cedula, Nombres, Apellidos";
           $lis->Columnas=array("p.id","p.fechapre","p.psa_estudiante","pe.nombres","pe.apellidos");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(100,150,100,250,250);
           $lis->Aling    =array('center','center','center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           $lis->LFiltro=" p.estado='P' and DATE_SUB(p.fechapre,INTERVAL -p.dias DAY)<now()";

           $lis->Iconos=array();

           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "alumnos":
            $tabla  ="personas p INNER JOIN estudiantes e on e.psa_cedula=p.cedula INNER JOIN carreras c on c.id=e.cra_id";
            $campos ="p.cedula,p.nombres,p.apellidos,e.semestre,e.regimen,c.carrera";
            $orden  ="p.cedula asc";

           #Configuracion de Tabla
           $titulos        ="#Cedula, Nombres, Apellidos, Semestre, Regimen, Carrera";
           $lis->Columnas=array("p.cedula","p.nombres","p.apellidos","e.semestre","e.regimen","c.carrera");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(80,170,170,80,80,250);
           $lis->Aling    =array('center','center','center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro="";
           //}

           #Configuracion de los Iconos
           if ($_SESSION['usuario']['nivel']!="U") {
               $editar=array("URL","alumnos","id,tipoform","0,E","E","Editar Registro");
               $borrar=array("URL","alumnos","id,tipoform","0,E","D","Editar Registro");
               $carnet=array("URL","carnets","id,tipoform","0,E","T","Carnet Bibliotecario");
               $lis->Iconos=array($editar,$borrar,$carnet);
           }


           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;

        case "suspendidos":
            $tabla  ="carnets c INNER JOIN personas p on p.cedula=c.ete_cedula";
            $campos ="c.id,p.cedula,p.nombres,p.apellidos,c.observacion";
            $orden  ="p.cedula asc";

           #Configuracion de Tabla
           $titulos        ="#Carnet, Cedula, Nombres, Apellidos, Observacion";
           $lis->Columnas=array("c.id","p.cedula","p.nombres","p.apellidos","c.observacion");
           $lis->ClaseCSS  ="listadoverde";
           $lis->RutaImg="../listado/imagenes";
           $ids=array();   //Contiene los Indices de los Campos que no se mostraran


           $lis->sizeTd    =array(100,120,200,200,270);
           $lis->Aling    =array('center','center','center','center','center');
           $lis->setSizeTb(890);
           //if (!empty($_POST['noLabel']))	$lis->NoLabel   =explode(",",trim($_POST['noLabel']));	else $lis->NoLabel=array();
           $lis->NoLabel=array();  //Contiene los indices de las Columnas Segun Titulo Que no Se Mostrara

           #Configuraci�n de Busqueda
           // if (!empty($_POST['valor'])){
           $lis->LFiltro=" now() BETWEEN c.desde and c.hasta and c.estado='S'";
           //}

           #Configuracion de los Iconos
           //$editar=array("URL","alumnos","id,tipoform","0,E","E","Editar Registro");
           //$carnet=array("URL","carnets","id,tipoform","0,E","T","Carnet Bibliotecario");
           $lis->Iconos=array();

           #Configuracion de paginaci�n
           if (empty($maxpage)) {
               $maxpage=30;
           }
           $limit=array('0'=>1,'1'=>$maxpage);
        break;
    }
