<?php
    session_start();    
	 include("../includes/classdb.php");
    include("../includes/classvd.php");
    include("../includes/funcion.php");
    include("../includes/checkin.php");
    
    $bd = new dbMysql();
    $bd->dbConectar();
    
    if (empty($_SERVER['HTTP_REFERER'])){
        if(!CheckCliente($bd)){
         header("location: index.php?op=Conexion");
        }
    }        
    else{
        if (!CheckOrigen()){
         header("location: index.php?op=Conexion"); 
        }
        elseif(!CheckCliente($bd)){
         header("location: index.php?op=Conexion");
        }                           
    } 
    
    //Busqueda de los Datos de la Participacion 
   $ConDatos=$bd->dbConsultar("select c.cedula,p.pais,m.moneda MonedaOficial,m.cambio,mo.moneda MonedaBase,m.cambio,f.monto from clientes as c inner join franquiciados as f on f.cliente=c.cedula inner join paises as p on c.pais=p.id inner join monedas as m on m.id=p.monedaoficial inner join monedas as mo on m.monedabase=mo.id where c.cedula=? and f.estado='A' limit 1",array($_SESSION['cliente']['cedula']));
   if (!$bd->Error){
      $Datos=$ConDatos->fetch_array();    
   }else{
      echo $bd->MsgError;
   }
   
    $ConConfig=$bd->dbConsultar("select c.tiempoactivo tiempo,c.minimoinicial minimo,c.mmaximo maximo,m.moneda ,c.conveniofpc as convenio from configuracion as c inner join monedas as m on m.id=c.monedabase where c.id=1");
    if (!$bd->Error){
        $FConfig=$ConConfig->fetch_array();

    }
   
   $CDiferido=CDiferido($bd,$_SESSION['cliente']['cedula']);
   $MaxRecapi=round($FConfig['maximo']-($Datos['monto']+$CDiferido['mbase']),2);
 
/*
    echo "<pre>";     
   print_r($Datos);
   print_r($FConfig);
   print_r($CDiferido);
   echo "</pre>";
*/          
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Clientes.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Retiros de Fondos </title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<!-- Estilos Para las Herramientas UI -->
<link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />
<link href="../css/estructura.css" rel="stylesheet" type="text/css" />
<link href="../css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/menu/stmenu.js" ></script>
<script type="text/javascript" src="../scripts/jquery/jquery.js" ></script>
<script type="text/javascript" src="../scripts/jquery/ffranquiciados.js" ></script>
<!-- Version JQuery UI 1.10 -->
<script type="text/javascript" src="../scripts/jquery/jqueryui.js" ></script>    
<!--Hora y Fecha -->
<script type="text/javascript" src="../scripts/calendario/datepicker.js" ></script>
<script type="text/javascript" src="../scripts/calendario/timepicker.js" ></script>
    <!-- Configuracion de Widget de Calendario -->
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
               	yearRange: "c-50:",
                maxDate:''+dia+'/'+mes+'/'+anio+'',
                regional:"es"	        
            });            
        });
    </script> 


<!-- InstanceEndEditable -->
</head>

