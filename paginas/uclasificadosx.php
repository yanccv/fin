<html>
<head>
<link rel="stylesheet" href="../css/clasificados.css" type="text/css" />
<!--<link rel="stylesheet" href="../css/estructura.css" type="text/css" />
<link rel="stylesheet" href="../css/clasificados.css" type="text/css" />
<link rel="stylesheet" href="../css/formularios.css" type="text/css" />

<script type="text/javascript" src="../scripts/jquery/jquery.js"></script>
-->
<script>
$(document).ready(
   function() {
      var numeroImagenes;
      var contenidoInicial=$('.bloque-imagenes').html();
      var margenTop; 
      function carrusel(){
         margenTop=$('.bloque-imagenes').css('margin-top');
         margenTop=margenTop.split('p');
         margenTop[0]=margenTop[0] - 1;
         
         if((($('.bloque-imagenes').children().size() - 4) * 73) < Math.abs(margenTop[0])){
            $('.bloque-imagenes').append(contenidoInicial);
         };
         $('.bloque-imagenes').css('margin-top',margenTop[0] +'px');

      }
      
      var parar=setInterval(function mover() {carrusel();},50);
      $('.bloque-imagenes').mouseover(function(){
	     clearInterval(parar);
      });
      $('.bloque-imagenes').mouseout(function(){
	     parar=setInterval(function mover() {carrusel();},50);
      });
});
</script>
</head>
<body>
<?php    
   if (!class_exists("dbMysql")){
      include("../includes/classdb.php");
      $bd = new dbMysql();
      $bd->dbConectar();      
   }
   $vp="vp/";

?>
<div class="Articulo">
   <div class="TituloArticulo">Busqueda de Clasificados</div>
   <div class="SeparadorArticuloInterno"></div>
   <div class="ContenidoArticulo">
    <form id="BClasificados" name="Bclasificados" method="get">
        <input id="op" name="op" value="<?php echo $_GET['op']; ?>" type="hidden" />
        <div class="CampoCompleto">
            <div class="EtiquetaCorta">Categoria</div>
            <div class="CampoMedio">
            <?php
                echo $bd->dbComboSimple("select id, categoria from categorias",array(),"categoria",0,array(1),$_GET['categoria']);
            ?>
            </div>
            <div class="CampoCorto">
                <input type="radio" id="foto" name="foto" <?php if ($_GET['foto']=='Si')    echo "checked='checked'"; ?> value="Si" />Con Foto
                <input type="radio" id="foto" name="foto" <?php if ($_GET['foto']=='No')    echo "checked='checked'"; ?> value="No" />Sin Foto
                
            </div>
            <div class="Limpiador"></div>            
        </div>
        <div class="CampoCompleto">
            <div class="EtiquetaCorta">Pais</div>
            <div class="CampoCorto">
            <?php
                echo $bd->dbComboSimple("select id, pais from paises",array(),"CPais",0,array(1),$_GET['CPais']);
            ?>
            </div>
            <div class="EtiquetaCorta">Estado</div>
            <div class="CampoMedio">
                <?php 
                    if (!empty($_GET['Estado'])){
                        echo $bd->dbComboSimple("select id, estado from estados where pais=?",array($_GET['CPais']),"Estado",0,array(1),$_GET['Estado']);
                    }else{
                ?>
                <select id="Estado" name="Estado">
                    <option value="0">Seleccion</option>
                </select>
                <?php 
                    }
                ?>
            </div>
            <div class="Limpiador"></div>            
        </div>
        
        <div class="CampoCompleto">
            <div class="EtiquetaCorta">Buscar</div>
            <div class="CampoMedio">
                <input type="text" id="busqueda" name="busqueda" value="<?php echo $_GET['busqueda']; ?>" size="50" maxlength="50" />
            </div>
            <div class="EtiquetaCorta">
                <input type="submit" id="BBClasificado"  name="BBClasificado" value="Buscar Clasificado"/>
            </div>
            <div class="Limpiador"></div>            
        </div>        
        <div class="Limpiador"></div>
    </form>
   </div>
