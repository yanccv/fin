<?php
    session_start();
    include("../includes/classdb.php");
   include("../includes/funcion.php");
    $bd = new dbMysql();
    $bd->dbConectar();
    if ($_GET['tipoform']=="E") {
        $id=(int) $_GET['id'];
        $CClasificado=$bd->dbConsultar("SELECT	c.id,c.titulo,c.descripcion,c.direccion,c.imagenes,c.categoria,p.id idpub,p.dias ndias,p.costo,c.idpais pais,c.idestado estado,m.referencia,b.banco,cta.cuenta, mo.moneda
            FROM	clasificados as c
            	inner join movimientos as m on m.id=c.movimiento
               inner join paises as pa on pa.id=c.idpais
               inner join estados as e on (e.pais=c.idpais and e.id=c.idestado)
               inner join publicaciones as p on p.id=c.dias
               inner join cuentas as cta on cta.id=m.cuenta
               inner join bancos as b on b.id=cta.banco
               inner join categorias as ca on ca.id=c.categoria
               inner join monedas as mo on mo.id=pa.monedaoficial
            where c.id=?", array((int) $_GET['id']));
        //echo $bd->getSql();
        if ($bd->Error) {
            echo "<center>".$bd->MsgError."</center>";
            exit();
        } else {
            if ($CClasificado->num_rows>0) {
                $Clasificado=$CClasificado->fetch_array();
                $imagenes=explode("|", $Clasificado['imagenes']);
                $imagenesvp=$imagenes;
                for ($i=0;$i<count($imagenesvp);$i++) {
                    if (!empty($imagenesvp[$i])) {
                        $name[$i]=substr($imagenesvp[$i], strrpos($imagenesvp[$i], "/")+1, -4);
                        $file[$i]=substr($imagenesvp[$i], strrpos($imagenesvp[$i], "/")+1);
                        $imagenesvp[$i]=substr($imagenesvp[$i], 0, strrpos($imagenesvp[$i], "/")+1)."vp/".$file[$i];
                    }
                }
            } else {
                echo "<center>Disculpe Cliente No Encontrado</center>";
            }
        }
    } else {
        $_GET['tipoform']="N";
    }
    //print_r($_GET);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Administrativa.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Activación de Clasificados</title>
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<!--<link rel="stylesheet" href="../css/screen.css">-->
<link rel="stylesheet" href="../css/lightbox.css">

<script type="text/javascript" src="../scripts/jquery/jquery.js"></script>
<script type="text/javascript" src="../scripts/jquery/lightbox.js"></script>
<script type="text/javascript" src="../scripts/jquery/forms.js"></script>
<!--<script type="text/javascript" src="../scripts/ckeditor/editor.js"></script>-->

<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
    <!-- Estilos Para las Herramientas UI -->
    <link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />

     <!--Hora y Fecha -->
    <script type="text/javascript" src="../scripts/calendario/datepicker.js" ></script>
        <!-- Version JQuery UI 1.10 -->
    <script type="text/javascript" src="../scripts/jquery/jqueryui.js" ></script>

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
               	yearRange: "c:c+1",
                //minDate:''+dia+'/'+mes+'/'+anio+'',
                regional:"es"
            });
        });
    </script>
<!-- InstanceEndEditable -->
<link href="../css/estructura.css" rel="stylesheet" type="text/css" />
<!-- Vinculo al Menu-->
<script type="text/javascript" src="../scripts/menu/stmenu.js"></script>
</head>

