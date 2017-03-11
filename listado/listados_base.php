<?php    
	header('Content-Type: text/html; charset=UTF-8');
    include('../includes/cl_lis.php');  
	$lis=new LISTA();        
    
    if (!empty($_GET['tipolis']))  $tipolis=$_GET['tipolis'];    //else  $tipolis="roles";
    $lis->Forms=$tipolis;
   
    include("../listado/php/parametros.php");
    echo "<br /><center><div id='title".$lis->ClaseCSS."'>".str_replace("_"," ",$_GET['titulo'])."</div></center><br />";			
	$lis->buscar($campos,$titulos,$ids);    
	echo "<div id='listado'>";
	   $lis->Listados($campos,$titulos,$tabla,$filtro,$orden,$limit);	
	   //echo $lis->getSql();		
	   $lis->paginacion($tabla,$limit);	
	echo "</div>"; 
		      
?>
<link href="../listado/css/listado.css" rel="stylesheet" type="text/css" />
<script src="../listado/scripts/jquery.js" type="text/javascript"></script>
<script src="../listado/scripts/listado.js" type="text/javascript"></script>
<br />
<br />
