<?php
    include("../includes/classdb.php");
    include("../includes/funcion.php");
    $bd= new dbMysql();
    $bd->dbConectar();
    $IdArea="Clasificados";
    $ConArea=$bd->dbConsultar("select * from areas where area=?", array($IdArea));
    if (!$bd->Error) {
        $Area=$ConArea->fetch_array();
    }
    $search=true;
    if (isset($_GET['search']) && $_GET['search']=='false') {
        $search=false;
    }

    //Busco los Banner Princiapal de la Imagen
    $ConABanner=$bd->dbConsultar(
        "select * from banners where idarea=? and isnull(posicion) limit 1",
        array($Area['id'])
    );
    if (!$bd->Error) {
        $Abanner=$ConABanner->fetch_array();
        //$banners=explode(":",$Area['banners']);
    }

     //Busqueda de los banners que se deben publicar luego de este articulo
     $ConBanners=$bd->dbConsultar("
select db.idbanner,db.titulo,db.banner,db.enlace,b.posicion,b.ancho,b.alto,b.rotativo,b.imagen,b.cantidad
from
	banners as b
	left join detabanner as db on b.id=db.idbanner
	left join publicaciones as p on p.id=db.idplan

where
	estado='A' and curdate() between desde and date_add(desde, interval p.dias day) and b.idarea=? and isnull(b.posicion)
order by
	b.idarea,b.posicion
     ", array($Area['id']));
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Publica.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Ver Clasificado</title>
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
<link rel="stylesheet" href="../css/lightbox.css">
<script type="text/javascript" src="../scripts/jquery/lightbox.js"></script>
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
        if (!empty($IdArea) && ($IdArea=='Clasificados') && $search) {
            include("bclasificados.php");
        }

            //$ConClasificado=$bd->dbConsultar("select c.* from clasificados as c inner join paises as p on where c.id=? and c.estado='A'",array((int) $_GET['id']));
         unset($ConClasificado);
            $ConClasificado=$bd->dbConsultar("select ca.categoria,c.titulo,c.descripcion,c.direccion,c.imagenes,c.factivo,p.pais,e.estado,datediff(date_add(c.factivo, interval pu.dias day),now()) fin from clasificados as c inner join publicaciones as pu on pu.id=c.dias inner join paises as p on p.id=c.idpais inner join estados as e on (e.pais=c.idpais and e.id=c.idestado) inner join categorias as ca on ca.id=c.categoria where c.estado='A' and c.id=? and curdate() between c.factivo and date_add(c.factivo, interval pu.dias day)", array((int) $_GET['id']));
            if (!$bd->Error) {
                if ($ConClasificado->num_rows>0) {
                    $Clasificado=$ConClasificado->fetch_array();
                    $imagenes=explode("|", $Clasificado['imagenes']);
                    $imagenesvp=$imagenes;
                    for ($i=0;$i<count($imagenesvp);$i++) {
                        if (!empty($imagenesvp[$i])) {
                            $name[$i]=substr($imagenesvp[$i], strrpos($imagenesvp[$i], "/")+1, -4);
                            $file[$i]=substr($imagenesvp[$i], strrpos($imagenesvp[$i], "/")+1);
                            $imagenesvp[$i]=substr($imagenesvp[$i], 0, strrpos($imagenesvp[$i], "/")+1)."vp/".$file[$i];
                        }
                    }
                    ?>
		        <div class="Articulo" id="IdArticulo" >
                <div class="TituloArticulo"><?php echo $Clasificado['titulo'];
                    ?></div>
                <div class='SeparadorArticuloInterno'></div>
                <div class="ContenidoArticulo" id="IdContenidoArticulo">
                	<div class="CampoCompleto">
                  	<div class="Etiqueta">Descripción: </div>
                     <div class="CampoMuyLargo"><?php echo $Clasificado['descripcion'];
                    ?> </div>
                     <div class="Limpiador"></div>
                  </div>
                	<div class="CampoCompleto">
                  	<div class="Etiqueta">Contacto: </div>
                     <div class="CampoMuyLargo"><?php echo $Clasificado['direccion'];
                    ?> </div>
                     <div class="Limpiador"></div>
                  </div>
                	<div class="CampoCompleto">
                  	<div class="Etiqueta">Pais: </div>
                     <div class="CampoCorto"><?php echo $Clasificado['pais'];
                    ?> </div>
                  	<div class="EtiquetaCorta">Estado: </div>
                     <div class="CampoMedio"><?php echo $Clasificado['estado'];
                    ?> </div>
                     <div class="Limpiador"></div>
                  </div>
                	<div class="CampoCompleto">
                  	<div class="Etiqueta">Vence en : </div>
                     <div class="CampoCorto"><?php echo $Clasificado['fin']." Días";
                    ?> </div>
                     <div class="Limpiador"></div>
                  </div>
          	<div class="FormSubTitulo">
            	Fotos del Clasificado
            </div>
            <div class="FotosClasificado">
            <!--<div class="container">-->
      			<div class="image-row">
		       		<div class="image-set">
                    <?php
                        $y=0;
                    for ($i=0;$i<count($imagenesvp);$i++) {
                        if (!empty($imagenesvp[$i])) {
                            echo "<div class='vpImgClasificadoCliente' id='IMG_".$name[$i]."'>\n".
                                        "<div class='vpImgClasificado'>".
                                            "<a href='".$imagenes[$i]."' data-lightbox='example-set' data-title='".$Clasificado['titulo']."'>".
                                                "<img class='example-image' src='".$imagenesvp[$i]."' alt=''/>".
                                            "</a>".
                                        "</div>\n".
                                   "</div>\n";
                            $y++;
                        }
                    }
                    ?>
      				</div>
               </div>
            <!--</div>-->
            </div>
                </div>
              </div>


      <?php

                } else {
                    echo "<br /><br /><center>Disculple Clasificado No Encontrado</center>";
                }
            }
        ?>
	 <!-- InstanceEndEditable -->
    </div>
    <?php include('derechos.html'); ?>
</div>

</body>
<!-- InstanceEnd --></html>
