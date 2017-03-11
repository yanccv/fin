<?php
	session_start();    
	include("../includes/classdb.php");
   include("../includes/funcion.php");
	$bd = new dbMysql();
	$bd->dbConectar();	    
    if ($_GET['tipoform']=="E"){
        $id=(int) $_GET['id'];
        
        $CBanner=$bd->dbConsultar("
select 
	d.id,b.ancho,b.alto,a.area,if (isnull(b.posicion),'Banner Principal',concat('Despues de ',ar.titulo)) posicion,d.titulo,d.enlace,d.banner,concat(p.dias,' Dias, ',' Por ',p.costo*m.cambio,' ',m.moneda) plan
from 
	detabanner as d 
	inner join publicaciones as p on p.id=d.idplan 
	inner join banners as b on b.id=d.idbanner 
	inner join areas as a on a.id=b.idarea 
	left join articulos as ar on b.posicion=ar.id 
	inner join clientes as c on c.cedula=d.idcliente
	inner join paises as pa on pa.id=c.pais
	inner join monedas as m on m.id=pa.monedaoficial
where d.id=?",array((int) $_GET['id']));

        if ($bd->Error){
            echo "<center>".$bd->MsgError."</center>";
            exit();
        }else{
            if ($CBanner->num_rows>0){
                $Banner=$CBanner->fetch_array();
                $imagenes=$Banner['banner'];
                if (!empty($imagenes)){
                    $imagenesvp=$imagenes;
                    $name=substr($imagenesvp,strrpos($imagenesvp,"/")+1,-4);
                    $file=substr($imagenesvp,strrpos($imagenesvp,"/")+1);                        
                    $imagenesvp=substr($imagenesvp,0,strrpos($imagenesvp,"/")+1)."vp/".$file;                                                                                          
                    
                }
            }
            else
            {
                echo "<center>Disculpe Cliente No Encontrado</center>";    
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
<title>Activación de Banners</title>
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
            	Formulario de Revición de Pago de Banners Para Su Respectiva Activación                 
            </div>
            <div class="SeparadorArticuloInterno"></div>         
       	  <form method="post" action="procesar.php" name="ActBanner" id="ActBanner">
            <input type="hidden" id="idform" name="idform" value="GBanner" />
            <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
            <input type="hidden" id="ancho" name="ancho" value="<?php echo $Banner['ancho']; ?>" />
            <input type="hidden" id="alto" name="alto" value="<?php echo $Banner['alto']; ?>" />
            
            <input type="hidden" id="idpais" name="idpais" value="<?php echo $Banner['pais']; ?>" />
            <div class="CampoCompleto">
            	<div class="EtiquetaCorta">Seccion:</div>
                <div class="CampoLargo"><?php echo $Banner['area']; ?></div>
            	<div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
            	<div class="EtiquetaCorta">Ubicación:</div>
                <div class="CampoLargo"><?php echo $Banner['posicion']; ?>             
                </div>
            	<div class="Limpiador"></div>
            </div> 
            <div class="CampoCompleto">
                <div class="EtiquetaCorta">Dimenciones: </div>
                <div class="CampoMedio" id="Dimenciones"><?php echo "Ancho: ".$Banner['ancho']."px y Alto: ".$Banner['alto']."px";  ?>                            
                </div>
                <div class="Limpiador"></div>                    
            </div>
            <div class="CampoCompleto">
                <div class="EtiquetaCorta">Plan: </div>
                <div class="CampoCorto">
                <?php
                  echo $Banner['plan'];
                ?>
<!--                
                    <select id="CPlanBanner" name="CPlanBanner">
                        <option value="0">Seleccione Posición</option>                            
                    </select>
-->                    
                </div>                    
                <div class="Limpiador"></div>                                            
            </div>
<?php             
/*
                SELECT 
	               p.id,concat('Publicar Durante ',p.dias,' Dias, Por ',round(p.costo),' ', m.moneda) plan,concat('Ancho: ',ancho,'px y Alto: ',alto,'px') dimenciones,ancho,alto 
                FROM 
	               publicaciones as p 
		              inner join paises as pa on pa.id=p.pais 
		              inner join monedas as m on m.id=pa.monedaoficial 
                      inner join banners as b on b.id=p.tipo
                where p.tipo=? and pa.id=?;                                   
*/
?>
                                    
            <div class="CampoCompleto">
            	<div class="EtiquetaCorta">Titulo:</div>
               <div class="CampoLargo">
                <input type="text" id="titulo" name="titulo" size="60" maxlength="60" value="<?php echo $Banner['titulo']; ?>" />                
               </div>
            	<div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
               <div class="EtiquetaCorta">Enlace:</div>
               <div class="CampoLargo">
                    <input type="text" id="enlace" name="enlace" size="60" maxlength="60" value="<?php echo $Banner['enlace']; ?>" />                     
               </div>                  
               <div class="Limpiador"></div>
            </div> 
          	<div class="FormSubTitulo">
            	Imagen del Banner                 
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
                                        "<div class='vpEliminarBanner' rel='".$id."' img='".$file."'><a href='#'>Eliminar</a></div>\n".
                                   "</div>\n";                                                          
                              $y++;
                           }                                
                                                                                  
                     ?>                        
      				</div>
               </div>
            </div> 
            <div class="CampoCompleto" id="LoadFotos" >
                <div class="DetaLoadImg">
                <center>
                <div id="ICentrada">
                <?php           
                   if (empty($Banner['banner'])){
                ?>

                         <div class="botonInputFileModificado">
                            <input type="file" class="inputImagenOculto" id="imagen" name="imagen"/>
                            <div class="boton">Buscar Imagen</div>    
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
            	<input name="Boton" id="Boton" value="Actualizar Información" type="submit" />
            </div>           
      	  </form>
           <div id="info"></div>
        </div>
        
		<!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>
