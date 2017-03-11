<?php
    session_start();
	 include("../includes/classdb.php");
    $bd= new dbMysql();
    $bd->dbConectar();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Clientes.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Opciones de Publicidad</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link href="../css/estructura.css" rel="stylesheet" type="text/css" />
<link href="../css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/menu/stmenu.js" ></script>
<script type="text/javascript" src="../scripts/jquery/jquery.js" ></script>
<script type="text/javascript" src="../scripts/jquery/ffranquiciados.js" ></script>
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
    <?php

        if ($_SESSION['cliente']['fpm']==0){
    ?>
        <div class="Articulo" id="FormDatos">
                <div class="TituloArticulo">Afilición a la Franquicia de Publicidad y Mercadeo</div>
                <div class='SeparadorArticuloInterno'></div>
                <form id="FormFranquiciado" name="FormFranquiciado" method="POST"  enctype="multipart/form-data" action="pfranquiciados.php">
                <input type="hidden" id="idform" name="idform" value="FPMAfiliarse" />
                <div class="ContenidoArticulo">
                    <div class="CampoCompleto">
                    <strong>NOTA:</strong> Aun no estas afiliado a la franquicia de participacion de capitales si deseas hacerlo acepta los termino y condiciones y click en afiliarme
                    <?php
                        $ConConfig=$bd->dbConsultar("select c.conveniofpm as convenio from configuracion as c inner join monedas as m on m.id=c.monedabase where c.id=1");
                        if (!$bd->Error){
                            $FConfig=$ConConfig->fetch_array();
                        }else{
                            echo $bd->MsgError;
                        }
                    ?>
                    </div>
                    <div class="CampoCompleto">
                        <center><textarea cols="100"  rows="7"><?php echo $FConfig['convenio']; ?></textarea><br />
                            <input type="checkbox" id="terminos" name="terminos" value="Registrar" />Acepto las condiciones y terminos establecidos de ser participante<br />
                        </center>
                    </div>
                    <div class="FormFin">
                        <input type="submit" id="Enviar" name="Enviar"  value="Afiliarme" />
                    </div>
                </div>
                </form>
                <div id="info"></div>
                <div class="Limpiador"></div>
        </div>
     <?php }else{ ?>
        <div class="Articulo" id="FormDatos">
                <div class="TituloArticulo">Opciones de la Franquicia de Publicidad y Mercadeo</div>
                <div class='SeparadorArticuloInterno'></div>
                <div class="ContenidoArticulo">
                    <div class="ButtonsFPM"><a href="clasificado.php">Clasificados</a></div>
                    <div class="ButtonsFPM"><a href="banner.php">Banner Publicitarios</a></div>
                    <div class="ButtonsFPM"><a href="paginas.php">Cupones Web</a></div>
                    <!--<div class="ButtonsFPM"><a href="cupones.php">Cupones Web</a></div>-->
                    <div class="Limpiador"></div><br />
                    <div class="ButtonsFPM"><a href="listados.php?tipolis=ClasificadosCliente&page=areas">Mis Clasificados</a></div>
                    <div class="ButtonsFPM"><a href="listados.php?tipolis=BannersCliente&page=areas">Mis Banners Publicitarios</a></div>
                    <div class="ButtonsFPM"><a href="cupones.php">Mis Cupones Web</a></div>
                    <div class="Limpiador"></div><br />
                </div>
                <div id="info"></div>
                <div class="Limpiador"></div>
        </div>
     <?php  }?>
    <!-- InstanceEndEditable --></div>
    <?php include('derechos.html'); ?>
</div>
</body>
<!-- InstanceEnd --></html>
