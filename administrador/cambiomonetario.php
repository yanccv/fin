<?php 
	session_start();    
	include("../includes/classdb.php");
	$bd = new dbMysql();
	$bd->dbConectar();	
   $enc=false; 
   $tipoform="N";
   if (!empty($_GET['id'])){
      $id=(int) $_GET['id'];
      $ConEstado=$bd->dbConsultar("select * from estados where id =?",array($id));
      if (!$bd->Error){
         if ($ConEstado->num_rows>0){
            $Estado=$ConEstado->fetch_array();
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
<title>Cambio Monetario</title>
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
            	Formulario de Actualización Monetaria                 
            </div>
            <div class="SeparadorArticuloInterno"></div>      
              
       	  <form method="post" action="procesar.php" name="fdatos" id="fdatos">
            <input type="hidden" id="idform" name="idform" value="CambioMonetario" />        
            <input type="hidden" id="tipoform" name="tipoform" value="<?php echo $tipoform; ?>" />
            <div class="CampoCompleto">
               <div class="Etiqueta">Hoy <?php echo date("d/m/Y"); ?></div>
            	<div class="Limpiador"></div>
            </div> 
            <?php
               $ConMonedas=$bd->dbConsultar("select id,moneda,cambio from monedas where id <>? ",array($MBase['id']));
               if (!$bd->Error){
                  if ($ConMonedas->num_rows>0){
                     while ($Moneda=$ConMonedas->fetch_array()){
            ?>
               <div class="CampoCompleto">               
               	<div class="Etiqueta">1 <?php echo $MBase['moneda']; ?> =</div>
                   <div class="CampoLargo">
                        <input type="text" id="cambio[]" name="cambio[]" size="10" maxlength="7" value="<?php echo $Moneda['cambio']; ?>" />
                        <input type="hidden" id="monedas[]" name="monedas[]" value="<?php echo $Moneda['id']; ?>" />
                        <?php echo $Moneda['moneda']; ?>                                             
                   </div>            	
               	<div class="Limpiador"></div>
               </div>                                   
            <?php                        
                     }
                  }
               }
            ?>
<?php
                                        
            $ConHM=$bd->dbConsultar("select * from hmonedas where desde=curdate() limit 1",array());
            if (!$bd->Error){
               if ($ConHM->num_rows>0){
                 echo "<div class='FormFin'>Disculpe el Dia de Hoy Ya se Realizo un Cambio Monetario, No Se Pueden Hacer Mas Cambios Durante el Mismo Dia</div>";
               }else{
?>
            <div class="FormFin">
            	<input name="Boton" id="Boton" value="Guardar" type="submit" />
            </div>           
<?                  
               }
            }else
               $errores[]=$bd->MsgError;
?>             
      	  </form>
           <div id="info"></div>
        </div> 
        <!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>