</div>
<?php 
    if ($_GET['BBClasificado']){
        
        if (!empty($_GET['categoria'])) $Categoria=" and categoria=".((int) $_GET['categoria'])." ";
        if (!empty($_GET['CPais'])) $Pais=" and idpais=".((int) $_GET['CPais'])." ";
        if (!empty($_GET['Estado'])) $Estado=" and idestado=".((int) $_GET['Estado'])." ";        
        if (!empty($_GET['foto']))  if ($_GET['foto']=='Si') $Foto=" and length(imagenes)>20 ";        else    $Foto=" and length(imagenes)<20 ";
        if (!empty($_GET['busqueda'])) $Busqueda=" and titulo like '%".($_GET['busqueda'])."%' and descripcion like '%".$_GET['busqueda']."%' ";            
    
        $ConLClasificados=$bd->dbConsultar("SELECT c.id,c.titulo,c.descripcion,c.imagenes,c.factivo desde,DATE_ADD(c.factivo,INTERVAL p.dias day) hasta FROM clasificados as c inner join publicaciones as p on p.id=c.dias where c.estado='A' and curdate() between c.factivo and DATE_ADD(c.factivo,INTERVAL p.dias day) $Categoria $Pais $Estado $Foto $Busqueda limit 10",array());
        echo $bd->getSql();
        if (!$bd->Error){
            echo $bd->MsgError;
        }
        
        
?>  
<div class="Articulo">
   <div class="TituloArticulo">Resultado de la Busqueda de Clasificados</div>
   <div class="SeparadorArticuloInterno"></div>
   <div class="ContenidoArticulo">   
   <div id="vpListado" align='center'>
   <?php           
        if ($ConLClasificados->num_rows>0){
            while ($LClasificado=$ConLClasificados->fetch_array()){            
                if (strlen($LClasificado['imagenes'])>20){
                  $imagenes=explode("|",$Clasificado['imagenes']);
                 
                  $enc=false;
                  $i=0;                  
                  do {
                     if (!empty($imagenes[$i])){
                        $enc=true;                        
                        $imagen=$imagenes[$i];                        
                     }
                     $i++;
/*                     
                     if ($i>5)
                        exit();
*/                        
                  }while(!$enc || $i<=count($imagenes)); 
                    $imagen=substr($LClasificado['imagenes'],0,stripos($LClasificado['imagenes'],"|"));                    
                    $Separador=strrpos($imagen,"/")+1;
                    $Folder=substr($imagen,0,$Separador);
                    $Nombre=substr($imagen,$Separador); 
                    $FileImg=$Folder.$vp.$Nombre;                 
                }
                else{
                  $FileImg="../clasificados/sinfoto.jpg";
                }                                               
   ?>
            <a href="verclasificado.php?id=<?php echo $LClasificado['id']; ?>" alt="">
            <div class="vp">
               <div class="vpFoto"><center><img src="<?php echo $FileImg; ?>" alt=""/></center></div>
               <div class="vpDetalle">
                  <strong>Titulo: </strong><?php echo $LClasificado['titulo']; ?><br />
                  <strong>Descripción: </strong><?php echo $LClasificado['descripcion'].$LClasificado['imagenes']; ?><br />
                  <strong>Publicado Desde: </strong><?php echo FUser($LClasificado['desde']); ?>, <strong>Hasta el</strong> <?php echo FUser($LClasificado['hasta']); ?> <br />
                  <div class="vpInformacion"><strong>Click Para Ver Mas...</strong></div>
               </div>
            </div> 
            <div class="Limpiador"></div>
            </a>                       
   <?php 
            }
        }else{
         echo "<br /><br /><center>No se encontraron resultados</center>";
        }
    ?>
   </div>
   </div>
</div>  
<?php } ?>
 
<?php    
   $ConClasificados=$bd->dbConsultar("SELECT c.id,c.titulo,c.descripcion,c.imagenes,c.factivo desde,DATE_ADD(c.factivo,INTERVAL p.dias day) hasta FROM clasificados as c inner join publicaciones as p on p.id=c.dias where c.estado='A' and curdate() between c.factivo and DATE_ADD(c.factivo,INTERVAL p.dias day) limit 10",array());
   
   if (!$bd->Error){
      if ($ConClasificados->num_rows>0){         
?>
<div class="Articulo">
   <div class="TituloArticulo">Ultimos Clasificados</div>
   <div class="SeparadorArticuloInterno"></div>
   <div class="ContenidoArticulo">
      <div class="carrusel">
		<ul class="bloque-imagenes">
         <?php       
            $s=0;      
            
            while ($Clasificado=$ConClasificados->fetch_array()){                
               //Extraccion del Nombre de la Imagen Para la Vista Previa
               
                if (strlen($Clasificado['imagenes'])>20){                
                  $imagenes=explode("|",$Clasificado['imagenes']);
                 
                  $enc=false;
                  $i=0;                  
                  do {
                     if (!empty($imagenes[$i])){
                        $enc=true;                        
                        $imagen=$imagenes[$i];                        
                     }
                     $i++;
                     if ($i>5)
                        exit();
                  }while(!$enc && $i<=count($imagenes));  
                                
                  //$imagen=substr($Clasificado['imagenes'],0,stripos($Clasificado['imagenes'],"|"));
                  $Separador=strrpos($imagen,"/")+1;
                  $Folder=substr($imagen,0,$Separador);
                  $Nombre=substr($imagen,$Separador); 
                  $FileImg=$Folder.$vp.$Nombre;                                   
               }
               else{
                  $FileImg="../clasificados/sinfoto.jpg";
               }                               
         ?>      
			<li>
            <a href="verclasificado.php?id=<?php echo $Clasificado['id']; ?>" alt="">
            <div class="vp">
               <div class="vpFoto"><center><img src="<?php echo $FileImg; ?>" alt=""/></center></div>
               <div class="vpDetalle">
                  <strong>Titulo: </strong><?php echo $Clasificado['titulo']; ?><br />
                  <strong>Descripción: </strong><?php echo $Clasificado['descripcion']; ?><br />
                  <strong>Publicado Desde: </strong><?php echo FUser($Clasificado['desde']); ?>, <strong>Hasta el</strong> <?php echo FUser($Clasificado['hasta']); ?> <br />
                  <div class="vpInformacion"><strong>Click Para Ver Mas...</strong></div>
               </div>
            </div> 
            <div class="Limpiador"></div>
            </a>           
         </li>
         <!--<li><div class="vpSeparador"></div></li>-->
         
         <?php 
            }
         ?>
		</ul>
   </div>      
   </div>
</div>
<?php 
   }
}else{
   echo "<center>".$bd->MsgError."</center>";
}
?>
</body>
</html>