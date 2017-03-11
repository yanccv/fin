<?php
	include('../includes/cl_lis.php'); 
	$lis=new LISTA();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Cliente.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Documento sin título</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link href="../listado/css/listado.css" rel="stylesheet" type="text/css" />
<script src="../listado/scripts/jquery.js" type="text/javascript"></script>
<script src="../listado/scripts/listado.js" type="text/javascript"></script>
<!-- InstanceEndEditable -->
<link href="../css/General.css" rel="stylesheet" type="text/css" />
<link href="../css/Formularios.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../menu/stmenu.js"></script>
</head>

<body>
<div id="Page">
  	
  <div id="Banner"><img src="../imagenes/banner.jpg" width="900" height="100" /></div>
  <div id="Menu">
  <span>
  	<script src="../menu/menu.js" type="text/javascript"></script> 
  </span>
  </div>
  
  <div id="Contenido"><!-- InstanceBeginEditable name="CentroCliente" -->
  <?php 
  	if (!empty($_GET['tipolis']))  $tipolis=$_GET['tipolis'];    else  $tipolis="roles";
    $lis->Forms=$tipolis;
   
    include("../listado/php/parametros.php");
    echo "<br /><center><div id='title".$lis->ClaseCSS."'>".str_replace("_"," ",$_GET['titulo'])."</div></center><br />";			
	$lis->buscar($campos,$titulos,$ids);    
	echo "<div id='listado'>";
		$lis->Listados($campos,$titulos,$tabla,$filtro,$orden,$limit);	
		$lis->paginacion($tabla,$limit);	
	echo "</div>";	
  ?>
  <br />
  <div id="InfoAdicional">
    	<a href="../paginas/<?php echo $_GET['base']; ?>.php" class="BotonAuxiliar">Agregar Nuevo</a>
   </div>
  <!-- InstanceEndEditable --></div>
</div>
</body>
<!-- InstanceEnd --></html>
