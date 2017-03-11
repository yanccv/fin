<?php
    session_start();
    include("../includes/classdb.php");
    include("../includes/classvd.php");
    include("../includes/funcion.php");
    include("../includes/checkin.php");
    $CheckIn=0;
if (empty($_SERVER['HTTP_REFERER'])) {
    unset($_SESSION['cliente']);
    sleep(2);
    echo "Extrada No Autorizada, No Puedes Entrar Directamente a esta Pagina";
    exit();
} else {
    //Analisis Vinculo Interno
    if (!CheckOrigen()) {
        sleep(2);
    }
/*
        $dominio=$_SERVER['HTTP_HOST'];
        $viene=substr($_SERVER['HTTP_REFERER'],0,strrpos($_SERVER['HTTP_REFERER'],"/"));
        $viene=substr($viene,0,strrpos($viene,"/"));
        $cdefault="http://".$dominio;
        if (strcmp($viene,$cdefault)==0){
            //echo "Viene de enlace Interno";
        }else{
            //Vinculo Externo Espera 2 segundos en procesar cada peticion
            sleep(2);
        }
*/
    $Valid= new Validar();
    $bd = new dbMysql();
    $bd->dbConectar();

    if ($Valid->alfa($_GET['cedula'], 6, 20, "Cedula  o ID")) {
        $errores[]=$Valid->error;
    }
    if ($Valid->alfa($_GET['cescritorio'], 6, 20, "Clave de Escritorio")) {
        $errores[]=$Valid->error;
    }
    if ($errores) {
        //Revisar Si Ya Inicio Session*/
        if (isset($_SESSION['cliente']['cedula'])) {
            //Busqueda a ver Si Existe el Usuario De La Session
            $CheckIn=CheckCliente($bd);
        } else {
            //Se Muestra El Error XQ debio ingresar su cedula y clave
            echo '<script>alert("'.$errores[0].'\r\r\r\r\r\n'.$errores[1].'");  setTimeout("",2000);  window.location.href="index.php?op=Conexi'.urlencode('ó').'n"</script>';
            exit();
        }
    }
    if ($CheckIn==0) {
        switch ($_SERVER["REQUEST_METHOD"]) {
            case "GET":
                $cedula=$_GET['cedula'];
                $cescritorio=$_GET['cescritorio'];
                break;
            case "POST":
                $cedula=$_POST['cedula'];
                $cescritorio=$_POST['cescritorio'];
                break;
        }
        //$ConUsuario=$bd->dbConsultar("select c.cedula,c.nombre,c.apellido,c.fpc ,c.fpm from clientes as c inner join franquiciados as f on (c.cedula=f.cliente and f.estado='A') where c.cedula=? and c.cescritorio=? and c.estado='A' and curdate() between f.inicio and f.fin limit 1",array($_GET['cedula'],$_GET['cescritorio']));
        $ConUsuario=$bd->dbConsultar("select c.cedula,c.nombre,c.apellido,c.fpc ,c.fpm,c.estado from clientes as c inner join franquiciados as f on c.cedula=f.cliente where c.cedula=? and c.cescritorio=? limit 1", array($_GET['cedula'], $_GET['cescritorio']));
        if (!$bd->Error) {
            if ($ConUsuario->num_rows>0) {
                $Usuario=$ConUsuario->fetch_array();
                $_SESSION['cliente']['cedula'] = $Usuario['cedula'];
                $_SESSION['cliente']['nombre'] = $Usuario['nombre'];
                $_SESSION['cliente']['apellido'] = $Usuario['apellido'];
                $_SESSION['cliente']['fpc'] = $Usuario['fpc'];
                $_SESSION['cliente']['fpm'] = $Usuario['fpm'];
                $_SESSION['cliente']['estatus'] = $Usuario['estado'];
            } else {
                echo utf8_encode('<script>alert("Login o Clave de Escritorio Incorrecta");  setTimeout("",2000);  window.location.href="index.php?op=Conexi'.urlencode('ó').'n"</script>');
                exit();
            }
        } else {
            echo utf8_encode('<script>alert("Error Conectado a la Base de Datos");  setTimeout("",2000);  window.location.href="index.php?op=Conexi'.urlencode('ó').'n"</script>');
            exit();
        }
    }
    //BUSQUEDA DE INFORMACION DEL FRANQUICIADO
    //$ConCliente=$bd->dbConsultar("select c.cedula,c.nombre,c.apellido,c.fnac,c.direccion,c.telefonos,c.email,c.asociador,c.pais,p.pais npais,c.fpc,c.fpm,c.estado,f.inicio,f.fin,f.monto,m.moneda mlocal,mo.moneda mbase from clientes as c inner join paises as p on p.id=c.pais inner join monedas as m on p.monedaoficial=m.id inner join monedas as mo on mo.id=m.monedabase inner join franquiciados as f on (c.cedula=f.cliente and f.estado='A' ) where c.cedula=? and curdate() between f.inicio and f.fin  limit 1 ", array($_SESSION['cliente']['cedula']));
    $ConCliente=$bd->dbConsultar("select c.cedula,c.nombre,c.apellido,c.fnac,c.direccion,c.telefonos,c.email,c.asociador,c.pais,p.pais npais,c.fpc,c.fpm,c.estado,f.inicio,f.fin,f.monto,m.id idmoneda,m.moneda mlocal,mo.moneda mbase from clientes as c inner join paises as p on p.id=c.pais inner join monedas as m on p.monedaoficial=m.id inner join monedas as mo on mo.id=m.monedabase inner join (select cliente,inicio,fin,monto,estado from franquiciados where cliente=? order by fin desc limit 1) as f on c.cedula=f.cliente  where c.cedula=? limit 1 ", array($_SESSION['cliente']['cedula'], $_SESSION['cliente']['cedula']));
    if (!$bd->Error) {
        if ($ConCliente->num_rows>0) {
            $error=0;
            $Cliente=$ConCliente->fetch_array();
            $_SESSION['cliente']['idpais']=$Cliente['pais'];
            $_SESSION['cliente']['npais']=$Cliente['npais'];
            //Consulta de Datos del Afiliador
            $ConAfiliador=$bd->dbConsultar("SELECT c.nombre,c.apellido,f.monto from clientes as c left join franquiciados as f on (c.cedula=f.cliente and f.estado='A') where c.cedula=? limit 1", array($Cliente['asociador']));
            if (!$bd->Error) {
                $Afiliador=$ConAfiliador->fetch_array();
            } else {
                echo $bd->MsgError;
                exit();
            }
            //Consulta para el calculo del % de Participacion
            $x=PorcentajeParticipacion($bd, $Cliente['monto']);

            //Consulta Para Buscar Los Asociados Directos y indirectos
            $todos=0;
            $activos=0;
            $directos=0;
            $nivel=0;
            $porc=100;
            $monto=0;
            RecorrerAsociados($bd, $Cliente['cedula'], $todos, $activos, $directos, $nivel, $porc, $monto);
            //Consultar El Monto del Ultimo Mes Liquidado
            $ConLiq=$bd->dbConsultar(
                "
                SELECT m.monto_oficial,m.monto_base FROM movimientos as m
                inner join franquiciados as f on f.cliente=m.cliente
                where m.estado='A' and  m.franquicia='FCG' and m.movimiento='Liquidez'
                and m.fautoriza BETWEEN f.inicio and f.fin and m.cliente=?
                order by m.fautoriza desc limit 1",
                array($_SESSION['cliente']['cedula'])
            );
            if (!$bd->Error) {
                $liquidez=$ConLiq->fetch_array();
            } else {
                echo $bd->MsgError;
                exit();
            }

            //Consulta del Monto en Aquellos Retiros Que no Han Sido Procesados
            $SDiferido=SDiferido($bd, $Cliente['cedula']);
            $_SESSION['cliente']['sdiferido']=$SDiferido;

            //Consulta del Saldo Acumulado del Participante
            $Saldo=Saldo($bd, $Cliente['cedula'])-$SDiferido;
            $_SESSION['cliente']['saldo']=$Saldo;

            //Consulta para buscar la fecha del ultimo calculo y el valor de la moneda local con respecto a la moneda base_convert
            $conMoneda=$bd->dbConsultar("select cambio,hasta from hmonedas where id=? and (select fautoriza from movimientos where cliente =? and movimiento='Liquidez' order by fautoriza desc limit 1) between desde and hasta", array($Cliente['idmoneda'], $Cliente['cedula']));
            if (!$bd->Error) {
                $HMoneda=$conMoneda->fetch_array();
            }
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Clientes.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Mi Escritorio</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link href="../css/estructura.css" rel="stylesheet" type="text/css" />
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/menu/stmenu.js" ></script>
<script type="text/javascript" src="../scripts/jquery/jquery.js" ></script>
<script type="text/javascript" src="../scripts/\ajaxUpload/ajaxUpload20.js" ></script>
<script type="text/javascript" src="../scripts/jquery/ffranquiciados.js" ></script>
    <!-- Estilos Para las Herramientas UI -->
<link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />

     <!--Hora y Fecha -->
<script type="text/javascript" src="../scripts/calendario/datepicker.js" ></script>
        <!-- Version JQuery UI 1.10 -->
<script type="text/javascript" src="../scripts/jquery/jqueryui.js" ></script>
<script>
    $(function() {
    	// Botón para subir la firma
		var btn_firma = $('#addImage'), interval;
			new AjaxUpload('#addImage', {
				action: 'uploadPhoto.php',
				onSubmit : function(file , ext){
					if (! (ext && /^(jpg|png|gif)$/.test(ext))){
						// extensiones permitidas
						alert('Sólo se permiten Imagenes .jpg , .png o gif');
						// cancela upload
						return false;
					} else {
						$('#loaderAjax').show();
						btn_firma.text('Espere por favor');
						this.disable();
					}
				},
				onComplete: function(file, response){

					// alert(response);

					btn_firma.text('Cambiar Imagen');

					respuesta = $.parseJSON(response);

					if(respuesta.respuesta == 'done'){
						$('#fotografia').removeAttr('scr');
						$('#fotografia').attr('src','../clientes/' + respuesta.fileName);
						$('#loaderAjax').show();
						// alert(respuesta.mensaje);
					}
					else{
						alert(respuesta.mensaje);
					}

					$('#loaderAjax').hide();
					this.enable();
				}
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
            <?php
            if ($_SESSION['cliente']['estatus'] == 'A') {
                ?>
            <script type="text/javascript" src="../scripts/menu/cliente.js"></script>
            <?php

            }
            ?>

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
    <div id="MenuCuerpo">
        <div class="Articulo">
            <div class="TituloArticulo">Datos del Asociador</div>
            <?php if ($ConAfiliador->num_rows<=0) {
    ?>
            <div class="Item"><center>Es Un Asociado Directo, No Tiene Asociador</center></div>
            <?php

} else {
    ?>
                <?php
                   //Consulta para el calculo del % de Participacion del Asociador
                   $xafiliador=PorcentajeParticipacion($bd, $Afiliador['monto']);
    ?>
                <div class="Item">Nombre: <?php echo $Afiliador['nombre'];
    ?></div>
                <div class="Item">Apellido: <?php echo $Afiliador['apellido'];
    ?></div>
                <?php
                if (empty($Afiliador['monto'])) {
                    echo "<div class='Item'>Cliente No Activo</div>";
                } else {
                    echo "<div class='Item'>% de Participación: ". round($xafiliador, 2)."</div>";
                }
}
            ?>
            <div class="TituloArticulo">Mi Participación</div>
            <div class="Item"><center>Desde <?php echo FUser($Cliente['inicio']); ?> al <?php echo FUser($Cliente['fin']); ?></center></div>
            <div class="Item">Asociados Directos: <?php echo $directos; ?></div>
            <div class="Item">Asociados Totales Activos: <?php echo $activos; ?></div>
            <div class="Item">Asociados Totales: <?php echo $todos; ?></div>
            <div class="Item"><strong>Moneda Local: <?php echo $Cliente['mlocal']; ?></strong></div>
            <div class="Item">Monto en <?php echo $Cliente['mbase'].": ". number_format($Cliente['monto'], 2, ",", "."); ?></div>
            <div class="Item">% de Participación: <?php echo number_format($x, 2, ",", "."); ?>%</div>
            <div class="Item">Liquidez Ultimo Mes: <?php echo number_format($liquidez['monto_base'], "2", ",", "."); ?></div>
            <div class="Item">Saldo Diferido: <?php echo number_format($SDiferido, "2", ",", "."); ?></div>
            <div class="Item">Saldo Dispobible: <?php echo number_format($Saldo, "2", ",", "."); ?></div>
            <div class="Item"><strong>Ultimo Calculo: <?php echo FUser($HMoneda['hasta']) ?></strong></div>
            <div class="Item">1 <?php echo $Cliente['mbase'] ?> = <?php echo number_format($HMoneda['cambio'], "2", ",", ".").' '.$Cliente['mlocal']; ?></div>

        </div>
    </div>

    <div id="DetalleCuerpo">
        <div class="Articulo" id="FormDatos">
                <div class="TituloArticulo">Datos Personales del Afiliado</div>
                <div class='SeparadorArticuloInterno'></div>
                <div class="ContenidoArticulo">
                    <section class="contentLayout" id="contentLayout">
            	    	<div id="contenedorImagen">
                            <?php
                            $foto = '../clientes/nofoto.jpg';
                            if (file_exists('../clientes/'.$_SESSION['cliente']['cedula'].'.gif')) {
                                $foto ='../clientes/'.$_SESSION['cliente']['cedula'].'.gif';
                            } elseif (file_exists('../clientes/'.$_SESSION['cliente']['cedula'].'.jpg')) {
                                $foto ='../clientes/'.$_SESSION['cliente']['cedula'].'.jpg';
                            } elseif (file_exists('../clientes/'.$_SESSION['cliente']['cedula'].'.png')) {
                                $foto ='../clientes/'.$_SESSION['cliente']['cedula'].'.png';
                            }
                            ?>
                            <img id="fotografia" class="fotografia" src="<?php echo $foto; ?>">
            	    	</div>
            	    	<button class="boton" id="addImage">Cambiar Imagen</button>
            	    	<div class="loaderAjax" id="loaderAjax">
            		    	<img src="../imagenes/default-loader.gif">
            	       		<span>Actualizando Foto</span>
            		    </div>
            	    </section>
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Cedula o ID: </div>
                        <div class="CampoCorto"><?php echo $Cliente['cedula']; ?></div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Nombre: </div>
                        <div class="CampoCorto"><?php echo $Cliente['nombre']; ?></div>
                        <div class="EtiquetaCorta">Apellido: </div>
                        <div class="CampoCorto"><?php echo $Cliente['apellido']; ?></div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Fec. Nac: </div>
                        <div class="CampoCorto"><?php echo FUser($Cliente['fnac']); ?></div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Dirección: </div>
                        <div class="CampoLargo"><?php echo $Cliente['direccion']; ?></div>
                        <div class="Limpiador"></div>
                    </div>
                    <?php
                        $tele=explode("|", $Cliente['telefonos']);
                    ?>
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Telefono 1: </div>
                        <div class="CampoCorto"><?php echo $tele[0]; ?></div>
                        <div class="EtiquetaCorta">Telefono 2: </div>
                        <div class="CampoCorto"><?php echo $tele[1]; ?></div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Telefono 3 </div>
                        <div class="CampoCorto"><?php echo $tele[2]; ?></div>
                        <div class="EtiquetaCorta">Correo: </div>
                        <div class="CampoCorto"><?php echo $Cliente['email']; ?></div>
                        <div class="Limpiador"></div>
                    </div>
                </div>
                <div id="info"></div>
                <div class="Limpiador"></div>
        </div>
        <div class="Articulo" id="FormClave">
                <div class="TituloArticulo">Cambio de Clave de Escritorio</div>
                <div class='SeparadorArticuloInterno'></div>
                <form id="FormFranquiciado" name="FormFranquiciado" method="POST"  enctype="multipart/form-data" action="pfranquiciados.php">
                <div class="ContenidoArticulo">
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Clave Actual: </div>
                        <div class="CampoCorto">
                            <input type="password" id="claact" name="claact"  maxlength="20" size="20" />
                            <input type="hidden" id="idform" name="idform" value="CambiarClave" readonly="true" maxlength="20" size="20" />
                        </div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Nueva Clave: </div>
                        <div class="CampoCorto"><input type="password" maxlength="20" size="20" id="nuecla" name="nuecla" /></div>
                        <div class="Etiqueta">Repita Nueva Clave: </div>
                        <div class="CampoCorto"><input type="password" maxlength="20" size="20" id="repcla" name="repcla" /></div>
                        <div class="Limpiador"></div>
                    </div>

                    <div class="CampoCompleto">
                        <div class="FormFin"><input type="submit" id="Enviar" name="Enviar" value="Cambiar Clave" /></div>
                        <div class="Limpiador"></div>
                    </div>
                </div>
                </form>
                <div id="info"></div>
        </div>
        <div class="Articulo" id="FormClaveInvita">
                <div class="TituloArticulo">Cambio de Clave de Invitaci&oacute;n</div>
                <div class='SeparadorArticuloInterno'></div>
                <form id="FormFranquiciado" name="FormFranquiciado" method="POST"  enctype="multipart/form-data" action="pfranquiciados.php">
                <div class="ContenidoArticulo">
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Nueva Clave: </div>
                        <div class="CampoCorto"><input type="password" maxlength="20" size="20" id="nuecla" name="nuecla" /></div>
                        <input type="hidden" id="idform" name="idform" value="CambiarClaveInvita" readonly="true" maxlength="20" size="20" />
                        <div class="Etiqueta">Repita Nueva Clave: </div>
                        <div class="CampoCorto"><input type="password" maxlength="20" size="20" id="repcla" name="repcla" /></div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="FormFin"><input type="submit" id="Enviar" name="Enviar" value="Cambiar Clave" /></div>
                        <div class="Limpiador"></div>
                    </div>
                </div>
                </form>
                <div id="info"></div>
        </div>
    </div>
    <div class="Limpiador"></div>
    <!-- InstanceEndEditable --></div>
    <?php include('derechos.html'); ?>
</div>
</body>
<?php //unset($_SESSION['cliente']); ?>
<!-- InstanceEnd --></html>
