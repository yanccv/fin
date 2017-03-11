<?php 
	session_start();    
	include("../includes/classdb.php");
	$bd = new dbMysql();
	$bd->dbConectar();	
    $id= (int) $_GET['id'];
    $CArea=$bd->dbConsultar("select * from areas where id=?",array($id));
    if ($bd->Error){
        echo "<center>".$bd->MsgError."</center>";
        exit();
    }else{
         if ($CArea->num_rows>0){
            $Area=$CArea->fetch_array();
            $enc=true;
            
            $Banners=explode(":",$Area['banners']);
/*            
            echo "<pre>";
            print_r($Banners);
            echo "</pre>";
*/            
//            $ruta="../files/$id/Banner/";   
/*            
            for ($i=1;$i<count($Banners);$i++){
                echo $Banners[$i]."<br />";    
            }
*/                       
/*            
            if (is_dir($ruta)){
                //echo "Existe el Directorio";
                if ($dh = opendir($ruta))
                {
                    while (($file = readdir($dh)) !== false) 
                    {
                        if ($file=="." || $file==".." || (filetype($ruta . $file)<>'file'))  continue;
                        $Banners[]=$ruta.$file;
                        $BTamano[]=getimagesize($ruta . $file);
                    } 
                    closedir($dh);    
                }
            }
*/                                                                          
         }
         else
         {
            echo "<center>Disculpe Articulo No Encontrado</center>";    
         }
     }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Administrativa.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Documento sin título</title>
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
            	Formulario de Registro de Areas                 
            </div>
            <div class="CampoCompleto">
            	<div class="Etiqueta">Area: 
                </div>
                <div class="CampoCorto">
                    <?php echo $Area['area']; ?>
                    <input name="idform" type="hidden" id="idform" value="bannareas" />
                    <input name="id" type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />                                                    
                </div>                
                <div class="Limpiador"></div>
            </div>
            <?php 
            if ($enc){
                for ($i=1;$i<count($Banners);$i++){
                    if (is_file($Banners[$i])){
                        $BTamano=getimagesize($Banners[$i]);    
                    }else{
                        continue;
                    }             
                    $file=substr($Banners[$i],strripos($Banners[$i],"/")+1);       
                    echo '<div class="CampoCompleto">'.
                         '<div class="CampoImagen">'.
                         '<input name="oldimg[]" type="hidden" id="oldimg[]" value="'.$Banners[$i].'" />'.
                         '<img src="'.$Banners[$i].'" width="'.((int)($BTamano[0]*0.24)).'" height="'.((int)($BTamano[1]*0.24)).'" title="'.$file.'" border="0"/>'.
                         '<span class="EliminarImagen"><a href="#" rel="'.$Banners[$i].'" id="DelImg">Eliminar</a></span>'.
                         '</div>'.                
                         '<div class="Limpiador"></div>'.
                         '</div>';                                        
                }
            }
            
            ?>
            <a href='#' >Hola Mundo</a>
            <div class="CampoCompleto">
            	<div class="EtiquetaLarga">Imagen de Banner: </div>
                <div class="CampoLargo">
                    <input id="img[]" name="img[]" type="file" />
                </div>                                
                <div class="Limpiador"></div>
            </div>            
            <div class="FormFin">
                <input name="BAddImg" id="BAddImg" value="Agregar Otra Imagen" type="button" />
            	<input name="Boton" id="Boton" value="Guardar Informacion" type="submit" />
            </div>           
      	  </form>
        </div>
        <div id="info" class=""></div>
		<!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>
