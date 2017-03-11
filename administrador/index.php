<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/lightbox.css">
<script type="text/javascript" src="../scripts/jquery/jquery.js"></script>
<script type="text/javascript" src="../scripts/jquery/lightbox.js"></script>
<script type="text/javascript" src="../scripts/jquery/forms.js"></script>
<script type="text/javascript" src="../scripts/jquery/jqueryui.js" ></script>

    <link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />


    <script type="text/javascript" src="../scripts/calendario/datepicker.js" ></script>

    <script type="text/javascript" src="../scripts/jquery/jqueryui.js" ></script>

<link href="../css/estructura.css" rel="stylesheet" type="text/css" />

</head>
<body>
   <div id="FormLogin">
      <div>
         <img src="../imagenes/blogin.jpg" border='0'/>
      </div>
      <div id="TitleLogin">Panel Administrativo</div>
      <form id="Logueo" name="Logueo" action="procesar.php" method="post">
         <input type="hidden" id="idform" name="idform" value="Login"/>
         <div class="CampoCompleto">
            <div class="Etiqueta">Login:</div>
            <div class="CampoCorto"> <input type="text" id="login" name="login" size="20" maxlength="20" /></div>
            <div class="Limpiador"></div>
         </div>
         <div class="CampoCompleto">
            <div class="Etiqueta">Clave:</div>
            <div class="CampoCorto"> <input type="password" id="clave" name="clave" size="20" maxlength="20" /></div>
            <div class="Limpiador"></div>
         </div>
         <div class="FormFin" >
            <input type="submit" value="Entrar" id="Enviar" name="Enviar" />
         </div>
      </form>
      <div id="info"></div>
   </div>
</body>
</html>
