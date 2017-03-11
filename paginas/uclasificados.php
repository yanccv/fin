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

<?php
   $ConClasificados=$bd->dbConsultar("SELECT c.id,c.titulo,concat(substr(c.descripcion,1,75),'...') descripcion,c.imagenes,c.factivo desde,DATE_ADD(c.factivo,INTERVAL p.dias day) hasta FROM clasificados as c inner join publicaciones as p on p.id=c.dias where c.estado='A' and curdate() between c.factivo and DATE_ADD(c.factivo,INTERVAL p.dias day) limit 10",array());

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
               $FileImg=null;
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
         ?>
			<li>
            <a href="verclasificado.php?id=<?php echo $Clasificado['id']; ?>" alt="">
            <div class="vp">
               <div class="vpFoto">
                   <center>
                       <?php if (!empty($FileImg)){ ?>
                           <img src="<?php echo $FileImg; ?>" alt=""/><?php
                       } else { ?>
                           <svg xmlns='http://www.w3.org/2000/svg' height='100px' width='100px' version='1.0' viewBox='-300 -300 600 600' xml:space='preserve'>
                           <circle stroke='#AAA' stroke-width='10' r='250' fill='#FFF'/>
                           <text style='letter-spacing:1;text-anchor:middle;text-align:center;stroke-opacity:.5;stroke:#000;stroke-width:2;fill:#444;font-size:360px;font-family:Bitstream Vera Sans,Liberation Sans, Arial, sans-serif;line-height:125%;writing-mode:lr-tb;' transform='scale(.2)'>
                           <tspan y='-40' x='8'>NO IMAGE</tspan>
                           <tspan y='400' x='8'>AVAILABLE</tspan>
                           </text>
                           </svg>
                           <?php
                       }?>  </center></div>
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
