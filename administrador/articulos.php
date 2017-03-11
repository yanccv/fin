<?php
	session_start();
	include("../includes/classdb.php");
	$bd = new dbMysql();
	$bd->dbConectar();
	$area= (int) $_GET['area'];
	$_SESSION['Folder']=$area;
	$CArea= $bd->dbConsultar("select area from areas where id=?",array($area));
	$Area=$CArea->fetch_array();
    if ($_GET['tipoform']=="E"){
        $CArticulo=$bd->dbConsultar("select * from articulos where area=? and id=?",array($_GET['area'],$_GET['id']));
        if ($bd->Error){
            echo "<center>".$bd->MsgError."</center>";
            exit();
        }else{
            if ($CArticulo->num_rows>0){
                $Articulo=$CArticulo->fetch_array();
            }
            else
            {
                echo "<center>Disculpe Articulo No Encontrado</center>";
            }
        }
        //if ($CArticulo)
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Administrativa.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Creacion y editado de articulos</title>
<link href="../scripts/ckeditor/editor.css" rel="stylesheet" type="text/css" />
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../scripts/ckfinder/ckfinder.js"></script>
<script type="text/javascript" src="../scripts/jquery/jquery.js"></script>
<script type="text/javascript" src="../scripts/jquery/forms.js"></script>
<!--<script type="text/javascript" src="../scripts/ckeditor/editor.js"></script>-->

<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
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
       	  <form method="post" action="procesar.php" name="fdatos" id="fdatos">
          	<div class="FormTitulo">
            	Formulario de Publicacion de Articulos
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Area: <?php echo $Area[0]; ?>
                    <input name="idform" type="hidden" id="idform" value="articulos" />
                    <input name="id" type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
                    <input name="area" type="hidden" id="area" value="<?php echo $area; ?>" />
                    <input name="tipoform" type="hidden" id="tipoform" value="<?php echo $_GET['tipoform']; ?>" />
                </div>
            	<div class="Etiqueta">Titulo SubMenu: </div>
                <div class="CampoCorto">
                    <input name="tmenu" type="text" id="tmenu" value="<?php echo $Articulo['tmenu']; ?>" size="25" maxlength="25" />
                </div>
            	<div class="EtiquetaCorta">Orden: </div>
                <div class="CampoCorto">
                    <input name="aorden" type="hidden" id="aorden" value="<?php echo $Articulo['orden']; ?>" />
                    <select id="orden" name="orden">
                    <?php
                        $COrden=$bd->dbConsultar("select orden,tmenu from articulos where area=? order by orden asc",array($area));
                        print_r($COrden);
                        if ($bd->Error){
                            //echo $bd->MsgError;
                            echo "<option value=''>".$bd->MsgError."</option>";
                        }else
                        {
                            echo "<option value='0'>Colocar Primero</option>";
                            if ($COrden->num_rows>0){

                                while ($OArticulo=$COrden->fetch_array()){
                                    $sel="";
                                    if ($Articulo['orden']==$OArticulo['orden']) continue;
                                    if (($Articulo['orden']-1)==$OArticulo['orden'])
                                        $sel="selected='selected'";
                                    echo "<option value='".$OArticulo['orden']."' ".$sel." title='".$OArticulo['orden']."'>Despues de ".$OArticulo['tmenu']."</option>";
                                }

                            }
                        }
                    ?>
                    </select>
                </div>
                <div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Titulo Articulo: </div>
                <div class="CampoCorto">
                    <input name="titulo" type="text" id="titulo" value="<?php echo $Articulo['titulo']; ?>" size="100" maxlength="150" />
                </div>
                <div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="EtiquetaLarga">Información de Articulo: </div><br />

                <div><textarea name="contenido" id="contenido"><?php echo $Articulo['contenido']; ?></textarea></div>
                <div class="Limpiador"></div>
            </div>
            <div class="FormFin">
            	<input name="Boton" id="Boton" value="Guardar Informacion" type="submit" />
            </div>
      	  </form>
        </div>
        <div id="info" class=""></div>
              <script type="text/javascript">
				CKEDITOR.replace( 'contenido', {
					extraPlugins : 'autogrow',
					toolbar :
					[
					['Save','Preview','Source','-','Cut','Copy','Paste','PasteFromWord','PasteText','SelectAll','RemoveFormat','-','Undo','Redo','-','Table','Image','Flash','-','Link','Unlink','Anchor','HorizontalRule','Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField',,'PageBreak','Print','Templates'],
					['Bold', 'Italic','Underline','-','Subscript','Superscript','-','NumberedList','BulletedList','-','Indent', 'Outdent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','SwitchBar','Maximize','SpecialChar','-','TextColor','BGColor','Styles','Format','Font','FontSize'],
					],
					filebrowserBrowseUrl : '../scripts/ckfinder/ckfinder.html?type=Files',
					filebrowserImageBrowseUrl : '../scripts/ckfinder/ckfinder.html?type=Images',
					filebrowserFlashBrowseUrl : '../scripts/ckfinder/ckfinder.html?type=Flash',
					filebrowserUploadUrl : '../scripts/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
					filebrowserImageUploadUrl : '../scripts/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
					filebrowserFlashUploadUrl : '../scripts/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
					filebrowserWindowWidth : '650',
 					filebrowserWindowHeight : '500'
				});
			getvalue = function()
			{
				var editor = CKEDITOR.instances.contenido;
				var value = editor.getData();
				return value;
			}
			</script>
		<!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>
