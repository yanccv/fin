<?php
//Pagina Central
    include("../includes/classdb.php");
    include("../includes/funcion.php");
    $bd= new dbMysql();
    $bd->dbConectar();
/*
    if (!isset($_GET['op']))
        $_GET['op']="Inicio";
    $IdArea=$_GET['op'];
    $IdArea="Conexion";
*/
    $IdArea="Conexión";
    $ConArea=$bd->dbConsultar("select * from areas where area=?", array($IdArea));
    if (!$bd->Error) {
        $Area=$ConArea->fetch_array();
        //$banners=explode(":",$Area['banners']);
    }

    //Busco los Banner Princiapal de la Imagen
    $ConABanner=$bd->dbConsultar("select * from banners where idarea=? and isnull(posicion) limit 1", array($Area['id']));
    if (!$bd->Error) {
        $Abanner=$ConABanner->fetch_array();
        //$banners=explode(":",$Area['banners']);
    }

     //Busqueda de los banners que se deben publicar luego de este articulo
     $ConBanners=$bd->dbConsultar("select db.idbanner,db.titulo,db.banner,db.enlace,b.posicion,b.ancho,b.alto,b.rotativo,b.imagen,b.cantidad from banners as b left join detabanner as db on b.id=db.idbanner left join publicaciones as p on p.id=db.idplan where estado='A' and curdate() between desde and date_add(desde, interval p.dias day) and b.idarea=? and isnull(b.posicion) order by b.idarea,b.posicion", array($Area['id']));
     $i=0;
     if (!$bd->Error) {
         if ($ConBanners->num_rows>0) {
             while ($Banner=$ConBanners->fetch_array()) {
                 $banners[$i++]="<li><a href='".$Banner['enlace']."' target='_blank'><img border='0' src='".$Banner['banner']."'  title='".$Banner['titulo']."' width='".$Banner['ancho']."'  height='".$Banner['alto']."'></a></li>";
             }
         }
     }
      for ($j=$ConBanners->num_rows;$j<$Abanner['cantidad'];$j++) {
          $banners[$i++]="<li><img src='".$Abanner['imagen']."' title='Banner Disponible'     width='".$Abanner['ancho']."' height='".$Abanner['alto']."'></li>";
      }

    //Informacion Perteneciente a esta Pagina Como Tal
    $ConConfig=$bd->dbConsultar("select c.tiempoactivo tiempo,c.minimoinicial minimo,c.mmaximo maximo,m.moneda ,c.conveniofpc as convenio from configuracion as c inner join monedas as m on m.id=c.monedabase where c.id=1");
    if (!$bd->Error) {
        $FConfig=$ConConfig->fetch_array();
    }
    $ConInvita=$bd->dbConsultar("select c.cedula,c.nombre,c.apellido,date_format(c.fregistro,'%d/%m/%Y') fregistro,p.pais,f.monto from clientes as c inner join paises as p on c.pais=p.id left join franquiciados as f on f.cliente=c.cedula where c.cinvita=? limit 1", array($_POST['cinvita']));
    if ($bd->Error) {
        echo $bd->MsgError;
        exit();
    }
    //echo $bd->getSql();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Publica.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Registro de Nuevos Participantes</title>

<!-- InstanceEndEditable -->
 	<!--Archivos CSS-->
    <link href="../css/estructura.css" rel="stylesheet" type="text/css" />
    <link href="../css/clasificados.css"  rel="stylesheet" type="text/css" />
    <link href="../css/formularios.css"  rel="stylesheet" type="text/css" />
    <!--Estilos de los Banner Animados-->
    <link rel="stylesheet" href="../slider/css/theme-metallic.css">
	<!--FIN CSS -->

<!-- Archivos JS -->
	<!-- Version JQuery 1.10 -->
	<script type="text/javascript" src="../scripts/jquery/jquery.js" ></script>
	<script type="text/javascript" src="../scripts/jquery/jqueryui.js" ></script>
	<script type="text/javascript" src="../scripts/jquery/finternauta.js" ></script>

    <!-- MENU       -->
    <script type="text/javascript" src="../scripts/menu/stmenu.js" ></script>

	<!--Files Para Slider-->
    <script type="text/javascript" src="../slider/scripts/jquery.anythingslider.js" ></script>
    <script type="text/javascript" src="../slider/scripts/jqueryeasing.js" ></script>
	<script src="../scripts/swfobject_modified.js" type="text/javascript"></script>
	<script>
		$(function(){

			$('#slider1').anythingSlider({
			    enableArrows        : false,
            	enableNavigation    : false,
	            enableKeyboard      : false,
	            toggleControls      : true,
				theme           : 'metallic',
				mode			: 'fade',
				easing          : 'linear',
				buildStartStop  : false,
				autoPlay		: true,
				delay			: 10000
			});
		});
	</script>
	<!-- Fin Files Slider	-->
