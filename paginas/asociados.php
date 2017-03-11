<?php
session_start();
include("../includes/classdb.php");
include("../includes/classvd.php");
include("../includes/funcion.php");
include("../includes/checkin.php");
$bd = new dbMysql();
$bd->dbConectar();
if (empty($_SERVER['HTTP_REFERER'])) {
    if (!CheckCliente($bd)) {
        header("location: index.php?op=Conexion");
    }
} else {
    if (!CheckOrigen()) {
        header("location: index.php?op=Conexion");
    } elseif (!CheckCliente($bd)) {
        header("location: index.php?op=Conexion");
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Clientes.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Retiros de Fondos </title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<!-- Estilos Para las Herramientas UI -->
<link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />
<link href="../css/estructura.css" rel="stylesheet" type="text/css" />
<link href="../css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/menu/stmenu.js" ></script>
<script type="text/javascript" src="../scripts/jquery/jquery.js" ></script>
<script type="text/javascript" src="../scripts/jquery/ffranquiciados.js" ></script>
<!-- Version JQuery UI 1.10 -->
<script type="text/javascript" src="../scripts/jquery/jqueryui.js" ></script>
<!--Hora y Fecha -->
<script type="text/javascript" src="../scripts/calendario/datepicker.js" ></script>
<script type="text/javascript" src="../scripts/calendario/timepicker.js" ></script>
    <!-- Configuracion de Widget de Calendario -->
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
               	yearRange: "c-50:",
                maxDate:''+dia+'/'+mes+'/'+anio+'',
                regional:"es"
            });
        });
    </script>


<!-- InstanceEndEditable -->
</head>

<body>
<div id="Contenedor">
	<div id="Banner">
    	<img src="../imagenes/banner.png" border="0" />
    </div>
	<div id="FilaMenu">
	  	<div id="Menu">
    		<script type="text/javascript" src="../scripts/menu/cliente.js"></script>
	    </div>
    </div>
	<div id="DatosUser">
    	<div class="UserCedula"><strong>Cedula: </strong><?php echo $_SESSION['cliente']['cedula']; ?></div>
    	<div class="UserNombre"><strong>Nombre: </strong><?php echo $_SESSION['cliente']['nombre']." ".$_SESSION['cliente']['apellido']; ?></div>
    	<div class="UserPais"><strong>Pais: </strong><?php echo $_SESSION['cliente']['npais']; ?></div>
        <div class="Limpiador"></div>
    </div>
    <div id="Cuerpo">
    <!-- InstanceBeginEditable name="CentroClientes" -->
        <div class="Articulo">
                <div class="TituloArticulo">Listado de Asociados Directos</div>
                <div class='SeparadorArticuloInterno'></div>
                <br />
                <table id="TRetiros" class="Tabla" align='center' >
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                     <?php
                        $ConAsociados=$bd->dbConsultar("select nombre,apellido,telefonos,email,estado from clientes where asociador=?", array($_SESSION['cliente']['cedula']));
                        if ($bd->Error) {
                            echo $bd->MsgError;
                        } else {
                            if ($ConAsociados->num_rows>0) {
                                ?>
                    <tbody>
                    <?php
                        while ($Asociados=$ConAsociados->fetch_array()) {
                            ?>
                        <tr>
                            <td align='center'><?php echo $Asociados['nombre'];
                            ?></td>
                            <td align='center' ><?php echo $Asociados['apellido'];
                            ?></td>
                            <td align='center' ><?php echo $Asociados['email'];
                            ?></td>
                            <td align='center' ><?php echo $Asociados['telefonos'];
                            ?></td>
                            <?php
                                switch ($Asociados['estado']) {
                                    case "A":   $estado="Activo";     break;
                                    case "I":   $estado="Inactivo";   break;
                                }
                            ?>
                            <td align='center' ><?php echo $estado;
                            ?></td>
                        </tr>
                    <?php

                        }
                                ?>

                    </tbody>
                    <?php

                            }
                            ?>
                </table>
                <div id="info"></div>
                <br />
        </div>
        <?php

                        }
        ?>
    <div class="Limpiador"></div>
    <!-- InstanceEndEditable --></div>
    <?php include('derechos.html'); ?>
</div>
</body>
<!-- InstanceEnd --></html>
