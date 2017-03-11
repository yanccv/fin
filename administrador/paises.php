<?php 
	session_start();    
	include("../includes/classdb.php");
	$bd = new dbMysql();
	$bd->dbConectar();	
   $enc=false; 
   $tipoform="N";
   if (!empty($_GET['id'])){
      $id=(int) $_GET['id'];
      $ConPais=$bd->dbConsultar("select * from paises where id =?",array($id));
      if (!$bd->Error){
         if ($ConPais->num_rows>0){
            $Pais=$ConPais->fetch_array();
            $enc=true;
            $tipoform="E";
         }
      }else{
         echo $bd->MsgError;
         exit();
      }
   }   

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Administrativa.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Registro de Paises</title>
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery/jquery.js"></script>
<script type="text/javascript" src="../scripts/jquery/forms.js"></script>
<link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery/jqueryui.js" ></script>

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
          	<div class="FormTitulo">
            	Formulario Registro de Paises                  
            </div>
            <div class="SeparadorArticuloInterno"></div>         
       	  <form method="post" action="procesar.php" name="fdatos" id="fdatos">
            <input type="hidden" id="idform" name="idform" value="Paises" />        
            <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
            <input type="hidden" id="tipoform" name="tipoform" value="<?php echo $tipoform; ?>" />
            <div class="CampoCompleto">
            	<div class="Etiqueta">País:</div>
                <div class="CampoCorto"><input type="text" id="pais" name="pais" size="40" maxlength="50" value="<?php echo $Pais['pais']; ?>" /></div>
            	<div class="Etiqueta">Moneda:</div>
                <div class="CampoCorto">
                  <?php 
                     echo $bd->dbComboSimple("select id,moneda from monedas",array(),"moneda",0,array(1),$Pais['monedaoficial']);
                  ?>
                </div>
                
            	<div class="Limpiador"></div>
            </div>            
            <div class="FormFin">
            	<input name="Boton" id="Boton" value="Guardar" type="submit" />
            </div>           
      	  </form>
           <div id="info"></div>
        </div> 
        <!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>
