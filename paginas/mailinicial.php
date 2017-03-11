<?php
include("../includes/fmails.php");

        $html.='    <strong>Cedula:</strong>'.$cedula.'<br /> 
                    <strong>Nombre:</strong>'.$nombre.'<br />
                    <strong>Apellido:</strong>'.$apellido.'<br />
                    <strong>Dirección:</strong>'.$direccion.'<br />';        
            for ($i=0;$i<count($telefono);$i++)
                $html.= "<strong>Telefono ".($i+1).":</strong>".$telefono[$i]."<br />";
        $html.='    <strong>Dirección Electronica:</strong>'.$email.'<br />
                    <strong>Clave de Invitación:</strong>'.$cinvita.'<br />
                    <strong>Clave de Conexión:</strong>'.$cconexion.'<br />';



echo Send_Mail("inicial","admin@fin.com","yanccv@yahoo.es","Yan Carlos Cubides Varela",$html);

//echo $_SERVER["HTTP_HOST"];
/*
$_SERVER["HTTP_HOST"];
echo "<pre>";
print_r($_SERVER);
echo "</pre>";
echo $_SERVER['SERVER_NAME'];
$tele="0276-5173973|0412-1602839|0414-14785236";
$telefono=explode("|",$tele);
?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<title>Regintro Inicial de Partipantes</title>
</head>
<body>
   <div style="width: 600px; margin: auto; height: 450px;">
      <div style="height: 120px;" id="Banner">
        <img src="http://<?php echo $_SERVER['SERVER_NAME'] ?>/imagenes/bannermail.png" />         
      </div>
      <div style="background-color: #CEF0FF; border-top: #0098DB solid 1px; border-left: #0098DB solid 1px; border-right: #0098DB solid 1px; padding-top: 8px;  padding-bottom: 8px; font-size: 14px;  font-weight: bold; text-align:center">              
            <center><strong>Registro Inicial de Participación</strong></center>
        </div>
      <div style="padding:25px; border: #0098DB solid 1px;  -moz-border-radius: 0px 0px 5px 5px;  -webkit-border-radius: 0px 0px 5px 5px;  border-radius: 0px 0px 5px 5px;">        
        
        <strong>Cedula:</strong><?php echo $cedula; ?><br /> 
        <strong>Nombre:</strong><?php echo $nombre; ?><br />
        <strong>Apellido:</strong><?php echo $apellido; ?><br />
        <strong>Dirección:</strong><?php echo $direccion; ?><br />
        <?php
            for ($i=0;$i<count($telefono);$i++)
                echo "<strong>Telefono ".($i+1).":</strong>".$telefono[$i]."<br />";
        
        ?>
        <strong>Dirección Electronica:</strong><?php echo $email; ?><br />
        <strong>Clave de Invitación:</strong><?php echo $cinvita; ?><br />
        <strong>Clave de Conexión:</strong><?php echo $cconexion; ?><br />
        <br />
        <div style="font-size: 12px;">
            <center>Haz recibido este correo al registrarte como participante en <a style="text-decoration: none;" href="http://www.fondointeractivodenegocios.com">Fondo Interactivo de Negocios </a> Luego de realizar el deposito ve <a style="text-decoration: none;" href='http://www.fondointeractivodenegocios.com/paginas/activar.php' >Activar Participacion</a> y registrar su deposito para empezar a distrutar de los beneficios de formar parte de la franquicia de <strong>Participación de Capitales</strong></center>
        </div>
      </div>
      <div>
      
      </div>      
   </div>

</body>
</html>

*/

?>