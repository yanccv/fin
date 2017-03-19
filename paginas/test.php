<?php
include("../includes/classdb.php");
$bd= new dbMysql();
$bd->dbConectar();
if (strcmp($_SERVER['SERVER_NAME'], 'www.fondointeractivodenegocios.com.ve') == 0) {
    $IdArea="Conexión";
} else {
    $IdArea="Conexión";
    //$IdArea="ConexiÃ³n";
}
$ConArea=$bd->dbConsultar("select * from areas where area=?", array($IdArea));

if (!$bd->Error) {
    if ($ConArea->num_rows>0) {
        $Area=$ConArea->fetch_array();
        $banners=explode(":", $Area['banners']);
    } else {
        echo "Disculpe No Se Encontro Ningun Registro";
        echo $bd->getSql();
        exit();
    }
} else {
    echo $bd->MsgError;
    exit();
}
$ConConfig=$bd->dbConsultar("select c.tiempoactivo tiempo,c.minimoinicial minimo,c.mmaximo maximo,m.moneda ,c.conveniofpc as convenio from configuracion as c inner join monedas as m on m.id=c.monedabase where c.id=1");
if (!$bd->Error) {
    $FConfig=$ConConfig->fetch_array();
}
$bd->dbActualizar(
    "update clientes c
    	inner join paises p on p.id=c.pais
        inner join monedas m on p.monedaoficial=m.id
    set minimoap=(select minimoregistro from configuracion limit 1)
    where cedula='?'",
    array($_POST['cedula'])
);
//*m.cambio
//print_r($FConfig);
$ConInvita=$bd->dbConsultar("select c.cedula,c.nombre,c.apellido,date_format(c.fregistro,'%d/%m/%Y') fregistro,p.pais,c.pais idpais,p.monedaoficial idmoneda, m.moneda, m.cambio,c.minimoap,c.estado from clientes as c inner join paises as p on c.pais=p.id inner join monedas as m on p.monedaoficial=m.id where c.cconexion=? and c.cedula=? limit 1", array($_POST['cconexion'], $_POST['cedula']));
if ($bd->Error) {
    echo $bd->MsgError;
    exit();
}

