<?php
session_start();
if (empty($_SESSION['usuario']['login'])) {
    header("location: index.php");
}
include("../includes/classdb.php");
include("../includes/funcion.php");
$bd = new dbMysql();
$bd->dbConectar();
if (isset($_GET['cedula'])) {
    $conCliente=$bd->dbConsultar('select * from clientes where cedula=?', array($_GET['cedula']));
    if (!$bd->Error) {
        $Cliente=$conCliente->fetch_array();
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Administrativa.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Editar Cliente</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->


<script type="text/javascript" src="../scripts/jquery/jquery.js"></script>
<script type="text/javascript" src="../scripts/jquery/jqueryui.js"></script>
<script type="text/javascript" src="../scripts/jquery/forms.js"></script>
<link href="../css/estructura.css" rel="stylesheet" type="text/css" />
<link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />
<script>
    var fecha= new Date();
    var dia=fecha.getDate();
    //alert(fecha.getDay());
    //alert(hoy);
    var mes=fecha.getMonth()+1;
    //alert(fecha.getMonth());
    var anio=fecha.getFullYear();
    //alert(dia+" "+mes+ " "+anio);
    $().ready(function() {
    //$.datepicker.setDefaults($.datepicker.regional["es"]);
        $('input[tipo=fechahora]').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            regional:"es"
        });
    });
</script>
<!-- InstanceEndEditable -->
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
            <div class="Articulo" id="FormDatos">
                <div class="TituloArticulo">Datos Personales del Afiliado</div>
                <div class='SeparadorArticuloInterno'></div>
                <form id="fdatos" name="fdatos" method="POST"  enctype="multipart/form-data" action="procesar.php">
                <div class="ContenidoArticulo">
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Cedula o ID: </div>
                        <div class="CampoCorto">
                            <input type="text" id="cedula" name="cedula" value="<?php echo $Cliente['cedula']; ?>" readonly="true" maxlength="20" size="20" />
                            <input type="hidden" id="idform" name="idform" value="ActualizarDatos" readonly="true" maxlength="20" size="20" />
                        </div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Nombre: </div>
                        <div class="CampoCorto"><input type="text" maxlength="30" size="30" id="nombre" value="<?php echo $Cliente['nombre']; ?>" name="nombre" /></div>
                        <div class="EtiquetaCorta">Apellido: </div>
                        <div class="CampoCorto"><input type="text" maxlength="30" size="30" id="apellido" value="<?php echo $Cliente['apellido']; ?>" name="apellido" /></div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Fec. Nac: </div>
                        <div class="CampoCorto"><input type="text" maxlength="10" size="15" tipo='fechahora' value="<?php echo FUser($Cliente['fnac']); ?>" id="fnac" name="fnac" /></div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Dirección: </div>
                        <div class="CampoLargo"><input type="text" maxlength="100" size="50" id="direccion" value="<?php echo $Cliente['direccion']; ?>" name="direccion" /></div>
                        <div class="Limpiador"></div>
                    </div>
                    <?php
                        $tele=explode("|", $Cliente['telefonos']);
                    ?>
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Telefono 1: </div>
                        <div class="CampoCorto"><input type="text" maxlength="16" size="20" id="tele1" value="<?php echo $tele[0]; ?>" name="tele1" /></div>
                        <div class="EtiquetaCorta">Telefono 2: </div>
                        <div class="CampoCorto"><input type="text" maxlength="16" size="20" id="tele2" value="<?php echo $tele[1]; ?>" name="tele2" /></div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Telefono 3 </div>
                        <div class="CampoCorto"><input type="text" maxlength="16" size="20" id="tele3" value="<?php echo $tele[2]; ?>" name="tele3" /></div>
                        <div class="EtiquetaCorta">Correo: </div>
                        <div class="CampoCorto"><input type="text" maxlength="30" size="30" value="<?php echo $Cliente['email']; ?>" id="correo" name="correo" /></div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="FormFin">
                        <input type="submit" id="Boton" name="Boton"  value="Actualizar Datos" />
                    </div>
                </div>
                </form>
                <div id="info"></div>
                <div class="Limpiador"></div>
            </div>
        </div>




        <!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>
