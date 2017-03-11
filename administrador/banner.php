<?php 
	session_start();    
	include("../includes/classdb.php");
	$bd = new dbMysql();
	$bd->dbConectar();	
    $enc=false;
    if ($_GET['tipoform']=="E"){
        $CBanner=$bd->dbConsultar("select * from banners where id=?",array( (int) $_GET['id']));
        if ($bd->Error){
            echo "<center>".$bd->MsgError."</center>";
            exit();
        }else{
            if ($CBanner->num_rows>0){
                $Banner=$CBanner->fetch_array(); 
                $enc=true;  
                
                $imagenes=$Banner['imagen'];
                if (!empty($imagenes)){
                    $imagenesvp=$imagenes;
                    $name=substr($imagenesvp,strrpos($imagenesvp,"/")+1,-4);
                    $file=substr($imagenesvp,strrpos($imagenesvp,"/")+1);                        
                    $imagenesvp=substr($imagenesvp,0,strrpos($imagenesvp,"/")+1)."vp/".$file;                                                                                                                                  
                }                             
            }
            else
            {
                echo "<center>Disculpe Articulo No Encontrado</center>";    
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
<title>Documento sin título</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />
<link href="../css/formularios.css" rel="stylesheet" type="text/css" /> 
<link rel="stylesheet" href="../css/lightbox.css"> 
<script type="text/javascript" src="../scripts/jquery/jquery.js"></script>
<script type="text/javascript" src="../scripts/jquery/lightbox.js"></script>
<script type="text/javascript" src="../scripts/jquery/forms.js"></script>
<script type="text/javascript" src="../scripts/jquery/jqueryui.js" ></script>
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
       	  <form method="post" action="procesar.php" name="FBanner" id="FBanner">
                <input name="idform" type="hidden" id="idform" value="DEBanners" />
                <input name="id" type="hidden" id="id" value="<?php echo (int) $_GET['id']; ?>" />
                <input name="tipoform" type="hidden" id="tipoform" value="<?php echo $_GET['tipoform']; ?>" />
          	<div class="FormTitulo">
            	Formulario de Registro de Espacios Publicitarios                 
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Area: 
                </div>
                <div class="CampoCorto">
                    <?php
                        echo $bd->dbComboSimple("select id,area from areas",array(),"BArea",0,array(1),$Banner['idarea']);
                    ?>                                                                                                                
                </div>
                <div class="Limpiador"></div>
             </div>
             <div class="CampoCompleto">
            	<div class="Etiqueta">Posici&oacute;n: </div>
                <div class="CampoCorto">
                    <?php
                    if ($enc){                                            
                        $ConArticulo=$bd->dbConsultar("select id,concat('Despues del Articulo > ',titulo) as titulo from articulos where estado='A' and area=?",array($Banner['idarea']));
                        if (!$bd->Error){
                            if ($ConArticulo->num_rows>0){  
                                echo "<select id='BAArticulos' name='BAArticulos'>";
                                if ($Banner['posicion']==0) $select="selected='selected'";
                                echo "<option value='0' $select >Banner Principal</option>";                    
                                while ($Articulo=$ConArticulo->fetch_array()){
                                    $select="";
                                    if ($Banner['posicion']==$Articulo['id']) $select="selected='selected'"; 
                                    echo "<option value='".$Articulo['id']."' $select>".$Articulo['titulo']."</option>";
                                } 
                                echo "</select>";    
                            }else{
                                echo "Area Vacia";
                            }
                         }
                     }else{               
                    ?>
                	<select id="BAArticulos" name="BAArticulos">
                        <option value="0">Seleccion el Area</option>
                    </select>
                    <?php } ?>
                </div>                
                <div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Ancho: </div>
                <div class="CampoCorto">
                    <input type="text" id="ancho" name="ancho" size="15" value="<?php echo $Banner['ancho']; ?>" maxlength="3" />
                </div>
            	<div class="Etiqueta">Alto: </div>
                <div class="CampoCorto">
                    <input type="text" id="alto" name="alto" size="15"value="<?php echo $Banner['alto']; ?>"  maxlength="3" />
                </div>
                <div class="Limpiador"></div>
             </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Banner Rotativo: </div>
                <div class="CampoCorto">
                    <input type="checkbox" <?php if ($Banner['rotativo']=="S") echo "checked='checked'"; ?> id="rotativo" name="rotativo" value="S" />
                </div>
            	<div id="CantidadBanners" class="Etiqueta">Cantidad de Banners: </div>
                <div class="CampoCorto">
                    <input type="text" id="cantidad" name="cantidad" value="<?php echo $Banner['cantidad']; ?>" size="5" maxlength="1" />
                </div>
                <div class="Limpiador"></div>
             </div> 

            <div class="FotosClasificado">	
      			<div class="image-row">
		       		<div class="image-set">
                    <?php          
                        $y=0;  
                           if (!empty($imagenesvp)){
                              echo "<div class='vpImgClasificadoCompleta' id='IMG_".$name."'>\n".
                                        "<div class='vpImgClasificado'>".
                                            "<a href='".$imagenes."' data-lightbox='example-set' data-title='".$Banner['titulo']."'>".
                                                "<img class='example-image' src='".$imagenesvp."' alt=''/>".
                                            "</a>".
                                        "</div>\n".                                        
                                   "</div>\n";                                                          
                              $y++;
                           }                                
                                                                                  
                     ?>                        
      				</div>
               </div>
            </div>

                    <div class="SubTituloArticulo">
                    Imagen Por Defecto del Banner
                    </div>                    
                    <div class="DetaLoadImg">                      
                        <center>                      
                        <div class="botonInputFileModificado">
                            <input type="file" class="inputImagenOculto" id="imagen" name="imagen"/>
                            <div class="boton">Buscar Imagen</div>    
                        </div>  
                        </center>                                               
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
