<?php
	session_start();
	include("../includes/classdb.php");
	$bd = new dbMysql();
	$bd->dbConectar();
   $enc=false;
   $ConBaremo=$bd->dbConsultar("select * from baremos");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Administrativa.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Baremos</title>
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
            	Formulario de Configuración
            </div>
            <div class="SeparadorArticuloInterno"></div>
       	  <form method="post" action="procesar.php" name="fdatos" id="fdatos">
            <input type="hidden" id="idform" name="idform" value="Baremos" />
        <?php
         if (!$bd->Error){
            if ($ConBaremo->num_rows>0){
               while ($Baremo=$ConBaremo->fetch_array()){
         ?>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Monto:</div><input type="hidden" id="id[]" name="id[]" value="<?php echo $Baremo['id']; ?>" />
                <div class="CampoCorto"><input type="text" id="monto[<?php echo $Baremo['id']; ?>]" name="monto[<?php echo $Baremo['id']; ?>]" size="5" maxlength="7" value="<?php echo $Baremo['monto']; ?>" /></div>
            	<div class="Etiqueta">% de Participación:</div>
                <div class="CampoCorto"><input type="text" id="porce[<?php echo $Baremo['id']; ?>]" name="porce[<?php echo $Baremo['id']; ?>]" size="5" maxlength="5" value="<?php echo $Baremo['porcentaje']; ?>" /></div>
                <div class="EtiquetaCorta"><a class="DelBaremo" href="#">Eliminar</a></div>
            	<div class="Limpiador"></div>
            </div>
         <?php
               }
            }
      }else echo "<center><br /><br />".$bd->MsgError."</cente>";
      ?>
            <div class="FormFin">
            	<input name="Boton" id="Boton" value="Actualizar Baremos" type="submit" />
            </div>
      	  </form>
           <div id="info"></div>
        </div>
        <div class="FormFin">
     	   <input name="AddBaremo" id="AddBaremo" value="Agregar Linea" type="button" />
        </div>
        <!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>