$ConMontoDep=$bd->dbConsultar("select sum(monto_oficial) monto,sum(monto_base) montobase from movimientos where cliente=? and franquicia='FPC' and estado='N' and movimiento='Deposito' ", array($_POST['cedula']));
if (!$bd->Error) {
    $Depositado=$ConMontoDep->fetch_array();
    //print_r($Depositado);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Fondo Interactivo de Negocios</title>
<!--Archivos CSS-->
    <link href="../css/estructura.css" rel="stylesheet" type="text/css" />
    <!--Estilos de los Banner Animados-->
    <link rel="stylesheet" href="../slider/css/theme-metallic.css" />
    <!-- Estilos Para los Campos de los Formularios-->
    <link href="../css/formularios.css" rel="stylesheet" type="text/css" />
    <!-- Estilos Para las Herramientas UI -->
    <link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="../css/tabs.css" />
    <link rel="stylesheet" type="text/css" href="../css/styletabs.css" />
<!--FIN CSS -->


<!-- Archivos JS -->
    <!-- Version JQuery 1.10 -->
    <script type="text/javascript" src="../scripts/jquery/jquery.js" ></script>
    <!-- Version JQuery UI 1.10 -->
    <script type="text/javascript" src="../scripts/jquery/jqueryui.js" ></script>

    <!-- MENU       -->
    <script type="text/javascript" src="../scripts/menu/stmenu.js" ></script>

    <!-- Banners Animados-->
    <script type="text/javascript" src="../slider/scripts/jquery.anythingslider.js" ></script>
    <script type="text/javascript" src="../slider/scripts/jqueryeasing.js" ></script>

    <!--Hora y Fecha -->
    <script type="text/javascript" src="../scripts/calendario/datepicker.js" ></script>
    <script type="text/javascript" src="../scripts/calendario/timepicker.js" ></script>

    <!-- Controlador de Eventos -->
    <script type="text/javascript" src="../scripts/jquery/fcliente.js" ></script>
    <!--<script type="text/javascript" src="../scripts/tabs/modernizr.custom.04022.js"></script>-->
    <!-- Configuracion de Slider Rotativo-->
   	<script type="text/javascript">
		$(function(){
			$('#slider1').anythingSlider({
				theme           : 'metallic',
				mode			: 'fade',
				easing          : 'linear',
				buildStartStop  : false,
				autoPlay		: true,
				delay			: 5000
			});
		});
	</script>

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
<!-- FIN JS -->
</head>
<body>
    <div id="Contenedor">
        <?php
            include("banners.php");
        ?>
        <div id="slider">
            <ul id="slider1">
        <?php
                for ($i=0;$i<count($banners);$i++) {
                    if ((!empty($banners[$i])) && is_file($banners[$i])) {
                        echo "<li><img src='".$banners[$i]."' alt='".$IdArea."'></li>\n";
                    }
                }
        ?>
            </ul>
        </div>
        <?php
            include("menu.php");
        ?>
    <div id="Cuerpo">

    <?php
    if ($ConInvita->num_rows<=0) {
        ?>
        <div id='info' class='error'><center><?php echo 'Disculpe, Clave de Conexi&oacute;n Errada, Intente Nuevamente'; ?></center></div>
    <?php

    } else {
        $FInvita=$ConInvita->fetch_array();
        if ($FInvita['estado']=='A') {
    ?>
            <div id='info' class='error'><center><?php echo 'Disculpe, Ya Estas Activo'; ?></center></div>
    <?php
        } else {
    ?>
        <div class="Articulo">
            <div class="TituloArticulo">Activaci&oacute;n de la Franquicia de Participacion de Capitales y Mercadeo</div>
            <div class="SeparadorArticuloInterno"></div>
            <form action="pcliente.php" id="FormCliente" name="FormCliente">
                <div class="CampoCompleto">
                    <div class="Etiqueta">Cedula o ID: </div>
                    <div class="CampoCorto"><?php echo $FInvita['cedula']; ?></div><br />
                    <div class="Etiqueta">Nombre: </div>
                    <div class="CampoCorto"><?php echo $FInvita['nombre']; ?></div>
                    <div class="Etiqueta">Apellido: </div>
                    <div class="CampoCorto"><?php echo $FInvita['apellido']; ?></div><br />
                    <div class="Etiqueta">Fec de Registro: </div>
                    <div class="CampoCorto"><?php echo $FInvita['fregistro']; ?></div>
                    <div class="Etiqueta">Pais: </div>
                    <div class="CampoCorto"><?php echo $FInvita['pais']; ?></div>
                    <br />
                    <?php
                        $MinMoneda=($FInvita['minimoap']*$FInvita['cambio'])-$Depositado['monto'];
                        $MinBase = $FInvita['minimoap']-$Depositado['montobase'];
                        if ($MinMoneda < 0) {
                            $MinMoneda=0;
                            $MinBase=0;
                        }
                    ?>
                    <div class="EtiquetaLarga">Monto Minimo en <?php echo $FInvita['moneda']; ?>: </div>
                    <div class="CampoCorto"><?php echo number_format($FInvita['minimoap']*$FInvita['cambio'], 2, ",", "."); ?></div>
                    <div class="EtiquetaLarga">Monto Minimo en <?php echo $FConfig['moneda']; ?>: </div>
                    <div class="CampoCorto"><?php echo number_format($FInvita['minimoap'], 2, ",", "."); ?></div>
                    <br />
                    <div class="EtiquetaLarga">Monto Registrado en <?php echo $FInvita['moneda']; ?>: </div>
                    <div class="CampoCorto"><?php echo number_format($Depositado['monto'], 2, ",", "."); ?></div>
                    <div class="EtiquetaLarga">Monto Registrado en <?php echo $FConfig['moneda']; ?>: </div>
                    <div class="CampoCorto"><?php echo number_format($Depositado['montobase'], 2, ",", "."); ?></div>
                    <br />
                    <div class="EtiquetaLarga">Monto Restante en <?php echo $FInvita['moneda']; ?>: </div>
                    <div class="CampoCorto"><?php echo number_format($MinMoneda, 2, ",", "."); ?></div>
                    <div class="EtiquetaLarga">Monto Restante en <?php echo $FConfig['moneda']; ?>: </div>
                    <div class="CampoCorto"><?php echo number_format($MinBase, 2, ",", "."); ?></div><br />
                    <div class="Limpiador"></div>
                </div>
                <section class="tabs">
                    <input id="tab-1" type="radio" name="radio-set" class="tab-selector-1" checked="checked" />
                    <label for="tab-1" class="tab-label-1">Deposito o Transferencia</label>

                    <input id="tab-2" type="radio" name="radio-set" class="tab-selector-2" />
                    <label for="tab-2" class="tab-label-2">Tarjeta de Credito o Paypal</label>

                    <div class="clear-shadow"></div>

                    <div class="content">
                        <div class="content-1">
                            <div class="CampoCompleto">
                                <div class="Etiqueta">Banco/Servicio: </div>
                                <div class="CampoMedio">
                                <?php
                                    echo $bd->dbComboSimple("select b.id,b.banco from bancos as b inner join cuentas as c on c.banco=b.id and c.estado='A' where b.estado='A' and (b.pais=? or b.pais is null) and c.cliente is null", array($FInvita['idpais']), "CBanco", 0, array(1), null);
                                ?>
                                </div>
                                <div class="Etiqueta">Cuenta: </div>
                                <div class="CampoCorto">
                                    <select id="Cuenta" name="Cuenta"><option value="0">Seleccione Banco</option></select>
                                </div>
                                <div class="Limpiador"></div>
                            </div>
                            <div class="CampoCompleto">
                                <div class="EtiquetaLarga">Nro de Deposito o Referencia: </div>
                                <div class="CampoCorto">
                                    <input type="text" id="nroref" name="nroref" maxlength="15" size="20" />
                                </div>
                                <div class="Limpiador"></div>
                            </div>
                            <div class="CampoCompleto">
                                <div class="Etiqueta">Monto en <?php echo $FInvita['moneda']; ?>: </div>
                                <div class="CampoCorto">
                                    <input type="text" id="MontoDep" name="MontoDep" maxlength="15" size="15" />
                                    <input type="hidden" id="MonBase" name="MonBase" readonly="true" value="0.00"/>
                                    <input type="hidden" id="cambio" name="cambio" readonly="true" value="<?php echo $FInvita['cambio']; ?>"/>

                                    <input type="hidden" id="MonMinimo" name="MonMinimo" readonly="true" value="<?php echo $FInvita['minimoap']; ?>"/>
                                </div>
                                <div class="EtiquetaLarga">Monto en <?php echo $FConfig['moneda']; ?>: </div>
                                <div class="CampoCorto" id="MontoBase">0.00</div>
                                <div class="Limpiador"></div>
                            </div>
                            <div class="CampoCompleto">
                                <div class="Etiqueta">Fecha: </div>
                                <div class="CampoCorto"><input type="text" id="fecha" tipo='fechahora' name="fecha" maxlength="10" size="15" /></div>
                                <div class="Etiqueta">
                                    <a href='../paypal/src/paypalRequest.php?id=<?php echo $FInvita['cedula']; ?>&pay=<?php echo $FInvita['minimoap']; ?>'>
                                        <img src="../imagenes/paypal.png" border='0'\>
                                    </a>
                                </div>
                                <div class="Limpiador"></div>
                            </div>
                            <div class="CampoCompleto">
                                <div class="FormDerechos">
                                    <input type="hidden" id="idform" name="idform" value="RFPInicial"/>
                                    <input type="hidden" id="clave" name="clave" value="<?php echo $_GET['cinvita']; ?>"/>
                                    <input type="hidden" id="asociador" name="asociador" value="<?php echo $FInvita['cedula']; ?>"/>
                                    <textarea  cols="95"  rows="7"><?php echo $FConfig['convenio']; ?></textarea>
                                    <br />
                                    <input type="checkbox" id="Enviar" name="Enviar" value="Registrar" />Acepto las condiciones y terminos establecidos de ser participante<br />
                                    <strong>NOTA: Luego del registro cuentas con <?php echo $FConfig['tiempo']; ?> dias para realizar tu activaci&oacute;n, el monto Minimo de participacion es <?php  echo $FConfig['minimo']." ".$FConfig['moneda']; ?> y el maximo de <?php  echo $FConfig['maximo']." ".$FConfig['moneda']; ?></strong>
                                </div>
                            </div>
                            <div class="Limpiador"></div>
                        </div>
                        <div class="content-2">
                            <h2>Services</h2>
                            <p>Do you see any Teletubbies in here? Do you see a slender plastic tag clipped to my shirt with my name printed on it? Do you see a little Asian child with a blank expression on his face sitting outside on a mechanical helicopter that shakes when you put quarters in it? No? Well, that's what you see at a toy store. And you must think you're in a toy store, because you're here shopping for an infant named Jeb.</p>
                            <h3>Excellence</h3>
                            <p>Like you, I used to think the world was this great place where everybody lived by the same standards I did, then some kid with a nail showed me I was living in his world, a world where chaos rules not order, a world where righteousness is not rewarded. That's Cesar's world, and if you're not willing to play by his rules, then you're gonna have to pay the price. </p>
                        </div>
                    </div>
                </section>
                <div class="CampoCompleto">
                    <center><strong>NOTA: Hoy <?php echo date("d/m/Y"); ?>, El Cambio esta 1 <?php echo $FConfig['moneda']; ?> = <?php echo $FInvita['cambio']; ?> <?php echo $FInvita['moneda']; ?></strong></center>
                    <div class="Limpiador"></div>
                </div>
                <div class="CampoCompleto">
                    <div class="FormFin">
                        <input type="submit" id="Enviar" name="Enviar" value="Activar Participaci&oacute;n" />
                        <input type="hidden" id="idform" name="idform" value="RFPCActivar"/>
                        <input type="hidden" id="cedula" name="cedula" value="<?php echo $FInvita['cedula']; ?>"/>
                     </div>
                    <div class="Limpiador"></div>
                </div>
            </form>
        </div>
        <div id="info"></div>
        <?php

        }
    }
        ?>
        </div>
        <?php include('derechos.html'); ?>
    </div>
</body>
</html>
