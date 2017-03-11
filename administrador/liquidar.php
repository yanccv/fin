<?php
    session_start();
    include("../includes/classdb.php");
   include("../includes/funcion.php");
    $bd = new dbMysql();
    $bd->dbConectar();
    $ConLiquidez=$bd->dbConsultar("select fecha from movimientos where movimiento='Liquidez' order by fautoriza desc limit 1");
    if (!$bd->Error) {
        if ($ConLiquidez->num_rows>0) {
            $FLiquidez=$ConLiquidez->fetch_array();
            $msg="La Ultima Liquidacion se Realizo el Dia: ".FUser($FLiquidez['fecha']);
        } else {
            $msg="Aun No Se Ha Realizado Ninguna Liquidación";
        }
    } else {
        echo $bd->MsgError;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Administrativa.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Liquidación</title>
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery/jquery.js"></script>
<script type="text/javascript" src="../scripts/jquery/forms.js"></script>
<link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery/jqueryui.js" ></script>
<!--<script type="text/javascript" src="../scripts/ckeditor/editor.js"></script>-->

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
       	  <form method="post" action="procesar.php" name="fdatos" id="fdatos">
          	<div class="FormTitulo">
            	Liquidación Mensual de Participantes de las Franquicias, <?php echo $msg; ?>
            </div>

            <div class="FormFin">
                <input type="hidden" id="idform" name="idform" value="Liquidez" /><br />
            	<input name="Boton" class="Liquidez" id="Boton" value="Generar Liquidacion Mensual" type="submit" />
            </div>
      	  </form>
        </div>
        <div id="info" class=""></div>
		<!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>
