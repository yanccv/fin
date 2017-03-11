<?php
    session_start();
    include("../includes/classdb.php");
    include("../includes/funcion.php");
    $bd = new dbMysql();
    $bd->dbConectar();
   $enc=false;
   $ConConfig=$bd->dbConsultar("select * from configuracion");
   if (!$bd->Error) {
       if ($ConConfig->num_rows>0) {
           $Config=$ConConfig->fetch_array();
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
<title>Configuración</title>
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery/jquery.js"></script>
<script type="text/javascript" src="../scripts/jquery/forms.js"></script>
<link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery/jqueryui.js" ></script>
<script type="text/javascript" src="../scripts/calendario/datepicker.js" ></script>
<script type="text/javascript" src="../scripts/calendario/timepicker.js" ></script>


<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->

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
        $('input[tipo=fechahora]').datetimepicker({
            dateFormat: 'dd-mm-yy',
            timeFormat: 'HH:mm:ss'
        });
        /*
        $('input[tipo=fechahora]').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            yearRange: "c:c+1",
            //minDate:''+dia+'/'+mes+'/'+anio+'',
            regional:"es"
        });
        */
    });
</script>

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
       	  <form method="post" action="procesar.php" name="ActBanner" id="ActBanner">
            <input type="hidden" id="idform" name="idform" value="Configurar" />
            <input type="hidden" id="id" name="id" value="<?php echo $Config['id']; ?>" />
            <div class="CampoCompleto">
            	<div class="Etiqueta">Días Para Activar:</div>
                <div class="CampoCorto"><input type="text" id="dias" name="dias" size="15" maxlength="11" value="<?php echo $Config['tiempoactivo']; ?>" /></div>
            	<div class="Etiqueta">Mínimo de Apertura:</div>
                <div class="CampoCorto"><input type="text" id="minimo" name="minimo" size="15" maxlength="11" value="<?php echo $Config['minimoinicial']; ?>" /></div>
            	<div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Mínimo Por Registro:</div>
                <div class="CampoCorto"><input type="text" id="minreg" name="minreg" size="15" maxlength="11" value="<?php echo $Config['minimoregistro']; ?>" /></div>
            	<div class="Etiqueta">Mínimo Renovación:</div>
                <div class="CampoCorto"><input type="text" id="minren" name="minren" size="15" maxlength="11" value="<?php echo $Config['minimorenova']; ?>" /></div>
            	<div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Monto Máximo:</div>
                <div class="CampoCorto"><input type="text" id="mmaximo" name="mmaximo" size="15" maxlength="11" value="<?php echo $Config['mmaximo']; ?>" /></div>
            	<div class="Etiqueta">Moneda Base:</div>
                <div class="CampoCorto"><?php   echo $bd->dbComboSimple("select id,moneda from monedas", array(), "moneda", 0, array(1), $Config['monedabase']) ;   ?> </div>
            	<div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Convenio FPC:</div>
                <div class="CampoLargo"><textarea id="fpc" name="fpc" rows="8" cols="85"><?php echo $Config['conveniofpc']; ?></textarea></div>
            	<div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Convenio FPM:</div>
                <div class="CampoLargo"><textarea id="fpm" name="fpm" rows="8" cols="85"><?php echo $Config['conveniofpm']; ?></textarea></div>
            	<div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Cuenta de Correo:</div>
               <div class="CampoMedio"><input type="text" id="correo" name="correo" size="40" maxlength="70" value="<?php echo $Config['correo']; ?>" /></div>
            	<div class="Etiqueta">% Pago Publicidad:</div>
               <div class="CampoCorto"><input type="text" id="pago" name="pago" size="5" maxlength="5" value="<?php echo $Config['pppublicidad']; ?>" /></div>
            	<div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Monto x Cupon:</div>
                <div class="CampoCorto"><input type="text" id="preciocupon" name="preciocupon" size="15" maxlength="11" value="<?php echo $Config['preciocupon']; ?>" /></div>
                <div class="EtiquetaCorta">% Cupon:</div>
                <div class="CampoCorto"><input type="text" id="porcecupon" name="porcecupon" size="15" maxlength="11" value="<?php echo $Config['porcecupon']; ?>" /></div>
            	<div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Ciclo Cupon:</div>
                <div class="CampoCorto"><input type="text" id="maxciclo" name="maxciclo" size="15" maxlength="11" value="<?php echo $Config['maxciclo']; ?>" /></div>
            	<div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Fecha Hora:</div>
                <div class="CampoCorto"><input type="text" id="fechaesc" tipo='fechahora' name="fechaesc" size="15" maxlength="11" value="<?php echo FTUser($Config['fechaesc']); ?>" /></div>
                <div class="EtiquetaCorta">Nro Cupon:</div>
                <div class="CampoCorto"><input type="text" id="cuponesc" name="cuponesc" size="15" maxlength="20" value="<?php echo $Config['cuponesc']; ?>" /></div>
            	<div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Marquesina:</div>
                <div class="CampoLargo"><textarea id="marquesina" name="marquesina" rows="1" cols="85"><?php echo $Config['marquesina']; ?></textarea></div>
            	<div class="Limpiador"></div>
            </div>

            <div class="FormFin">
            	<input name="Boton" id="Boton" value="Actualizar Información" type="submit" />
            </div>
      	  </form>
           <div id="info"></div>
        </div>
        <!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>
