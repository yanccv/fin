<?php
    session_start();
	//include("../includes/classdb.php");
    //$bd= new dbMysql();
    //$bd->dbConectar();
	include('../includes/listados.php'); 
	$lis=new LISTA();    
    
    
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Clientes.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Documento sin título</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link href="../css/estructura.css" rel="stylesheet" type="text/css" />
<link href="../listado/css/listado.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/menu/stmenu.js" ></script>
<script src="../listado/scripts/jquery.js"  type="text/javascript"></script>
<script src="../listado/scripts/listado.js" type="text/javascript"></script>
<!-- InstanceEndEditable -->
</head>

<body>
<div id="Contenedor">
	<div id="Banner">
    	<img src="../imagenes/banner.png" border="0" />
    </div>
	<div id="FilaMenu">
	  	<div id="Menu">
    		<script type="text/javascript" src="../scripts/menu/cliente.js"></script>
	    </div>
    </div>
	<div id="DatosUser">

    	<div class="UserCedula"><strong>Cedula: </strong><?php echo $_SESSION['cliente']['cedula']; ?></div>        
    	<div class="UserNombre"><strong>Nombre: </strong><?php echo $_SESSION['cliente']['nombre']." ".$_SESSION['cliente']['apellido']; ?></div>                
    	<div class="UserPais"><strong>Pais: </strong><?php echo $_SESSION['cliente']['npais']; ?></div>        
        <div class="Limpiador"></div>        
    </div>    
    <div id="Cuerpo">    
    <!-- InstanceBeginEditable name="CentroClientes" -->
<div class="FormDatos">
  	    <?php 
  	if (!empty($_GET['tipolis']))  $tipolis=$_GET['tipolis'];    else  { echo "<div class='info'>Busqueda No Autorizada</div>";  exit();    }
    $lis->Forms=$tipolis;
   
    include("../listado/php/parametros.php");
    echo "<br /><center><div id='title".$lis->ClaseCSS."'>".str_replace("_"," ",$_GET['titulo'])."</div></center><br />";
    if ($lis->Busca)			
	   $lis->buscar($campos,$titulos,$ids);    
	echo "<div id='listado'>";
		$lis->Listados($campos,$titulos,$tabla,$filtro,$orden,$limit);
    //echo $lis->getSql();  
    if ($lis->Pagina)			
		$lis->paginacion($tabla,$limit);	
	echo "</div>";
   	
  ?>
  <br />
  <div id="InfoAdicional">
        <?php if (!empty($_GET['base'])){ ?>
    	<a href="../paginas/<?php echo $_GET['base']; ?>.php" class="BotonAuxiliar">Agregar Nuevo</a>
        <?php } ?>
   </div>
   <?php 
   
   /*
    
    print_r($lis->Iconos);
    if ($lis->Iconos)   echo "Hay";
    if (is_null($lis->Iconos))  echo "No Hay";
    if (empty($lis->Iconos))    echo "Esta Vacia";
   */
   ?> 
    <!-- InstanceEndEditable -->
    </div>
    <?php include('derechos.html'); ?> 
</div>       
</body>
<!-- InstanceEnd --></html>
