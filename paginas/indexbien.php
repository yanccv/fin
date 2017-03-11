<?php
//Pagina Central
    include("../includes/classdb.php");
    include("../includes/funcion.php");
    $bd= new dbMysql();
    $bd->dbConectar();
    if (!isset($_GET['op']))
        $_GET['op']="Inicio";
    $IdArea=$_GET['op'];

    $ConArea=$bd->dbConsultar("select * from areas where area=?",array($_GET['op']));
    if (!$bd->Error){
        $Area=$ConArea->fetch_array();
        $banners=explode(":",$Area['banners']);
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
    <link rel="stylesheet" href="../slider/css/theme-metallic.css">
<!--FIN CSS -->


<!-- Archivos JS -->

    <!-- Version JQuery 1.10 -->
    <script type="text/javascript" src="../scripts/jquery/jquery.js" ></script>
    <script type="text/javascript" src="../scripts/jquery/finternauta.js" ></script>

    <!-- MENU       -->
    <script type="text/javascript" src="../scripts/menu/stmenu.js" ></script>

    <!-- Banners Animados-->
    <script type="text/javascript" src="../slider/scripts/jquery.anythingslider.js" ></script>
    <script type="text/javascript" src="../slider/scripts/jqueryeasing.js" ></script>
<!--    <script src="../scripts/swfobject_modified.js" type="text/javascript"></script> -->

   	<script type="text/javascript">
		$(function(){
			$('#slider1').anythingSlider({
				theme           : 'metallic',
				mode			: 'fade',
				easing          : 'linear',
				buildStartStop  : false,
				autoPlay		: true,
				delay			: 3000
			});
			$('#slider2').anythingSlider({
				theme           : 'metallic',
				mode			: 'fade',
				easing          : 'linear',
				buildStartStop  : false,
				autoPlay		: true,
				delay			: 3000
			});
			$('#slider3').anythingSlider({
				theme           : 'metallic',
				mode			: 'fade',
				easing          : 'linear',
				buildStartStop  : false,
				autoPlay		: true,
				delay			: 3000
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
                for ($i=0;$i<count($banners);$i++){
                    if ((!empty($banners[$i])) && is_file($banners[$i]))
                        echo "<li><img src='".$banners[$i]."' alt='".$IdArea."'></li>\n";
                }
        ?>
            </ul>
        </div>
        <?php
            include("menu.php");
        ?>
    <div id="Cuerpo">
    <?php
        if (!empty($_GET['op']) && ($_GET['op']=='Clasificados')){
            include("uclasificados.php");
        }

        $ConArticulos=$db->dbConsultar("select art.* from articulos as art inner join  areas a on a.id=art.area where a.area=? order by art.orden asc",array($IdArea));
        if ($db->Error){
            echo "<center>".$db->MsgError."</center>";

        }else{
            if ($ConArticulos->num_rows>0){
                while ($Articulo=$ConArticulos->fetch_array()){
                    echo "<div class='Articulo'><a name='".$Articulo['tmenu']."' id='".$Articulo['tmenu']."'></a>";
                    echo "<div class='TituloArticulo'>".$Articulo['titulo']."</div><div class='SeparadorArticuloInterno'></div>";
                    echo "<div class='ContenidoArticulo'>".$Articulo['contenido']."</div>";
                    echo "</div><div class='SeparadorArticuloExterno'></div>";

                    //Busqueda de los banners que se deben publicar luego de este articulo
                    $ConBanners=$bd->dbConsultar("select db.idbanner,db.titulo,db.banner,db.enlace,b.posicion,b.ancho,b.alto,b.rotativo from detabanner as db inner join publicaciones as p on p.id=db.idplan inner join banners as b on b.id=db.idbanner where estado='A' and curdate() between desde and date_add(desde, interval p.dias day) and b.idarea=? and b.posicion=? order by b.idarea,b.posicion",array($Articulo['area'],$Articulo['id']));
                    echo $bd->getSql();
                    if (!$bd->Error){
                        if ($ConBanners->num_rows>0){
                           $ini=0;
                           $slide=2;
                           while ($Banner=$ConBanners->fetch_array()){
                              if ($ini==0 && $Banner['rotativo']=='S'){
                                 echo "<div id='slider'><ul class='slider2'>";
                                 $slide++;
                              }

                              if ($Banner['rotativo']=='S')
                                 echo "<li><img src='".$Banner['banner']."' title='".$Banner['titulo']."' width='".$Banner['ancho']."' height='".$Banner['alto']."'>$ini</li>\n";
                              else
                                 echo "<img src='".$Banner['banner']."' title='".$Banner['titulo']."' width='".$Banner['ancho']."' height='".$Banner['alto']."'>$ini\n";
                              $ini++;
                              if ($ini==$ConBanners->num_rows && $Banner['rotativo']=='S')
                                 echo "</ul></div>";
                           }
                        }
                    }


                }
            }else{
                echo "<center>No Hay Articulos</center>";
            }
        }
    ?>
    </div>
    <?php include('derechos.html'); ?>
</div>
</body>
</html>
