<?php
	session_start();
	include("../includes/classdb.php");
    include("../includes/funcion.php");
	$bd = new dbMysql();
	$bd->dbConectar();	
    if ($_GET['tipoform']=="E"){
        $CCliente=$bd->dbConsultar("select c.cedula,c.nombre,c.apellido,c.fregistro,c.fupdate,c.minimoap,c.pais idpais,p.pais,p.monedaoficial idmoneda,m.moneda,m.cambio,ms.moneda basemoneda,f.monto,f.inicio,f.fin,date_sub(f.fin,interval -1 day) ninicio, date_sub(date_sub(f.fin,interval -1 day), interval -1 year) nfin from clientes as c inner join franquiciados as f on f.cliente=c.cedula inner join paises as p on p.id=c.pais inner join monedas as m on m.id=p.monedaoficial inner join monedas as ms on ms.id=m.monedabase where cedula=?",array($_GET['cedula']));
        if ($bd->Error){
            echo "<center>".$bd->MsgError."</center>";
            exit();
        }else{
            if ($CCliente->num_rows>0){
                $Cliente=$CCliente->fetch_array(); 
                $ConConfig=$bd->dbConsultar("select c.tiempoactivo tiempo,c.minimoinicial minimo,c.mmaximo maximo,m.moneda ,c.conveniofpc as convenio from configuracion as c inner join monedas as m on m.id=c.monedabase where c.id=1");
                if (!$bd->Error){
                  $FConfig=$ConConfig->fetch_array();
                }   
                $Cambio=CambioMonetario($bd,$Cliente['idmoneda'],date("d/m/Y"));
                //echo $Cambio;                            
            }
            else
            {
                echo "<center>Disculpe Cliente No Encontrado</center>";    
            }
        }
    }else{
        //$_GET['tipoform']="N";
    }    
    //print_r($FConfig);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Administrativa.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Depositos de Recapitalizaciones</title>
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />  
<script type="text/javascript" src="../scripts/jquery/jquery.js"></script>
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
       	  <form method="post" action="procesar.php" name="fdatos" id="fdatos">
          	<div class="FormTitulo">
            	Formulario de Revición de Depositos Para Activación de Participación                 
            </div>
            <div class="CampoCompleto">


            	<div class="EtiquetaCorta">Cedula:</div>
                <div class="CampoCorto"><?php echo $Cliente['cedula']; ?></div>
            	<div class="EtiquetaCorta">Nombre:</div>
                <div class="CampoCorto"><?php echo $Cliente['nombre']; ?></div>
            	<div class="EtiquetaCorta">Apellido:</div>
                <div class="CampoCorto"><?php echo $Cliente['apellido']; ?></div>
                <input name="idform" type="hidden" id="idform" value="renovar" />
                <input name="cedula" type="hidden" id="cedula" value="<?php echo $Cliente['cedula']; ?>" />                                                  
             </div>
            <fieldset>Participación Actual
            <div class="CampoCompleto">             
               <div class="EtiquetaLarga">Participacion Desde :</div>
               <div class="CampoCorto"><?php echo FUser($Cliente['inicio']); ?></div>
            	<div class="EtiquetaLarga">Hasta:</div>
               <div class="CampoCorto"><?php echo FUser($Cliente['fin']); ?></div>                                                                
               <div class="Limpiador"></div>
            </div>          
            <div class="CampoCompleto">
           	   <div class="EtiquetaLarga">Monto Activo en <?php echo $Cliente['basemoneda']; ?>:</div>
               <div class="CampoCorto"><?php echo number_format($Cliente['monto'],2,",",""); ?></div>
            	<div class="EtiquetaLarga">Monto Activo en <?php echo $Cliente['moneda']; ?>:</div>
               <div class="CampoCorto"><?php echo number_format($Cliente['monto']*$Cambio,2,",",""); ?></div>                         
               <div class="Limpiador"></div>
            </div>                         
             </fieldset>
             <fieldset>Renovación de Participación
             <div class="CampoCompleto">             
            	<div class="EtiquetaLarga">Monto Maximo en <?php echo $Cliente['basemoneda']; ?>:</div>
               <div class="CampoCorto"><?php echo number_format($FConfig['maximo'],2,",",""); ?></div>
            	<div class="EtiquetaLarga">Monto Maximo en <?php echo $Cliente['moneda']; ?>:</div>
               <div class="CampoCorto"><?php echo number_format($FConfig['maximo']*$Cambio,2,",",""); ?></div>                
             </div>
             <div class="CampoCompleto">             
            	<div class="EtiquetaLarga">Monto Minimo en <?php echo $Cliente['basemoneda']; ?>:</div>
               <div class="CampoCorto"><?php echo number_format($FConfig['minimo'],2,",",""); ?></div>
            	<div class="EtiquetaLarga">Monto Minimo en <?php echo $Cliente['moneda']; ?>:</div>
               <div class="CampoCorto"><?php echo number_format($FConfig['minimo']*$Cambio,2,",",""); ?></div>                
             </div>
            <div class="CampoCompleto">
            	<div class="EtiquetaLarga">Fecha de Inicio:</div>
                <div class="CampoCorto"><input type="text" id="desde" tipo='fechahora' name="desde" value="<?php echo FUser($Cliente['ninicio']); ?>" maxlength="10" size="15" /></div>
            	<div class="EtiquetaLarga">Fecha de Culminación:</div>
                <div class="CampoCorto"><input type="text" id="hasta" tipo='fechahora' name="hasta" value="<?php echo FUser($Cliente['nfin']); ?>" maxlength="10" size="15" /></div>                                                                
                <div class="Limpiador"></div>
            </div>                          
            </fieldset>
            <div class="CampoCompleto">
                <?php
                    $ConDep=$bd->dbConsultar("select b.banco,d.referencia,d.fecha,d.monto_oficial,d.monto_base from movimientos as d inner join cuentas as c on d.cuenta=c.id inner join bancos as b on b.id=c.banco where d.cliente=? and d.estado='V' and franquicia='REN'",array($Cliente['cedula']));
                    echo $bd->getSql();
                    if ($bd->Error){
                        echo "<center>".$bd->MsgError."</center>";
                    }else{
                        if ($ConDep->num_rows>0){
                            echo "<table border='1' width='100%' >";
                            echo "<tr style='font-weight:bold;'><td align='center'>Pais</td><td align='center'>Banco</td><td align='center'>Refrencia</td><td align='center'>Fecha</td><td align='center'>F de Efectivo</td><td align='center'>".$Cliente['moneda']."</td><td align='center'>".$Cliente['basemoneda']."</td><td align='center'>Check</td></tr>";
                            while ($FilaDep=$ConDep->fetch_array()){
                                echo "<tr><td align='center'>".$Cliente['pais']."</td><td align='center'>".$FilaDep['banco']."</td><td align='center'>".$FilaDep['referencia']."</td><td align='center'>".FUser($FilaDep['fecha'])."</td><td align='center'><input id='fefectivo[".$FilaDep['referencia']."]'  name='fefectivo[".$FilaDep['referencia']."]' type='text' tipo='fechahora' value='".date("d/m/Y")."' readonly='true' /></td><td align='center'>".number_format($FilaDep['monto_oficial'],2,",","")."</td><td align='center'>".number_format($FilaDep['monto_base'],2,",","")."</td><td align='center'><input type='checkbox' name='deposito[]' id='deposito[]' value='".$FilaDep['referencia']."' /><input name='local[".$FilaDep['referencia']."]' type='hidden' id='local[".$FilaDep['referencia']."]' value='".$FilaDep['monto_oficial']."' /><input name='base[".$FilaDep['referencia']."]' type='hidden' id='base[".$FilaDep['referencia']."]' value='".$FilaDep['monto_base']."' /></td></tr>";
                            }
                            echo "</table>";
                        }
                        else {
                            echo "<center>Disculpe No Se Encontraron Depositos</center>";
                        }
                    }                                
                ?>
                <div class="Limpiador"></div>
            </div> 
                     
            <div class="FormFin">
            	<input name="Boton" id="Boton" value="Confirmar Depositos" type="submit" />
            </div>           
      	  </form>
        </div>
        <div id="info"></div>
		<!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>