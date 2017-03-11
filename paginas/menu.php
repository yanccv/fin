  <div id="FilaMenu">
  	<div id="Menu">
    	<?php include("../menu/publico.php");  ?>
    </div>
    <div id="Reproductor">
      <object width="165" height="32" hspace="0" vspace="0" >
      <param name="movie" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/reproductor/reproductor.swf" >
      <param name="movie" align="left" > 
      <param name="play" value="true" > 
      <embed value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/reproductor/reproductor.swf" >
      </embed>
      </object>
    </div>    
  </div>
  <marquee><?php echo $marquesina; ?></marquee>
  