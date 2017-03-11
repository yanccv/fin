<?php 
	session_start();    
	include("../includes/classdb.php");
	$bd = new dbMysql();
	$bd->dbConectar();	
    if ($_GET['tipoform']=="E"){
        $CCategoria=$bd->dbConsultar("select * from categorias where id=?",array($_GET['id']));
        if ($bd->Error){
            echo "<center>".$bd->MsgError."</center>";
            exit();
        }else{
            if ($CCategoria->num_rows>0){
                $Categoria=$CCategoria->fetch_array();                
            }
            else
            {
                echo "<center>Disculpe Categoria No Encontrada</center>";    
            }
        }
    }else{
        $_GET['tipoform']="N";
    }    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Administrativa.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Registro de Categorias</title>
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />  
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
            	Formulario de Registro de Categorias                 
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Categoria: </div>
                <div class="CampoCorto">
                    <input name="idform" type="hidden" id="idform" value="categorias" />
                    <input name="id" type="hidden" id="id" value="<?php echo (int) $_GET['id']; ?>" />                                    
                    <input name="tipoform" type="hidden" id="tipoform" value="<?php echo $_GET['tipoform']; ?>" />                
                    <input name="categoria" type="text" size="30" maxlength="50" id="categoria" value="<?php echo $Categoria['categoria']; ?>" />
                </div>
                <div class="Limpiador"></div>
            </div>
            <div class="FormFin">
            	<input name="Boton" id="Boton" value="Guardar Informacion" type="submit" />
            </div>           
      	  </form>
        </div>
        <div id="info" class=""></div>
		<!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>
