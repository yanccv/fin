<?php
//Pagina Central
    include("../includes/classdb.php");
    include("../includes/funcion.php");
    $bd= new dbMysql();
    $bd->dbConectar();
    if (!isset($_GET['op'])) {
        $_GET['op']="Inicio";
    }
    $IdArea=$_GET['op'];

    $ConArea=$bd->dbConsultar("select * from areas where area=?", array($_GET['op']));
    if (!$bd->Error) {
        $Area=$ConArea->fetch_array();
    }

    //Busco los Banner Princiapal de la Imagen
    $ConABanner=$bd->dbConsultar("select * from banners where idarea=? and isnull(posicion) limit 1", array($Area['id']));
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
<title>Fondo Interactivo de Negocios</title>
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
        if (!empty($_GET['op']) && ($_GET['op']=='Clasificados')) {
            include("bclasificados.php");
            include("uclasificados.php");
        }

        $ConArticulos=$bd->dbConsultar("select art.* from articulos as art inner join  areas a on a.id=art.area where a.area=? order by art.orden asc", array($IdArea));
        if ($bd->Error) {
            echo "<center>".$bd->MsgError."</center>";
        } else {
            if ($ConArticulos->num_rows>0) {
                while ($Articulo=$ConArticulos->fetch_array()) {
                    echo "<div class='Articulo'><a name='".$Articulo['tmenu']."' id='".$Articulo['tmenu']."'></a>";
                    echo "<div class='TituloArticulo'>".$Articulo['titulo']."</div><div class='SeparadorArticuloInterno'></div>";
                    echo "<div class='ContenidoArticulo'>".$Articulo['contenido']."<div class='Limpiador'></div></div>";
                    echo "</div><div class='SeparadorArticuloExterno'></div>";

                    //Busqueda de los banners que se deben publicar luego de este articulo
                    $ConBanners=$bd->dbConsultar("select db.idbanner,db.titulo,db.banner,db.enlace,b.posicion,b.ancho,b.alto,b.rotativo from detabanner as db inner join publicaciones as p on p.id=db.idplan inner join banners as b on b.id=db.idbanner where estado='A' and curdate() between desde and date_add(desde, interval p.dias day) and b.idarea=? and b.posicion=? order by b.idarea,b.posicion", array($Articulo['area'], $Articulo['id']));

                    if (!$bd->Error) {
                        if ($ConBanners->num_rows>0) {
                            $ini=0;
                            $slide=2;
                            while ($Banner=$ConBanners->fetch_array()) {
                                if (is_file($Banner['banner'])) {
                                    echo "<div class='Publicidad'><a href='".$Banner['enlace']."' target='_blank'><img border='0' src='".$Banner['banner']."' title='".$Banner['titulo']."' width='".$Banner['ancho']."' height='".$Banner['alto']."'></a></div>\n";
                                }
                            }
                        } else {
                            $ConBanners=$bd->dbConsultar("select * from banners where idarea=? and posicion=?", array($Articulo['area'], $Articulo['id']));
                            if (!$bd->Error) {
                                if ($ConBanners->num_rows>0) {
                                    $Banner=$ConBanners->fetch_array();
                                    echo "<div class='Publicidad'><img src='".$Banner['imagen']."' title='Espacio Disponible' width='".$Banner['ancho']."' height='".$Banner['alto']."'></div>\n";
                                }
                            } else {
                                echo $bd->MsgError;
                            }
                        }
                    }
                }
            } else {
                echo "<center>No Hay Articulos</center>";
            }
        }
    ?>
    <!-- InstanceEndEditable -->
    </div>
    <?php include('derechos.html'); ?>
</div>

</body>
<!-- InstanceEnd --></html>