<body>
<div id="Contenedor">
	<div id="Banner">
    	<img src="../imagenes/banner.png" border="0" />
    </div>
	<div id="FilaMenu">
	  	<div id="Menu">
    		<script type="text/javascript" src="../scripts/menu/cliente.js"></script>
	    </div>
    </div>
	<div id="DatosUser">
    	<div class="UserCedula"><strong>Cedula: </strong><?php echo $_SESSION['cliente']['cedula']; ?></div>        
    	<div class="UserNombre"><strong>Nombre: </strong><?php echo $_SESSION['cliente']['nombre']." ".$_SESSION['cliente']['apellido']; ?></div>                
    	<div class="UserPais"><strong>Pais: </strong><?php echo $_SESSION['cliente']['npais']; ?></div>        
        <div class="Limpiador"></div>        
    </div>    
    <div id="Cuerpo">    
    <!-- InstanceBeginEditable name="CentroClientes" -->
        <div class="Articulo" id="FormDatos">
                <div class="TituloArticulo">Nueva Recapitalización</div>
                <div class='SeparadorArticuloInterno'></div>
                <form id="FormFranquiciado" name="FormFranquiciado" method="POST"  enctype="multipart/form-data" action="pfranquiciados.php">
                <div class="ContenidoArticulo">
                  <?php if ($Datos['monto']==$FConfig['maximo']) echo "<center>Disculpe has alcanzado el monto maximo en la franquicia</center>";
                        else {
                  ?>                     
                
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Capital Actual: </div>
                        <div class="CampoMedio"><?php echo $Datos['monto']. " ".$Datos['MonedaBase']; ?> </div>
                        <div class="Etiqueta">Capital Maximo: </div>
                        <div class="CampoCorto"><?php echo $FConfig['maximo']. " ".$Datos['MonedaBase']; ?> </div>                        
                        <div class="Limpiador"></div>                    
                    </div>
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Capital Diferido: </div>
                        
                        <div class="CampoCorto" id="CDiferido"><?php echo ((double)$CDiferido['mbase']). " ".$Datos['MonedaBase']; ?> </div>
                    
                        <div class="EtiquetaLarga">Maximo a Recapitalizar: </div>
                        <div class="CampoCorto"><?php echo $MaxRecapi. " ".$Datos['MonedaBase']; ?> </div>
                        <div class="Limpiador"></div>                    
                    </div>                    
                                        
                  <fieldset>Datos del Deposito
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Banco: </div>
                        <div class="CampoMedio">
                        <?php
                           echo $bd->dbComboSimple("select b.id,b.banco from bancos as b inner join cuentas as c on c.banco=b.id where b.estado='A' and b.pais=? and c.estado='A' and c.cliente is null group by b.id",array($_SESSION['cliente']['idpais'],$_SESSION['cliente']['cedula']),"CBanco",0,array(1),null);
                        ?>
                        </div>
                        <div class="Etiqueta">Cuenta: </div>
                        <div class="CampoCorto">
                            <select id="Cuenta" name="Cuenta">
                                <option value="0"> Seleccione el Banco</option>
                            </select>                           
                           <input type="hidden" id="id" name="id" value="" />
                           <input type="hidden" id="idform" name="idform" value="CRecapitalizar" />
                           <input type="hidden" id="capital" name="capital" value="<?php echo ($MaxRecapi); ?>" />
                           <input type="hidden" id="maximo" name="maximo" value="<?php echo $FConfig['maximo']; ?>" />
                           <input type="hidden" id="cambio" name="cambio" value="<?php echo $Datos['cambio']; ?>" />
                           <input type="hidden" id="tform" name="tform" value="A" />
                        </div>                        
                        <div class="Limpiador"></div>                    
                    </div>
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Nro o Referencia: </div>
                        <div class="CampoMedio">
                            <input type="text" id="referencia" maxlength="15" size="20" name="referencia" value="" />                                
                        </div>
                        <div class="Etiqueta" >Fecha : </div>
                        <div class="CampoCorto">
                           <input type="text" id="fecha" name="fecha" readonly="true" tipo="fechahora" value="<?php echo date("d/m/Y"); ?>" />
                        </div>                        
                        <div class="Limpiador"></div>                    
                    </div>
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Monto en <?php echo $Datos['MonedaOficial']; ?>: </div>
                        <div class="CampoMedio">
                            <input type="text" id="MontoDep" name="MontoDep" value="0.00" />                                
                        </div>
                        <div class="Etiqueta">Monto en <?php echo $Datos['MonedaBase']; ?>: </div>
                        <div class="CampoCorto">
                            <input type="text" id="MonBase" name="MonBase" value="0.00" />                                
                        </div>                                                                                                   
                        <div class="Limpiador"></div>                    
                    </div> 
                    <div class="CampoCompleto">
                        <div class="Etiqueta" >Tasa de Cambio : </div>
                        <div class="CampoCorto" >1 <?php echo $Datos['MonedaBase']."=".$Datos['cambio']." ".$Datos['MonedaOficial']; ?></div>
                                                                           
                        <div class="Limpiador"></div>                    
                    </div>                                     
                  </fieldset>                 
                    
                    <div class="FormFin">
                        <input type="submit" id="Enviar" name="Enviar"  value="Guardar" />
                    </div> 
                    <?php 
                        }
                    ?>                                        
                </div>
                </form>   
                <div id="info"></div>
                <div class="Limpiador"></div>
        </div>
        <div class="Articulo" id="FormClave">
                <div class="TituloArticulo">Listado de Depositos Para Recapitalizar</div>
                <div class='SeparadorArticuloInterno'></div>
                <br />
                <table id="TRetiros" class="Tabla" align='center' >
                    <thead>
                        <tr>                            
                            <th>Banco</th>
                            <th>Cuenta</th>
                            <th>Referencia</th>
                            <th>Autorizado</th>
                            <th>Monto</th>
                            <th>Estado</th>                                                                                    
                                                                                                                
                            <th colspan="2"></th>
                        </tr>
                    </thead>
                     <?php 
                        $ConMovimiento=$bd->dbConsultar("select m.id,b.banco,c.cuenta,m.referencia,m.movimiento,m.fautoriza,monto_base,m.estado from movimientos as m inner join cuentas as c on c.id=m.cuenta inner join bancos as b on b.id=c.banco where m.cliente=? and m.franquicia='REC' and m.estado<>'I' ",array($_SESSION['cliente']['cedula']));
                        if ($bd->Error){
                           echo $bd->MsgError;
                        }else{
                           if ($ConMovimiento->num_rows>0){                    
                    ?>                     
                    <tbody>
                    <?php 
                        while ($Movimiento=$ConMovimiento->fetch_array()){                                                                        
                    ?>
                        <tr id="<?php echo $Movimiento['id']; ?>">                            
                            <td align='left'><?php echo $Movimiento['banco']; ?></td>
                            <td align='center' ><?php echo $Movimiento['cuenta']; ?></td>
                            <td align='center' ><?php echo $Movimiento['referencia']; ?></td>
                            <td align='center' ><?php echo FUser($Movimiento['fautoriza']); ?></td>                            
                            <td align='center' ><?php echo number_format($Movimiento['monto_base'],2,",","."); ?></td> 
                            <?php
                                switch($Movimiento['estado']){
                                    case "A":   $estado="Aprobado";     break;
                                    case "R":   $estado="En Proceso";   break;
                                }                                
                            ?>
                            <td align='center' ><?php echo $estado; ?></td>                                                                                                               
                            <td width="25"><?php if ($Movimiento['estado']=="R"){?> <a id="BorrarRec" rel="<?php echo $Movimiento['id']; ?>" href="#" alt="Eliminar Retiro"><img src="../imagenes/eliminar.png" border='0'/></a><?php } ?></td>
                        </tr>
                    <?php                    
                        }
                    ?>
                                            
                    </tbody>  
                    <?php 
                     }
                    ?>                  
                </table>
                <div id="info"></div>
                <br />                
        </div>
        <?php 
            }
        ?>
    <div class="Limpiador"></div>
    <!-- InstanceEndEditable --></div>
    <?php include('derechos.html'); ?> 
</div>       
</body>
<!-- InstanceEnd --></html>