<body>
	<div id="Contenedor">
  	  <div id="Banner">
			<img src="../imagenes/banner.png" border='0' />
      </div>
      <div id="DatosUser">
		<table border="0" cellpadding="0" cellspacing="0"><tr><td width="300" align="left">Usuario: <?php echo $_SESSION['usuario']['login']; ?></td><td width="300" align="center">Nombre de Usuario: <?php echo $_SESSION['usuario']['nombre']; ?></td><td width="250" align="right">Perfil: Administrativo</td></tr></table>
      </div>
      <div id="FilaMenu">
			 <script type="text/javascript" src="../scripts/menu/administrativo.js"></script>
      </div>
        <div id="Cuerpo">
        <!-- InstanceBeginEditable name="CentroAdministrativo" -->
		<div class="FormDatos">
          	<div class="FormTitulo">
            	Formulario de Revición de Pago de Clasificados Para Su Respectiva Activación
            </div>
            <div class="SeparadorArticuloInterno"></div>
       	  <form method="post" action="procesar.php" name="ActClasificado" id="ActClasificado">
            <input type="hidden" id="idform" name="idform" value="GClasificado" />
            <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
            <div class="CampoCompleto">
            	<div class="EtiquetaCorta">Categoria:</div>
               <div class="CampoLargo">
               <?php
                  echo $bd->dbComboSimple("select id, categoria from categorias", array(), "categoria", 0, array(1), $Clasificado['categoria']);
               ?>
               </div>
            	<div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="EtiquetaCorta">Titulo:</div>
               <div class="CampoMuyLargo">
                  <input  type="text" id="titulo" name="titulo" value="<?php echo $Clasificado['titulo']; ?>" size="60" maxlength="60" />
               </div>
            	<div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
               <div class="EtiquetaCorta">Descripción:</div>
               <div class="CampoMuyLargo">
                  <input  type="text" id="descripcion" name="descripcion" value="<?php echo $Clasificado['descripcion']; ?>" maxlength="1200" />
               </div>
               <div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="EtiquetaCorta">Contacto:</div>
                <div class="CampoMuyLargo">
                  <input  type="text" id="contacto" name="contacto" value="<?php echo $Clasificado['direccion']; ?>" size="120" maxlength="150" />
                </div>
                <div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	 <div class="EtiquetaCorta">Pais:</div>
                <div class="CampoMedio">
                    <?php
                        echo $bd->dbComboSimple("select id,pais from paises", array(), "BPais", 0, array(1), $Clasificado['pais']);
                    ?>
                </div>
            	 <div class="EtiquetaCorta">Estado:</div>
                <div class="CampoMedio">
                    <?php
                        echo $bd->dbComboSimple("select id,estado from estados where pais=?", array($Clasificado['pais']), "Estado", 0, array(1), $Clasificado['estado']);
                    ?>
                </div>
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
                                echo "<div class='vpImgClasificadoCompleta' id='IMG_".$name[$i]."'>\n".
                                        "<div class='vpImgClasificado'>".
                                            "<a href='".$imagenes[$i]."' data-lightbox='example-set' data-title='".$Clasificado['titulo']."'>".
                                                "<img class='example-image' src='".$imagenesvp[$i]."' alt=''/>".
                                            "</a>".
                                        "</div>\n".
                                        "<div class='vpEliminarClasificado' rel='".$id."' img='".$file[$i]."'><a href='#'>Eliminar</a></div>\n".
                                   "</div>\n";
                                $y++;
                            }
                        }
                     ?>
      				</div>
               </div>
            <!--</div>-->
            </div>

            <div class="CampoCompleto" id="LoadFotos" >
                <div class="DetaLoadImg">
                <center>
                <div id="ICentrada">
                <?php
                   $max=12-$y;
                   for ($i=0;$i<$max;$i++) {
                       ?>
                      <div class="CampoCorto">
                         <div class="botonInputFileModificado">
                            <input type="file" class="inputImagenOculto" id="imagen[]" name="imagen[]"/>
                            <div class="boton">Buscar Imagen</div>
                         </div>
                      </div>
                  <?php

                   }
                  ?>
                  </div>
                </center>
                </div>
                <div class="Limpiador"></div>
            </div>
            <div class="FormFin">
            	<input name="Boton" id="Boton" value="Guardar Cambios" type="submit" />
            </div>
      	  </form>
        </div>
        <div id="info"></div>
		<!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>