<!--FIN Archivos JS-	-->
<!-- InstanceBeginEditable name="head" -->
<!--Archivos CSS-->
    <!-- Estilos Para los Campos de los Formularios-->
    <link href="../css/formularios.css" rel="stylesheet" type="text/css" />
    <!-- Estilos Para las Herramientas UI -->
    <link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />
<!--FIN CSS -->

<!--	SCRIPTS-->
    <!--Hora y Fecha -->
    <script type="text/javascript" src="../scripts/calendario/datepicker.js" ></script>
    <script type="text/javascript" src="../scripts/calendario/timepicker.js" ></script>

    <!-- Controlador de Eventos -->
    <script type="text/javascript" src="../scripts/jquery/fcliente.js" ></script>

    <!-- Configuracion de Widget de Calendario -->
    <script>
        var fecha= new Date();
        var dia=fecha.getDate();
        //alert(fecha.getDay());
        //alert(hoy);
        var mes=fecha.getMonth()+1;
        //alert(fecha.getMonth());
        var anio=fecha.getFullYear()-18;
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
<!--	FIN SCRIPTS	-->

<!-- InstanceEndEditable -->
</head>

<body>
<div id="Contenedor">
	<div id="Banner">
   <?php include("banners.php");    ?>
    </div>
  <div id="slider">
	<ul id="slider1">
    	<?php
            $usados=array();
            for ($i=0;$i<count($banners);$i++) {
                $enc=false;
                do {
                    $id=rand(0, count($banners)-1);
                    if (!in_array($id, $usados)) {
                        $usados[$i]=$id;
                        $enc=true;
                    }
                } while (!$enc);
                echo $banners[$id];
            }
        ?>
	</ul>
  </div>
  <?php include("menu.php"); ?>
    <div id="Cuerpo">
    <!-- InstanceBeginEditable name="AreaCentral" -->

    <?php
    if ($ConInvita->num_rows<=0) {
        ?>
        <div id='info' class='error'><center><?php echo 'Disculpe, Clave de Invitaci&oacute;n Errada, Intente Nuevamente'; ?></center></div>
    <?php

    } else {
        $FInvita=$ConInvita->fetch_array();
        $ConAsociados=$bd->dbConsultar("select count(asociador) asociados from clientes where asociador=?", array($FInvita['cedula']));
        if ($bd->Error) {
            echo $bd->MsgError;
        } else {
            $FilaAsociados=$ConAsociados->fetch_array();
        } ?>
        <div class="Articulo">
            <div class="TituloArticulo">Datos del Asociador</div>
            <div class="SeparadorArticuloInterno"></div>
                <div class="CampoCompleto">
                    <div class="CampoFoto">
    <?php
        $ext=array('.jpg', '.gif', '.png');
        foreach ($ext as $key => $value) {
            if (file_exists('../clientes/'.$FInvita['cedula'].$value)) {
                ?>
                    <img width="120" src="../clientes/<?php echo $FInvita['cedula'].$value ?>" height="120" />
                <?php
                break;
            }
        } ?>

                    </div>
                    <div class="Etiqueta">Nombre: </div>
                    <div class="CampoCorto"><?php echo $FInvita['nombre']; ?></div>
                    <div class="Etiqueta">Apellido: </div>
                    <div class="CampoCorto"><?php echo $FInvita['apellido']; ?></div><br />
                    <div class="Etiqueta">Fec de Registro: </div>
                    <div class="CampoCorto"><?php echo $FInvita['fregistro']; ?></div>
                    <div class="Etiqueta">Pais: </div>
                    <div class="CampoCorto"><?php echo $FInvita['pais']; ?></div>
                    <div class="Etiqueta">Asociados Directos: </div>
                    <div class="CampoCorto"><?php echo $FilaAsociados['asociados']; ?></div>
                    <div class="Etiqueta">% de Participaci&oacute;n: </div>
                    <div class="CampoCorto"><?php echo number_format(PorcentajeParticipacion($bd, $FInvita['monto']), 2, ",", ".")."%"; ?></div>


                    <div class="Limpiador"></div>
                </div>
        </div>
        <div class="Articulo">
            <div class="TituloArticulo">Registro de Nuevos Participantes</div>
            <div class="SeparadorArticuloInterno"></div>
            <form action="pcliente.php" id="FormCliente" name="FormCliente">

                <div class="CampoCompleto">
                    <div class="EtiquetaCorta">C&eacute;dula o ID: </div>
                    <div class="CampoCorto"><input type="text" id="cedula" name="cedula" maxlength="8" size="10" /></div>
                    <div class="EtiquetaCorta">Nombre: </div>
                    <div class="CampoCorto"><input type="text" id="nombre" name="nombre" maxlength="30" size="25" /></div>
                    <div class="EtiquetaCorta">Apellido: </div>
                    <div class="CampoCorto"><input type="text" id="apellido" name="apellido" maxlength="30" size="25" /></div>
                    <div class="Limpiador"></div>
                </div>
                <div class="CampoCompleto">
                    <div class="EtiquetaCorta">Fecha Nac: </div>
                    <div class="CampoCorto"><input type="text" tipo="fechahora" id="fnac" name="fnac" maxlength="10" size="15" /></div>
                    <div class="EtiquetaCorta">Direcci&oacute;n: </div>
                    <div class="CampoCorto"><input type="text" id="direccion" name="direccion" maxlength="100" size="65" /></div>
                    <div class="Limpiador"></div>
                </div>
                <div class="CampoCompleto">
                    <div class="EtiquetaCorta">Tel&eacute;fono 1: </div>
                    <div class="CampoCorto"><input type="text" id="tele1" placeholder="XXXX-XXXXXXX" name="tele1" maxlength="12" size="15" /></div>
                    <div class="EtiquetaCorta">Tel&eacute;fono 2: </div>
                    <div class="CampoCorto"><input type="text" id="tele2" placeholder="XXXX-XXXXXXX" name="tele2" maxlength="12" size="15" /></div>
                    <div class="EtiquetaCorta">Tel&eacute;fono 3: </div>
                    <div class="CampoCorto"><input type="text" id="tele3" placeholder="XXXX-XXXXXXX" name="tele3" maxlength="12" size="15" /></div>
                    <div class="Limpiador"></div>
                </div>
                <div class="CampoCompleto">
                    <div class="EtiquetaCorta">Pa&iacute;s: </div>
                    <div class="CampoCorto"><?php   echo $bd->dbComboSimple("select id, pais from paises", array(), "pais", 0, array(1), null); ?></div>
                    <div class="EtiquetaCorta">Email: </div>
                    <div class="CampoCorto"><input type="text" id="correo" name="correo" maxlength="70" size="65" /></div>
                    <div class="Limpiador"></div>
                </div>
                <div class="CampoCompleto">
                    <div class="FormDerechos">
                        <input type="hidden" id="idform" name="idform" value="RFPInicial"/>
                        <input type="hidden" id="clave" name="clave" value="<?php echo $_GET['cinvita']; ?>"/>
                        <input type="hidden" id="asociador" name="asociador" value="<?php echo $FInvita['cedula']; ?>"/>
                        <strong>NOTA: Luego del registro cuentas con <?php echo $FConfig['tiempo']; ?> dias para realizar tu activaci&oacute;n, el monto Minimo de participacion es <?php  echo $FConfig['minimo']." ".$FConfig['moneda']; ?> y el maximo de <?php  echo $FConfig['maximo']." ".$FConfig['moneda']; ?></strong>
                    </div>
                </div>
                <div class="CampoCompleto">
                    <div class="FormFin"><input type="submit" id="Enviar" name="Enviar" value="Registrar" /></div>
                    <div class="Limpiador"></div>
                </div>
            </form>
        </div>
        <div id="info"></div>
        <?php

    }
        ?>
	<!-- InstanceEndEditable -->
    </div>
    <?php include('derechos.html'); ?>
</div>

</body>
<!-- InstanceEnd --></html>
