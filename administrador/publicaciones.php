<?php 
	session_start();
	include("../includes/classdb.php");
	$bd = new dbMysql();
	$bd->dbConectar();	
    $enc=false;
    if ($_GET['tipoform']=="E"){
        $CPub=$bd->dbConsultar("select * from publicaciones where id=?",array( (int) $_GET['id']));
        if ($bd->Error){
            echo "<center>".$bd->MsgError."</center>";
            exit();
        }else{
            if ($CPub->num_rows>0){
                $Pub=$CPub->fetch_array(); 
                $enc=true;               
            }
            else
            {
                echo "<center>Disculpe Registro No Encontrado</center>";    
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
<title>Registro de Periodos de Publicacion de Banners y de Clasificados</title>
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
                <input name="idform" type="hidden" id="idform" value="RPublicaciones" />
                <input name="id" type="hidden" id="id" value="<?php echo (int) $_GET['id']; ?>" />
                <input name="tipoform" type="hidden" id="tipoform" value="<?php echo $_GET['tipoform']; ?>" />
          	<div class="FormTitulo">
            	Formulario de Registro de Periodos de Tiempo Para Los Banners y Clasificados                 
            </div>
            <div class="CampoCompleto">                    
            	<div class="Etiqueta">Aplicar A: </div>
                <div class="CampoCorto" >
                    <?php 
                        if ($enc){
                            if (empty($Pub['tipo'])){
                                echo "Clasificados";    
                                echo "<input type='hidden' id='tipo' name='tipo' value='C' />";
                            }else{
                                echo "Banners";
                                echo "<input type='hidden' id='tipo' name='tipo' value='B' />";
                            }                            
                        }else{
                    ?>
                    <input type="radio" id="tipo" name="tipo" value="C" />Clasificados 
                    <input type="radio" id="tipo" name="tipo" value="B" />Banners                                                                                                                                  
                    <?
                        }                        
                    ?>
                </div>                
                <div class="Limpiador"></div>
             </div>                 
             <?php 
                if ((!empty($Pub['tipo']) && $enc) || !$enc){                                    
             ?>                                    
            <div class="CampoCompleto" id="ListadoBanners" <?php if (!$enc || empty($Pub['tipo'])){ ?> style="visibility: hidden; display:  none;" <?php } ?>>
            	<div class="Etiqueta">Banner:</div>
                <div class="CampoCorto">                
                    <?php
                        echo $bd->dbComboSimple("SELECT b.id,concat('Area ',a.area, if(ISNULL(b.posicion),' Banner Principal',concat(' Despues del Articulo ',ar.titulo)) ) Banner FROM banners as b inner join areas as a on a.id=b.idarea left join articulos as ar on (ar.area=b.idarea and ar.id=b.posicion)",array(),"IdBanner",0,array(1),$Pub['tipo']);
                    ?>                
                </div>
                <div class="Limpiador"></div>
             </div>             
             <?php 
                }
             ?>
             <div class="CampoCompleto">
            	<div class="Etiqueta">Dias: </div>
                <div class="CampoCorto">
                    <input type="text" id="dias" name="dias" maxlength="3" value="<?php echo $Pub['dias']; ?>" size="5" />
                </div> 
            	<div class="EtiquetaLarga">Costo en Moneda Base: </div>
                <div class="CampoCorto">
                    <input type="text" id="costo" name="costo" size="10" maxlength="10" value="<?php echo $Pub['costo']; ?>" />
                </div>                    
                             
                <div class="Limpiador"></div>
            </div>
            <?php 
                if (($enc && empty($Pub['tipo'])) || !$enc){
            ?>
            <div class="CampoCompleto" id="ClasificadoFoto" <?php if (!empty($Pub['tipo']) || !$enc){ ?> style="visibility: hidden; display: none;" <?php } ?>>
            	<div class="Etiqueta">Con Foto: </div>
                <div class="CampoCorto">
                    <input type="radio" id="foto" name="foto" <?php if ($Pub['foto']=='S') echo "checked='checked'"; ?> value="S" />Si
                    <input type="radio" id="foto" name="foto" <?php if ($Pub['foto']=='N') echo "checked='checked'"; ?> value="N" />No
                </div>
                <div class="Limpiador"></div>
             </div>
             <?php
                }
             ?>
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
