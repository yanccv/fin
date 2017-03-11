<?php
header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
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
        //Analisis Vinculo Interno
                        
    }
    
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Clientes.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Cuentas Bancarias</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link href="../css/estructura.css" rel="stylesheet" type="text/css" />
<link href="../css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/menu/stmenu.js" ></script>
<script type="text/javascript" src="../scripts/jquery/jquery.js" ></script>
<script type="text/javascript" src="../scripts/jquery/ffranquiciados.js" ></script>
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
                <div class="TituloArticulo">Agregar Cuenta Bancaria</div>
                <div class='SeparadorArticuloInterno'></div>
                <form id="FormFranquiciado" name="FormFranquiciado" method="POST"  enctype="multipart/form-data" action="pfranquiciados.php">
                <div class="ContenidoArticulo">
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Banco: </div>
                        <div class="CampoMedio">
                        <?php
                           echo $bd->dbComboSimple("select b.id,b.banco from bancos as b where b.estado='A' and b.pais=?",array($_SESSION['cliente']['idpais']),"Banco",0,array(1),null);
                        ?>
                        </div>
                        <div class="Etiqueta">Nro de Cuenta: </div>
                        <div class="CampoCorto">
                           <input type="text" maxlength="20" size="30" id="cuenta" value="" name="cuenta" />
                           <input type="hidden" id="id" name="id" value="" />
                           <input type="hidden" id="idform" name="idform" value="CCuenta" />
                           <input type="hidden" id="tform" name="tform" value="A" />
                        </div>                        
                        <div class="Limpiador"></div>                    
                    </div>
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Tipo de Cuenta: </div>
                        <div class="CampoMedio">
                           <input type="radio" name="tipo" value="A" id="tipo_0">Ahorro                                                       
                           <input type="radio" name="tipo" value="C" id="tipo_1">Corriente                                                                                 
                        </div>
                        <div class="Limpiador"></div>                    
                    </div>
                    <div class="FormFin">
                        <input type="submit" id="Enviar" name="Enviar"  value="Guardar" />
                    </div>                                         
                </div>
                </form>   
                <div id="info"></div>
                <div class="Limpiador"></div>
        </div>
        <?php 
            $ConCuentas=$bd->dbConsultar("select c.id,b.banco,c.cuenta,c.tipo,c.estado from cuentas as c inner join bancos as b on b.id=c.banco where b.pais=? and c.cliente=? and c.estado='A'",array($_SESSION['cliente']['idpais'],$_SESSION['cliente']['cedula']));
            if ($bd->Error){
                echo $bd->MsgError;
            }else{
                if ($ConCuentas->num_rows>0){
                    
        ?>
        <div class="Articulo" id="FormClave">
                <div class="TituloArticulo">Listado de Cuentas Bancarias</div>
                <div class='SeparadorArticuloInterno'></div>
                <br />
                <table id="TCuentas" class="Tabla" align='center' >
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Banco</th>
                            <th>Tipo</th>
                            <th>Cuenta</th>                            
                            <th>Estado</th>
                            <th colspan="2"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        while ($Cuenta=$ConCuentas->fetch_array()){                                                                        
                    ?>
                        <tr id="<?php echo $Cuenta['id']; ?>">
                            <td align='center'><?php echo $Cuenta['id']; ?></td>
                            <td align='left'><?php echo $Cuenta['banco']; ?></td>
                            <td align='center'>
                            <?php 
                                switch($Cuenta['tipo']){
                                    case "A":    echo "Ahorro";  break;
                                    case "C":    echo "Corriente";  break;                                    
                                }
                            ?>
                            </td>
                            <td align='center' ><?php echo $Cuenta['cuenta']; ?></td>                            
                            <td align='center' ><?php echo $Cuenta['estado']; ?></td>
                            <td width="25"><a id="EditarCue" href="#" rel="<?php echo $Cuenta['id']; ?>" alt="Editar Cuenta"><img src="../imagenes/editar.png" border='0'/></a></td>
                            <td width="25"><a id="BorrarCue" href="#" rel="<?php echo $Cuenta['id']; ?>" alt="Eliminar Cuenta"><img src="../imagenes/eliminar.png" border='0'/></a></td>
                        </tr>
                    <?php                    
                        }
                    ?>
                                            
                    </tbody>                    
                </table>
                <div id="info"></div>
                <br />                
        </div>
        <?php 
                }
            }            
        ?>
    <div class="Limpiador"></div>
    <!-- InstanceEndEditable --></div>
    <?php include('derechos.html'); ?> 
</div>       
</body>
<!-- InstanceEnd --></html>
