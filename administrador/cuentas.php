<?php
    session_start();
    include("../includes/classdb.php");
    $bd = new dbMysql();
    $bd->dbConectar();
    if ($_GET['tipoform']=="E") {
        $CCuenta=$bd->dbConsultar("select * from cuentas where id=?", array($_GET['id']));
        if ($bd->Error) {
            echo "<center>".$bd->MsgError."</center>";
            exit();
        } else {
            if ($CCuenta->num_rows>0) {
                $Cuenta=$CCuenta->fetch_array();
            } else {
                echo "<center>Disculpe Cuenta No Encontrada</center>";
            }
        }
    } else {
        $_GET['tipoform']="N";
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Administrativa.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Registro de Cuentas</title>
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery/jquery.js"></script>
<script type="text/javascript" src="../scripts/jquery/forms.js"></script>
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
            	Formulario de Registro de Cuentas
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Banco: </div>
                <div class="CampoCorto">
                    <input name="idform" type="hidden" id="idform" value="Cuentas" />
                    <input name="id" type="hidden" id="id" value="<?php echo (int) $_GET['id']; ?>" />
                    <input name="tipoform" type="hidden" id="tipoform" value="<?php echo $_GET['tipoform']; ?>" />
                    <?php
                     echo $bd->dbComboSimple("select id,banco from bancos", array(), "banco", 0, array(1), $Cuenta['banco']);
                    ?>
                </div>
            	<div class="Etiqueta">Tipo de Cuenta: </div>
                <div class="CampoMedio">
                    <input type="radio" id="tipo" name="tipo" value="C" <?php if ($Cuenta['tipo']=='C') {  echo "checked='checked'"; } ?> />
                    <label for="tipo">Corriente</label>
                    <input type="radio" id="tipo" name="tipo" value="A" <?php if ($Cuenta['tipo']=='A') { echo "checked='checked'";} ?> />Ahorro
                    <input type="radio" id="tipo" name="tipo" value="E" <?php if ($Cuenta['tipo']=='E') { echo "checked='checked'";} ?> />Electronica
                </div>
                <div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Nro de Cuenta: </div>
                <div class="CampoCorto">
                  <input name="cuenta" type="text" id="cuenta" maxlength="20" size="25" value="<?php echo $Cuenta['cuenta']; ?>" />
                </div>
                <div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Titular: </div>
                <div class="CampoLargo">
                  <input name="titular" type="text" id="titular" value="<?php echo $Cuenta['titular']; ?>" />
                </div>
                <div class="Limpiador"></div>
            </div>
            <div class="FormFin">
            	<input name="Boton" id="Boton" value="Guardar Informacion" type="submit" />
            </div>
      	  </form>
        </div>
        <div id="info" class=""></div>
		<!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>
