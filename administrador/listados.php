<?php
	include('../includes/listados.php'); 
	$lis=new LISTA();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Administrativa.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo str_replace("_"," ",$_GET['titulo']); ?></title>
<link href="../listado/css/listado.css" rel="stylesheet" type="text/css" />
<link href="../css/estructura.css"      rel="stylesheet" type="text/css" />
<script src="../listado/scripts/jquery.js"  type="text/javascript"></script>
<script src="../listado/scripts/listado.js" type="text/javascript"></script>

<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<link href="../css/estructura.css" rel="stylesheet" type="text/css" />
<!-- Vinculo al Menu-->
<script type="text/javascript" src="../scripts/menu/stmenu.js"></script>
</head>

<body>
	<div id="Contenedor">
  	  <div id="Banner">
			<img src="../imagenes/banner.png" border='0' />
      </div>
      <div id="DatosUser">
		<table border="0" cellpadding="0" cellspacing="0"><tr><td width="300" align="left">Usuario: <?php echo $_SESSION['usuario']['login']; ?></td><td width="300" align="center">Nombre de Usuario: <?php echo $_SESSION['usuario']['nombre']; ?></td><td width="250" align="right">Perfil: Administrativo</td></tr></table> 
      </div>        
      <div id="FilaMenu">
			 <script type="text/javascript" src="../scripts/menu/administrativo.js"></script>        
      </div>      
        <div id="Cuerpo">        	
        <!-- InstanceBeginEditable name="CentroAdministrativo" -->
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
   </div>        
		<!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>
