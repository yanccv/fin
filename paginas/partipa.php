<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Publica.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Documento sin título</title>
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
    <!-- InstanceBeginEditable name="AreaCentral" -->AreaCentral<!-- InstanceEndEditable -->
    </div>
    <?php include('derechos.html'); ?>
</div>

</body>
<!-- InstanceEnd --></html>
