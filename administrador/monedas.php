<?php 
	session_start();    
	include("../includes/classdb.php");
	$bd = new dbMysql();
	$bd->dbConectar();	
   $enc=false; 
   $tipoform="N";
   if (!empty($_GET['id'])){
      $id=(int) $_GET['id'];
      $ConMoneda=$bd->dbConsultar("select * from monedas where id =?",array($id));
      if (!$bd->Error){
         if ($ConMoneda->num_rows>0){
            $Moneda=$ConMoneda->fetch_array();
            $enc=true;
            $tipoform="E";
         }
      }else{
         echo $bd->MsgError;
         exit();
      }
   }   
   //Busqueda de la Moneda Base 
   $ConMBase=$bd->dbConsultar("select id,moneda from monedas where id=(select monedabase from configuracion limit 1)");
   if (!$bd->Error){
      if ($ConMBase->num_rows>0){
         $MBase=$ConMBase->fetch_array();
      }else{
         echo "No se ha definido la moneda base";
         exit();
      }
   }   

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Administrativa.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Registro de Monedas</title>
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery/jquery.js"></script>
<script type="text/javascript" src="../scripts/jquery/forms.js"></script>
<link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery/jqueryui.js" ></script>

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
          	<div class="FormTitulo">
            	Formulario Registro de Los Diferentes Tipos de Monedas                  
            </div>
            <div class="SeparadorArticuloInterno"></div>         
       	  <form method="post" action="procesar.php" name="fdatos" id="fdatos">
            <input type="hidden" id="idform" name="idform" value="Monedas" />        
            <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
            <input type="hidden" id="base" name="base" value="<?php echo $MBase['id']; ?>" />
            <input type="hidden" id="tipoform" name="tipoform" value="<?php echo $tipoform; ?>" />
            <div class="CampoCompleto">
            	<div class="Etiqueta">Moneda:</div>
                <div class="CampoCorto"><input type="text" id="moneda" name="moneda" size="20" maxlength="20" value="<?php echo $Moneda['moneda']; ?>" /></div>
            	<div class="EtiquetaMuyLarga">Tasa de Cambio Frente a <?php echo $MBase['moneda']; ?>:</div>
                <div class="CampoCorto"><input type="text" id="cambio" name="cambio" size="10" maxlength="7" <?php if ($enc) echo "readonly='true'"; ?> value="<?php echo $Moneda['cambio']; ?>" /></div>
                
            	<div class="Limpiador"></div>
            </div>            
                        
            <div class="FormFin">
            	<input name="Boton" id="Boton" value="Guardar" type="submit" />
            </div>           
      	  </form>
           <div id="info"></div>
        </div> 
        <!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>
