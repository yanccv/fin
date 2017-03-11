<?php
    /*
    echo "<pre>";
    print_r($_SERVER);
    echo "</pre>";

    include("../includes/funcion.php");
    include("../includes/classdb.php");
    $bd= new dbMysql();
    $bd->dbConectar();
    echo $bd->Estatus;
    echo CambioMonetario($bd,2,'04/05/2014');
    echo $bd->getSql();
    */
?>
<html>
<head>
<link href="../css/estructura.css" rel="stylesheet" type="text/css" />
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />
<!--
<script type="text/javascript" src="../scripts/jquery/jquery.js" ></script>
<script type="text/javascript" src="../scripts/jquery/jqueryui.js" ></script>
<script type="text/javascript" src="../scripts/calendario/datepicker.js" ></script>
<script type="text/javascript" src="../scripts/jquery/fcliente.js" ></script>

<script type="text/javascript" src="../scripts/menu/stmenu.js" ></script>

<script type="text/javascript" src="../slider/scripts/jquery.anythingslider.js" ></script>
<script type="text/javascript" src="../slider/scripts/jquery.easing.1.2.js" ></script>
<script src="../scripts/swfobject_modified.js" type="text/javascript"></script>


<link rel="stylesheet" href="../slider/css/theme-metallic.css">


<script>
$().ready(function() {
        //$.datepicker.setDefaults($.datepicker.regional["es"]);
        $('input[tipo=fechahora]').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            regional:"es"
        });
});
</script>

<script>
		$(function(){
			$('#slider1').anythingSlider({
				theme           : 'metallic',
				mode			: 'fade',
				easing          : 'linear',
				buildStartStop  : false,
				autoPlay		: true,
				delay			: 3000
			});
		});
</script>

-->

<link href="../css/estructura.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/menu/stmenu.js" ></script>
<!-- Version JQuery 1.10 -->
<script type="text/javascript" src="../scripts/jquery/jquery.js" ></script>

<!--Files Para Slider-->
<script type="text/javascript" src="../slider/scripts/jquery.anythingslider.js" ></script>
<script type="text/javascript" src="../slider/scripts/jquery.easing.1.2.js" ></script>
<script src="../scripts/swfobject_modified.js" type="text/javascript"></script>
<link rel="stylesheet" href="../slider/css/theme-metallic.css">
	<script>
		$(function(){

			$('#slider1').anythingSlider({
				theme           : 'metallic',
				mode			: 'fade',
				easing          : 'linear',
				buildStartStop  : false,
				autoPlay		: true,
				delay			: 3000
			});
		});
	</script>

<!-- Fin Files Slider	-->


<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery/jqueryui.js" ></script>
<script type="text/javascript" src="../scripts/calendario/datepicker.js" ></script>
<script type="text/javascript" src="../scripts/calendario/timepicker.js" ></script>
<script type="text/javascript" src="../scripts/jquery/fcliente.js" ></script>
<script>
$().ready(function() {
        //$.datepicker.setDefaults($.datepicker.regional["es"]);
        $('input[tipo=fechahora]').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            regional:"es"
        });
});
</script>

</head>
<body>
    <input type="text" tipo="fechahora" id="Fecha" name="Fecha" />

    <input type="text" tipo="fechahora" id="fnac" name="fnac" maxlength="10" size="15" />
</body>
</html>
