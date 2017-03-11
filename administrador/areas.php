<?php
	session_start();
	include("../includes/classdb.php");
	$bd = new dbMysql();
	$bd->dbConectar();
    if ($_GET['tipoform']=="E"){
        $CArea=$bd->dbConsultar("select * from areas where id=?",array($_GET['id']));
        if ($bd->Error){
            echo "<center>".$bd->MsgError."</center>";
            exit();
        }else{
            if ($CArea->num_rows>0){
                $Area=$CArea->fetch_array();
            }
            else
            {
                echo "<center>Disculpe Articulo No Encontrado</center>";
            }
        }
    }else{
        $_GET['tipoform']="N";
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Administrativa.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Registro de Areas</title>
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
            	Formulario de Registro de Areas
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Area:
                </div>
                <div class="CampoCorto">
                    <input name="idform" type="hidden" id="idform" value="areas" />
                    <input name="id" type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
                    <input name="tipoform" type="hidden" id="tipoform" value="<?php echo $_GET['tipoform']; ?>" />
                    <input name="area" type="text" size="30" maxlength="50" id="area" value="<?php echo $Area['area']; ?>" />
                </div>
            	<div class="Etiqueta">Mostrar SubMenu: </div>
                <div class="CampoMedio">
                	<input type="radio" name="submenu" <?php if ($Area['msubmenu']=="S")   echo "checked='checked'"; ?> value="S" id="submenu_0" />Si
                    <input type="radio" name="submenu" <?php if ($Area['msubmenu']=="N")   echo "checked='checked'"; ?> value="N" id="submenu_1" />No
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
