<html>
<head>
<link rel="stylesheet" href="../css/clasificados.css" type="text/css" />
<!--<link rel="stylesheet" href="../css/estructura.css" type="text/css" />
<link rel="stylesheet" href="../css/clasificados.css" type="text/css" />
<link rel="stylesheet" href="../css/formularios.css" type="text/css" />

<script type="text/javascript" src="../scripts/jquery/jquery.js"></script>
-->
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
        <input id="id" name="id" value="<?php echo $_GET['id']; ?>" type="hidden" />
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

        $ConLClasificados=$bd->dbConsultar("SELECT c.id,c.titulo,concat(substr(c.descripcion,1,75),'...') descripcion,c.imagenes,c.factivo desde,DATE_ADD(c.factivo,INTERVAL p.dias day) hasta FROM clasificados as c inner join publicaciones as p on p.id=c.dias where c.estado='A' and curdate() between c.factivo and DATE_ADD(c.factivo,INTERVAL p.dias day) $Categoria $Pais $Estado $Foto $Busqueda limit 10",array());
        //echo $bd->getSql();
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
                $FileImg=null;
                if (strlen($LClasificado['imagenes'])>20){
                  $imagenes=explode("|",$LClasificado['imagenes']);

                  $enc=false;
                  $i=0;
                  do {
                     if (!empty($imagenes[$i])){
                        $enc=true;
                        $imagen=$imagenes[$i];
                     }
                     $i++;

                  }while(!$enc && $i<=count($imagenes));
                    //$imagen=substr($LClasificado['imagenes'],0,stripos($LClasificado['imagenes'],"|"));
                    $Separador=strrpos($imagen,"/")+1;
                    $Folder=substr($imagen,0,$Separador);
                    $Nombre=substr($imagen,$Separador);
                    $FileImg=$Folder.$vp.$Nombre;
                }
   ?>
            <a href="verclasificado.php?id=<?php echo $LClasificado['id']; ?>" alt="">
            <div class="vp">
               <div class="vpFoto">
                   <center>
                       <?php if (!empty($FileImg)) { ?>
                           <img src="<?php echo $FileImg; ?>" alt=""/>
                       <?php } else { ?>
                           <svg xmlns='http://www.w3.org/2000/svg' height='100px' width='100px' version='1.0' viewBox='-300 -300 600 600' xml:space='preserve'>
                           <circle stroke='#AAA' stroke-width='10' r='250' fill='#FFF'/>
                           <text style='letter-spacing:1;text-anchor:middle;text-align:center;stroke-opacity:.5;stroke:#000;stroke-width:2;fill:#444;font-size:360px;font-family:Bitstream Vera Sans,Liberation Sans, Arial, sans-serif;line-height:125%;writing-mode:lr-tb;' transform='scale(.2)'>
                           <tspan y='-40' x='8'>NO IMAGE</tspan>
                           <tspan y='400' x='8'>AVAILABLE</tspan>
                           </text>
                           </svg>
                       <?php } ?>

                   </center>
               </div>
               <div class="vpDetalle">
                  <strong>Titulo: </strong><?php echo $LClasificado['titulo']; ?><br />
                  <strong>Descripción: </strong><?php echo $LClasificado['descripcion']; ?><br />
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

</body>
</html>
