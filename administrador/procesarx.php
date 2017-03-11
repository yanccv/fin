<?php    
    session_start();
    if ($_POST['idform']!="login"){
      	include("check.php");
	   checkar($_SERVER['SCRIPT_NAME'],$_SESSION['usuario']['nivel'],$_SERVER['HTTP_REFERER']);            
    }
    include("../includes/funciones.php");

    include("../class/classvd.php");
    include("../class/areas.php");
    include("../class/autores.php");
    include("../class/carreras.php");
    include("../class/editoriales.php");
    include("../class/fuentes.php");
    include("../class/libros.php");
    include("../class/alumnos.php");
    include("../class/prestamos.php");
    include("../class/ejemplares.php");
    include("../class/carnets.php");    
    include("../class/usuarios.php");
    include("../class/configuraciones.php");
    
	//@include("../includes/funcion.php");
    $error=1;
    $va= new Validar();
	
	switch($_POST['idform']){
	   	case "BEjemplar":                        
            //$Libro= new Libros($_POST['id']);
            //$Libro->Buscar();            
            $bd = new dbMysql();
            $bd->dbConectar();
            $Consulta=$bd->dbConsultar("select ejemplar from ejemplares where libro=? and estado='A'",array($_POST['id']));
            if ($Consulta->num_rows>0){
                $combo="<select id='ejemplar' name='ejemplar'>";
                $option="<option value =''>Seleccione</option>";
                while ($fila=$Consulta->fetch_array()){
                    $option.="<option value ='".$fila['ejemplar']."'>".$fila['ejemplar']."</option>";
                }
                $combo.="</select>";
            }
            
            
            $msg['ejemplar']=$option;                
        break;
        
        case "BAlumno":                        
            $bd = new dbMysql();
            $bd->dbConectar();
            $Alumno = new Alumnos($_POST['cedula']);
            $Config = new Configuraciones();
            $Config->Buscar();
            $MaxLibros=$Config->getMaxLibros();
            if (!$Alumno->Existe())
                $msg['men']="Disculpe Alumno No registrado";
            else
            {   
                $Consulta=$bd->dbConsultar("SELECT dp.dprestamo,l.cota,l.titulo,DATE_FORMAT(p.fechapre,'%d/%m/%Y %H:%i:%s'),concat(ps.nombres,' ',ps.apellidos) from prestamos as p INNER JOIN personas as ps on ps.cedula=p.psa_estudiante INNER JOIN deta_prestamos as dp on dp.prestamo=p.id INNER JOIN libros as l on l.id=dp.libro where p.psa_estudiante=? and estado='P'",array($_POST['cedula']));                                 
                if ($Consulta->num_rows>0){
                    if ($Consulta->num_rows>=$MaxLibros){
                        $cadena="<table align='center'><caption><strong>LIBROS QUE POSEE EN PRESTAMO</strong></caption><tr><td>COTA</td><td>LIBRO</td><td>FECHA</td></tr>";
                        while ($Libros=$Consulta->fetch_array()){
                            $cadena.="<tr><td>".$Libros[1]."</td><td>".$Libros[2]."</td><td>".$Libros[3]."</td></tr>";
                        }                        
                        $cadena.="<tr><td colspan='3'>No Puede Realizar Mas Prestamos</td></tr></table>";
                        $msg['men']=$cadena;
                    }else{
                        $error=0;
                        //$Alumno=$Consulta->fetch_array();
                        $cadena="<table align='center'><caption><strong>LIBROS QUE POSEE EN PRESTAMO</strong></caption><tr><td>COTA</td><td>LIBRO</td><td>FECHA</td></tr>";
                        while ($Libros=$Consulta->fetch_array()){
                            $cadena.="<tr><td>".$Libros[1]."</td><td>".$Libros[2]."</td><td>".$Libros[3]."</td></tr>";
                            $Nombre=$Libros[4];
                        }
                        $cadena.="</table>";
                        $msg['men']=$cadena;
                        $msg['disponible']=$MaxLibros-$Consulta->num_rows;
                        $msg['nombre']=$Nombre;    
                    }
                }else{                
                    $Consulta=$bd->dbConsultar("SELECT id,CONCAT(p.nombres,' ',p.apellidos) Nombres,c.estado FROM carnets as c INNER JOIN personas as p on p.cedula=c.ete_cedula where c.ete_cedula=? and NOW() BETWEEN c.desde and c.hasta",array($_POST['cedula']));
                    if ($Consulta->num_rows<=0){                    
                        $msg['men']="Alumno No Posee Carnet Activo";
                    }else{                        
                        $Alumno=$Consulta->fetch_array();
                        switch($Alumno[2]){
                            case "S": $msg['men']="Carnet Suspendido, No Puede Prestar Libros";  break;
                            case "V": $msg['men']="Carnet Vencido, Solicita la Activacion";     break;
                            case "A": $msg['nombre']=$Alumno[1];  $msg['disponible']=$MaxLibros;    $error=0;      break;  
                        }
                    }                
                }                                                   
            }                                                     
        break;
        
        case "BPrestamos":                        
            $bd = new dbMysql();
            $bd->dbConectar();
            $Alumno = new Alumnos($_POST['cedula']);
            if (!$Alumno->Existe())
                $msg['men']="Disculpe Alumno No registrado";
            else
            {   $Alumno->Buscar();
                $msg['nombre']=$Alumno->getNombres()." ".$Alumno->getApellidos();                                                                
                $Consulta=$bd->dbConsultar("SELECT p.id,DATE_FORMAT(p.fechapre,'%d-%m-%Y %h:%i:%s') from prestamos p INNER JOIN estudiantes e on e.psa_cedula=p.psa_estudiante INNER JOIN personas ps on ps.cedula=p.psa_estudiante where p.estado='P' and p.psa_estudiante=?",array($_POST['cedula']));                                 
                if ($Consulta->num_rows>0){
                    $error=0;
                    $msg['prestamos'].="<option value=''>Seleccione</option>";
                    while ($fila=$Consulta->fetch_array()){
                        $msg['prestamos'].="<option value='".$fila[0]."'>".$fila[0]."--".$fila[1]."</option>";
                    }
                }else
                {                                                                            
                    $msg['men']="Alumno No Posee Prestamos";            
                }
            }                                                     
        break;

        case "BPrestamo":
            $Prestamo= new Prestamos($_POST['prestamo']);
            $Prestamo->Buscar();
            $msg['prestamo'].="<div class='FormTitulo'>Datos del Prestamos</div><input type='hidden' id='P".$Prestamo->getId()."' name='P".$Prestamo->getId()."'>";
            $msg['prestamo'].="<div class='CampoCompleto'><div class='Etiqueta'>Id:&nbsp;</div><div class='CampoCorto'>".$Prestamo->getId()."</div><div class='Etiqueta'>Fecha:</div><div class='CampoCorto'>".substr($Prestamo->getFechaPre(),0,10)."</div><div class='Limpiador'></div></div>";
            $msg['prestamo'].="<div class='CampoCompleto'><div class='Etiqueta'>Dias de Prestamo:&nbsp;</div><div class='CampoCorto'>".$Prestamo->getDias()."</div><div class='Limpiador'></div></div>";
            
                       
            $msg['prestamo'].="<div class='Listados'>";
            //echo count($Prestamo->Libros);
            for ($i=0;$i<count($Prestamo->Libros);$i++){
                if (empty($Prestamo->Libros[$i]['entrega'])){
                    $msg['prestamo'].="<div id='Nombre' class='ItemAutor'>".$Prestamo->Libros[$i]['titulo']."<span class='Entregar' ref='".$Prestamo->Libros[$i]['id']."'>Entregar Libro</span></div>";                    
                }
                else{
                    $msg['prestamo'].="<div id='Nombre' class='ItemAutor'>".$Prestamo->Libros[$i]['titulo']."</div>";
                }
            }
            $msg['prestamo'].="</div>";                    
            $Suspendido=CalcularSuspencion(substr($Prestamo->getFechaPre(),6,4).substr($Prestamo->getFechaPre(),2,4).substr($Prestamo->getFechaPre(),0,2),$Prestamo->getDias());            
            if ($Suspendido!=0){
                $msg['prestamo'].="<div class='CampoCompletoError'><div class='EtiquetaLarga'>Suspenci&oacute;n de Carnet:&nbsp;</div><div class='CampoCorto'><input id='fsuspendido' name='fsuspendido' tipo='fechahora' maxlength='10' type='text' value='".substr($Suspendido,8,2).substr($Suspendido,4,4).substr($Suspendido,0,4)."' ></div><div class='Limpiador'></div></div>";
            }
        break;
        
        case "ELibro":
            $Prestamo= new Prestamos($_POST['prestamo']);
            $Prestamo->Buscar();
            $ce=0;
            $edo=0;
            for ($i=0;$i<count($Prestamo->Libros);$i++){
                if (!empty($Prestamo->Libros[$i]['entrega']))
                    $ce++;
                if (count($Prestamo->Libros)-$ce==1){
                    $edo=1;
                    $msg['men']="Como Solo Falta Un Libro Por Entregar, Pulse Sobre el Boton Entregar Libro(s)";
                    break;                
                }                    
            }
            if (!$edo){
                $Prestamo->EntregarLibro($_POST['libro']);
                $error=$Prestamo->Error;
                $msg['men']=$Prestamo->getMensaje();
            }
            //$msg['prestamo'].="</div>";                    
        break;        
		case "configurar":            
            if ($va->fecha($_POST['inicio'],"","","Inicio de Semestre"))   $errores[]=$va->error;
            if ($va->fecha($_POST['fin'],$_POST['inicio'],"","Fin de Semestre"))   $errores[]=$va->error;
            if ($va->numeros($_POST['maxdias'],1,"","Maximo de Dias de Prestamo"))   $errores[]=$va->error;
            if ($va->numeros($_POST['maxlibro'],1,"","Maximo de Libros por Prestamo"))   $errores[]=$va->error;
            if ($va->numeros($_POST['maxpeso'],2,"","Peso Maximo de la Foto del Carnet"))   $errores[]=$va->error;
            if ($va->numeros($_POST['maxtamano'],2,"","Tamaño Maximo de la Foto del Carnet"))   $errores[]=$va->error;
            
            if (empty($errores)){
                $error=0;
                $Config= new Configuraciones($_POST['inicio'],$_POST['fin'],$_POST['maxdias'],$_POST['maxlibro'],$_POST['maxpeso'],$_POST['maxtamano']);  
                $Config->Configurar();              
                $error=$Config->Error;
                $msg['men']=$Config->Mensaje;

            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;
		case "areas":            
            if ($va->letras($_POST['area'],5,100,"Area"))   $errores[]=$va->error;
            if (empty($errores)){
                $error=0;
                $Areas= new Areas($_POST['id'],$_POST['area']);                
                switch($_POST['tform']){                    
                    case "A":
                        $Areas->Agregar();
                    break;
                    case "E":
                        $Areas->Modificar();                        
                    break;
                    default:
                        $msg["men"]="Disculpe Acceso invalido";
                    break;
                }
                $error=$Areas->Error;
                $msg['men']=$Areas->Mensaje;

            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;
        case "autores":            
            if ($va->letras($_POST['autor'],5,50,"Autores"))   $errores[]=$va->error;
            if (empty($errores)){
                $error=0;
                $Autor= new Autores($_POST['id'],$_POST['autor']);                
                switch($_POST['tform']){                    
                    case "A":
                        $Autor->Agregar();
                    break;
                    case "E":
                        $Autor->Modificar();                        
                    break;
                    default:
                        $msg["men"]="Disculpe Acceso invalido";
                    break;
                }
                $error=$Autor->Error;
                $msg['men']=$Autor->Mensaje;

            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;
        
        case "carreras":            
            if ($va->letras($_POST['carrera'],5,70,"Carrera"))   $errores[]=$va->error;
            if (empty($errores)){
                $error=0;
                $Carrera= new Carreras($_POST['id'],$_POST['carrera']);                
                switch($_POST['tform']){                    
                    case "A":
                        $Carrera->Agregar();
                    break;
                    case "E":
                        $Carrera->Modificar();                        
                    break;
                    default:
                        $msg["men"]="Disculpe Acceso invalido";
                    break;
                }
                $error=$Carrera->Error;
                $msg['men']=$Carrera->Mensaje;

            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;
        
        case "editoriales":            
            if ($va->letras($_POST['editorial'],5,70,"Editorial"))   $errores[]=$va->error;
            if (empty($errores)){
                $error=0;
                $Editorial= new Editoriales($_POST['id'],$_POST['editorial']);                
                switch($_POST['tform']){                    
                    case "A":
                        $Editorial->Agregar();
                    break;
                    case "E":
                        $Editorial->Modificar();                        
                    break;
                    default:
                        $msg["men"]="Disculpe Acceso invalido";
                    break;
                }
                $error=$Editorial->Error;
                $msg['men']=$Editorial->Mensaje;

            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;
        
        case "fuentes":            
            if ($va->letras($_POST['fuente'],5,70,"Fuente"))   $errores[]=$va->error;
            if (empty($errores)){
                $error=0;
                $Fuente= new Fuentes($_POST['id'],$_POST['fuente']);                
                switch($_POST['tform']){                    
                    case "A":
                        $Fuente->Agregar();
                    break;
                    case "E":
                        $Fuente->Modificar();                        
                    break;
                    default:
                        $msg["men"]="Disculpe Acceso invalido";
                    break;
                }
                $error=$Fuente->Error;
                $msg['men']=$Fuente->Mensaje;

            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;
        
        case "libros":   
            //print_r($_POST);        
            if ($va->longitud($_POST['cota'],15,30,"Cota","Caracteres"))   $errores[]=$va->error;
            if ($va->fecha($_POST['fregistro'],"","","Fec de Registro"))   $errores[]=$va->error;
			if ($va->longitud($_POST['titulo'],5,50,"Titulo","Caracteres"))   $errores[]=$va->error;
			if ($va->seleccion($_POST['edicion'],"Edicion"))   $errores[]=$va->error;
			if ($va->seleccion($_POST['editorial'],"Editorial"))   $errores[]=$va->error;
			if ($va->seleccion($_POST['area'],"Area"))   $errores[]=$va->error;
            if ($va->seleccion($_POST['fuente'],"Fuente"))   $errores[]=$va->error;
            if ($va->seleccion($_POST['anio'],"A&ntilde;o de Publicaci&oacute;n"))   $errores[]=$va->error;
            if ($va->seleccion($_POST['idioma'],"Idioma"))   $errores[]=$va->error;
            if ($va->seleccion($_POST['adquicision'],"Forma de Adquisici&oacute;n"))   $errores[]=$va->error;
            //adquicision
            if ($_POST['tform']!="E")
                if ($va->numeros($_POST['ejemplares'],1,"","Ejemplares"))   $errores[]=$va->error;
                                    
            for ($i=0;$i<count($_POST['Autores']);$i++){
                $Autor= new Autores("",$_POST['Autores'][$i]);
                if (!$Autor->Existe()){
                    $Autor->Agregar();
                    $Autor->Existe();
                }
                $Autores[$i]['id']=$Autor->IdE;
                $Autores[$i]['autor']=$_POST['Autores'][$i];
            }
            if (empty($errores)){
                $error=0;
                $Libros= new Libros($_POST['id'],$_POST['cota'],$_POST['titulo'],$_POST['edicion'],$_POST['editorial'],$_POST['area'],$_POST['ejemplares'],$_POST['fuente'],$_POST['isbn'],$_POST['fregistro'],$_POST['ciudad'],$_POST['anio'],$_POST['idioma'],$_POST['adquicision'],$Autores);                
                switch($_POST['tform']){                    
                    case "A":
                        $Libros->Agregar();
                    break;
                    case "E":
                        $Libros->Modificar();                        
                    break;
                    default:
                        $msg["men"]="Disculpe Acceso invalido";
                    break;
                }
                $error=$Libros->Error;
                $msg['men']=$Libros->Mensaje;

            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;
        case "librosdel":      
            //print_r($_POST);      
            if ($_SESSION['usuario']['nivel']!="A" && $_SESSION['usuario']['nivel']!="O")
                $errores[]="<center>Disculpe Nivel No Autorizado Para Realizar Eliminaciones</center>";
            if (empty($errores)){
                $Libros= new Libros($_POST['id']);                
                $Libros->Borrar();                        
                $error=$Libros->Error;
                $msg['men']=$Libros->Mensaje;

            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;        
        case "desincorporar":          
			if ($va->seleccion($_POST['ejemplar'],"Ejemplar a Desincorporar"))   $errores[]=$va->error;
            if ($va->seleccion($_POST['causa'],"Causa de la Desincorporaci&oacute;n"))   $errores[]=$va->error;            
            if (empty($errores)){
                $error=0;
                $Ejemplar= new Ejemplares($_POST['id'],$_POST['ejemplar'],$_POST['causa'],$_POST['obs']);
                $Ejemplar->Desincorporar();                                
                $error=$Ejemplar->Error;
                $msg['men']=$Ejemplar->Mensaje;
            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;
        case "incorporar":           
			if ($va->seleccion($_POST['ejemplares'],"Ejemplar a Desincorporar"))   $errores[]=$va->error;           
            if (empty($errores)){
                $error=0;
                $Ejemplar= new Ejemplares($_POST['id'],null,null,null,$_POST['ejemplares']);
                $Ejemplar->Incorporar();                                
                $error=$Ejemplar->Error;
                $msg['men']=$Ejemplar->Mensaje;
            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;

        case "reincorporar":    
			if ($va->seleccion($_POST['ejemplar'],"Ejemplar a Reincorporar"))   $errores[]=$va->error;           
            if (empty($errores)){
                $error=0;
                $Ejemplar= new Ejemplares($_POST['id'],$_POST['ejemplar']);
                $Ejemplar->Reincorporar();                                
                $error=$Ejemplar->Error;
                $msg['men']=$Ejemplar->Mensaje;
            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;
        
        case "alumnos":            
            if ($va->numeros($_POST['cedula'],6,8,"Cedula"))   $errores[]=$va->error;
			if ($va->letras($_POST['nombres'],3,50,"Nombres"))   $errores[]=$va->error;
            if ($va->letras($_POST['apellidos'],3,30,"Apellidos"))   $errores[]=$va->error;			
			if ($va->seleccion($_POST['semestre'],"Semestre"))   $errores[]=$va->error;
			if ($va->seleccion($_POST['regimen'],"Regimen"))   $errores[]=$va->error;
            if ($va->seleccion($_POST['carrera'],"Carrera"))   $errores[]=$va->error;
			if ($va->longitud($_POST['direccion'],15,70,"Direccion","Caracteres"))   $errores[]=$va->error;
			if ($va->telefono($_POST['telefono'],12,12,"Telefono"))   $errores[]=$va->error;
            if ($va->email($_POST['correo'],10,70,"Correo"))   $errores[]=$va->error;
            //Validaciones de la Imagen
            $raiz="../fotos/";        
            if (!empty($_POST['idfoto']))   $foto=$_POST['idfoto'];    
            if (!empty($_FILES['foto']['name'])){                
                $tipos=array("jpg","png","gif");
                $file=$_FILES['foto']['name'];
                $ext=strtolower(substr($_FILES['foto']['name'],(strrpos($_FILES['foto']['name'], '.', -4)+1))); 
                if (!in_array($ext,$tipos))
                    $errores[]="Formato de Imagen Invalido, Solo jpg,png,gif";
                $peso=round($_FILES['foto']['size']/1024,2);
                $size=getimagesize($_FILES['foto']['tmp_name']);
                if ($peso>5){   //Maximo 5Kb
                    $errores[]="Disculpe Imagen Muy Pesada, Maximo 100Kb";
                }elseif ($size[0]>100 || $size[1]>100) {    //Maximo de Ancho y Alto de 100px
                    $errores[]="Disculpe Imagen Muy Grande [".$size[0]."x".$size[1]."]px, Maximo [100x100]px";                
                }
                $foto=$raiz.$_POST['cedula'].".".$ext;
            }            

            if (empty($errores)){
                $error=0;
                $Alumno= new Alumnos($_POST['cedula'],$_POST['nombres'],$_POST['apellidos'],$_POST['semestre'],$_POST['regimen'],$_POST['carrera'],$_POST['direccion'],$_POST['telefono'],$_POST['correo'],$foto);                
                switch($_POST['tform']){                    
                    case "A":
                        $Alumno->Agregar();
                    break;
                    case "E":
                        $Alumno->Modificar();                        
                    break;
                    default:
                        $msg["men"]="Disculpe Acceso invalido";
                    break;
                }
                if (!empty($_FILES['foto']['name']) && !$Alumno->Error){            
                    
                    if (!is_dir($raiz))
                        mkdir($raiz,0777);
        
                    //comprobamos si el archivo ha subido
                    if ($file && move_uploaded_file($_FILES['foto']['tmp_name'],$foto))
                    {
                        sleep(2);//retrasamos la petición 3 segundos
                        $imagen="Archivo Cargado";//devolvemos el nombre del archivo para pintar la imagen
                    }else{
                        $imagen="Archivo No Cargado";
                    }            
                }
                $error=$Alumno->Error;
                $msg['men']=$Alumno->Mensaje."<br />".$imagen;

            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;
        
        case "alumnosm": 
             if (!empty($_FILES['archivo']['name'])){
                $file=$_FILES['archivo']['name'];
                $tipos=array("csv");                
                $ext=strtolower(substr($_FILES['archivo']['name'],(strrpos($_FILES['archivo']['name'], '.', -4)+1))); 
                if (!in_array($ext,$tipos))
                    $errores[]="Formato de Imagen Invalido, Solo CSV";                    
             }else{
                $errores[]="No Envio Ningun Archivo";
             }
             if (empty($errores)){                
                $fp = fopen ( $_FILES['archivo']['tmp_name'] , "r" );
                $filas=0;
                $filaProcesada=0;
                while (( $data = fgetcsv ( $fp , 2048, ";","\"" )) !== false ) { // Mientras hay líneas que leer...
                    $filas++;
                    $tipoimg=array("gif","jpg","png");                
                    $extimg=strtolower(substr($data[8],(strrpos($data[8], '.', -4)+1)));
                    $foto=null;
                    $rutafoto="../fotos/".$data[0].".".$extimg;
                    if (in_array($extimg,$tipoimg)){                        
                        if (file_exists($data[8])){                            
                            if (copy($data[8],$rutafoto)){
                                $foto=$rutafoto;
                            }else{
                                $imagen="No se Pudo Mover la Imagen:".$rutafoto." ";
                            }                            
                        }
                        else
                        {  $imagen= "Foto No Existe";  }
                        
                    }else{
                        $imagen= "No es Una Imagen Valida";
                    }                    
                    $Alumno = new Alumnos($data[0],$data[1],$data[2],$data[6],$data[7],$data[9],$data[3],$data[4],$data[5],$foto);
                    $Alumno->Agregar();
                    if (!$Alumno->Error){
                        $filaProcesada++;
                    }                        
                    if ($Alumno->Error || !empty($imagen)){
                        $cadena.="Error en Fila [".$filas."]: ";
                        if ($Alumno->Error)
                            $cadena.=$Alumno->getMensaje()." ";
                        if (!empty($imagen))
                            $cadena.=$imagen."<br />";
                    }
                }
                fclose ($fp);
                if ($filas==$filaProcesada){
                    $error=0;
                    $msg['men']="Todas las Filas Fueron Cargadas Correctamente";
                }else{
                    $msg['men']="Ocurrieron Errores en la Carga, Filas de Archivo ".$filas.", Filas Procesadas ".$filaProcesada.$men;
                }
                if (!empty($cadena))
                    $msg['men'].="<br /><br /><center>Errores Ocurridos Durante La Carga</center><br />".$cadena;
             }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERRORES POR CORREGIR ANTES DE CONTINUAR</center><ul><li>".implode("<li>",$errores)."</ul>";				 	                
             }
            /*           
            if ($va->numeros($_POST['cedula'],6,8,"Cedula"))   $errores[]=$va->error;
			if ($va->letras($_POST['nombres'],3,50,"Nombres"))   $errores[]=$va->error;
            if ($va->letras($_POST['apellidos'],3,30,"Apellidos"))   $errores[]=$va->error;			
			if ($va->seleccion($_POST['semestre'],"Semestre"))   $errores[]=$va->error;
			if ($va->seleccion($_POST['regimen'],"Regimen"))   $errores[]=$va->error;
            if ($va->seleccion($_POST['carrera'],"Carrera"))   $errores[]=$va->error;
			if ($va->longitud($_POST['direccion'],15,70,"Direccion","Caracteres"))   $errores[]=$va->error;
			if ($va->telefono($_POST['telefono'],12,12,"Telefono"))   $errores[]=$va->error;
            if ($va->email($_POST['correo'],10,70,"Correo"))   $errores[]=$va->error;
            */
            //Validaciones de la Imagen                    
        break;        
        
        case "carnets":            
            if ($va->numeros($_POST['Cedula'],6,8,"Cedula"))   $errores[]=$va->error;
            if (empty($_POST['NombreAlumno']))  $errores[]="Alumno No Encontrado";
            //if ($va->numeros($_POST['NombreAlumno'],6,8,"Cedula"))   $errores[]=$va->error;
			if ($va->fecha($_POST['desde'],'','',"Desde"))   $errores[]=$va->error;
            if ($va->fecha($_POST['hasta'],$_POST['desde'],'',"Hasta"))   $errores[]=$va->error;			
                        
            if (empty($errores)){
                $error=0;
                $Carnet= new Carnets($_POST['id'],$_POST['Cedula'],$_POST['desde'],$_POST['hasta'],$_POST['estado'],null,$_POST['fsuspendido']);                
                switch($_POST['tform']){                    
                    case "A":
                        $Carnet->Agregar();
                    break;
                    case "E":
                        $Carnet->Modificar();                        
                    break;
                    default:
                        $msg["men"]="Disculpe Acceso invalido";
                    break;
                }
                $error=$Carnet->Error;
                $msg['men']=$Carnet->Mensaje;

            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;
        
        case "prestamos":            
            if ($va->numeros($_POST['Cedula'],6,8,"Cedula"))   $errores[]=$va->error;
			if (count($_POST['Libros'])<=0)  $errores[]="No Hay Ningun Libro para Prestar";
            //print_r($_POST);
            for ($i=0;$i<count($_POST['Libros']);$i++){
                $Libro= new Libros($_POST['Libros'][$i]);                
                if (!$Libro->ExisteId()){
                    $errores[]="Disculpe Libros No Registrados";
                }
                $Libros[$i]['id']=$_POST['Libros'][$i];
                $Libros[$i]['ejemplar']=$_POST['Ejemplar'][$i];
            }
                        
            if (empty($errores)){
                $error=0;
                $Prestamo= new Prestamos($_POST['id'],$_SESSION['usuario']['cedula'],$_POST['Cedula'],$_POST['fechapre'],$_POST['dias'],null,$Libros);                
                switch($_POST['tform']){                    
                    case "A":
                        $Prestamo->Agregar();
                    break;
                    case "E":
                        $Prestamo->Modificar();                        
                    break;
                    default:
                        $msg["men"]="Disculpe Acceso invalido";
                    break;
                }
                $error=$Prestamo->Error;
                $msg['men']=$Prestamo->Mensaje;

            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;
		
        case "entregar":            
            if ($va->numeros($_POST['CedPres'],6,8,"Cedula"))   $errores[]=$va->error;
            if ($va->seleccion($_POST['Prestamos'],"Prestamos"))   $errores[]=$va->error;
            
            if (empty($errores)){
                $error=0;
                $Prestamo= new Prestamos($_POST['Prestamos']);
                $Prestamo->Entregar();    
                if (!empty($_POST['fsuspendido'])){
                    //echo "Entro";
                    $Carnet = new Carnets(null,$_POST['CedPres']);
                    $Carnet->Buscar();
                    $Carnet->Suspender($_POST['fsuspendido']);
                    //echo $Carnet->Mensaje;
                }            
                $error=$Prestamo->Error;
                $msg['men']=$Prestamo->Mensaje;
            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;
        
        case "usuarios":            
            if ($va->numeros($_POST['cedula'],6,8,"Cedula"))   $errores[]=$va->error;
			if ($va->letras($_POST['nombres'],3,30,"Nombres"))   $errores[]=$va->error;
            if ($va->letras($_POST['apellidos'],3,30,"Apellidos"))   $errores[]=$va->error;			
			if ($va->alfa($_POST['login'],6,20,"Login"))   $errores[]=$va->error;
			if ($va->alfa($_POST['clave'],6,20,"Clave"))   $errores[]=$va->error;
            if ($_POST['clave']!=$_POST['rclave'])         $errores[]="Las Claves No Coinciden"; 
            if ($va->seleccion($_POST['nivel'],"Nivel"))   $errores[]=$va->error;
                        
            if (empty($errores)){
                $error=0;
                $Usuario= new Usuarios($_POST['cedula'],$_POST['nombres'],$_POST['apellidos'],$_POST['login'],$_POST['clave'],$_POST['nivel']);                
                switch($_POST['tform']){                    
                    case "A":
                        $Usuario->Agregar();
                    break;
                    case "E":
                        $Usuario->Modificar();                        
                    break;
                    default:
                        $msg["men"]="Disculpe Acceso invalido";
                    break;
                }
                $error=$Usuario->Error;
                $msg['men']=$Usuario->Mensaje;

            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;

        case "login":            
			if ($va->alfa($_POST['login'],6,20,"Login"))   $errores[]=$va->error;
			if ($va->alfa($_POST['clave'],6,20,"Clave"))   $errores[]=$va->error;
                        
            if (empty($errores)){
                $error=0;
                $Usuario= new Usuarios(null,null,null,$_POST['login'],$_POST['clave']);                
                $Usuario->IniciarSession();                
                $error=$Usuario->Error;
                $msg['men']=$Usuario->Mensaje;

            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;

        case "cambiar":            
			if ($va->alfa($_POST['oclave'],6,20,"Clave Actual"))   $errores[]=$va->error;
            if ($va->alfa($_POST['clave'],6,20,"Nueva Clave"))   $errores[]=$va->error;
			if ($va->alfa($_POST['rclave'],6,20,"Repita Clave"))   $errores[]=$va->error;
            if ($_POST['clave']!=$_POST['rclave'])         $errores[]="Las Claves No Coinciden";
                        
            if (empty($errores)){
                $error=0;
                $Usuario= new Usuarios(null,null,null,$_SESSION['usuario']['login'],array($_POST['oclave'],$_POST['clave']));                
                $Usuario->CambiarClave();
                $error=$Usuario->Error;
                $msg['men']=$Usuario->Mensaje;

            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;
		
        case "actualizar":            
			if ($va->letras($_POST['nombres'],3,30,"Nombres"))   $errores[]=$va->error;
            if ($va->alfa($_POST['apellidos'],3,30,"Apellidos"))   $errores[]=$va->error;			                        
            if (empty($errores)){
                $error=0;
                $Usuario= new Usuarios($_POST['cedula'],$_POST['nombres'],$_POST['apellidos']);                
                $Usuario->ActualizarDatos();
                $error=$Usuario->Error;
                $msg['men']=$Usuario->Mensaje;

            }else{
   				$msg['titulo']="Errores Por Corregir Antes de Continuar";                
                $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>",$errores)."</ul>";				 	
            }
        break;
        
        default:
            $msg['men']="Seccion No Encontrada [".$_POST['idform']."]";
        break;
    }
    //$bd->dbDesconectar();
    $msg['error']=$error;
    echo json_encode($msg); 

?>