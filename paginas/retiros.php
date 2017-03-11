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
        //Analisis Vinculo Interno

    }

    //Consulto las Monedas
   $ConDatos=$bd->dbConsultar("select c.cedula,p.pais,m.moneda MonedaOficial,m.cambio,mo.moneda MonedaBase,m.cambio from clientes as c inner join paises as p on c.pais=p.id inner join monedas as m on m.id=p.monedaoficial inner join monedas as mo on m.monedabase=mo.id where cedula=? limit 1",array($_SESSION['cliente']['cedula']));
   if (!$bd->Error){
      $Datos=$ConDatos->fetch_array();
   }else{
      echo $bd->MsgError;
   }

   //Consulto Los Retiros Que Estan Por Procesar
   $Diferido=SDiferido($bd,$_SESSION['cliente']['cedula']);
   $_SESSION['cliente']['sdiferido']=(double) $Diferido;



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Clientes.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Retiros de Fondos </title>
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
                <div class="TituloArticulo">Nuevo Retiro de Fondos</div>
                <div class='SeparadorArticuloInterno'></div>
                <form id="FormFranquiciado" name="FormFranquiciado" method="POST"  enctype="multipart/form-data" action="pfranquiciados.php">
                <div class="ContenidoArticulo">
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Banco: </div>
                        <div class="CampoMedio">
                        <?php
                           echo $bd->dbComboSimple("select b.id,b.banco from bancos as b inner join cuentas as c on c.banco=b.id where b.estado='A' and b.pais=? and c.estado='A' and c.cliente=?",array($_SESSION['cliente']['idpais'],$_SESSION['cliente']['cedula']),"CBanco",0,array(1),null);
                        ?>
                        </div>
                        <div class="Etiqueta">Cuenta a Abonar: </div>
                        <div class="CampoCorto">
                            <select id="Cuenta" name="Cuenta">
                                <option value="0"> Seleccione el Banco</option>
                            </select>
                           <input type="hidden" id="id" name="id" value="" />
                           <input type="hidden" id="idform" name="idform" value="CRetiros" />
                           <input type="hidden" id="tform" name="tform" value="A" />
                        </div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="EtiquetaLarga">Monto del Retiro en <?php echo $Datos['MonedaBase']; ?>: </div>
                        <div class="CampoCorto">
                            <input type="text" id="MontoRet" name="MontoRet" value="0.00" />
                            <input type="hidden" readonly="true" id="cambio" name="cambio" value="<?php echo $Datos['cambio']; ?>" />
                            <input type="hidden" readonly="true" id="MonBase" name="MonBase" value="0.00" />
                        </div>
                        <div class="EtiquetaCorta" >En <?php echo $Datos['MonedaOficial']; ?>: </div>
                        <div class="CampoCorto" id="MontoBase">0.00</div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="EtiquetaLarga">Saldo Disponible en <?php echo $Datos['MonedaBase']; ?>: </div>
                        <div class="CampoCorto">
                            <input type="text" readonly="true" id="SaldoDis" name="SaldoDis" value="<?php echo round(($_SESSION['cliente']['saldo']),2); ?>" />
                        </div>
                        <div class="EtiquetaCorta">Nuevo Saldo: </div>
                        <div class="CampoCorto" id="NewSaldo">0.00</div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="EtiquetaLarga">Saldo Diferido en <?php echo $Datos['MonedaBase']; ?>: </div>
                        <div class="CampoCorto" id="SaldoDifBase"><?php echo number_format($Diferido,2); ?></div>
                        <div class="EtiquetaLarga" >Saldo Diferido en <?php echo $Datos['MonedaOficial']; ?>: </div>
                        <div class="CampoCorto"  id="SaldoDifOficial"><?php echo number_format($Diferido*$Datos['cambio'],2); ?></div>
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
        <div class="Articulo" id="FormClave">
                <div class="TituloArticulo">Listado de Retiros</div>
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
                        $ConMovimiento=$bd->dbConsultar("select m.id,b.banco,c.cuenta,m.referencia,m.movimiento,m.fautoriza,monto_base,m.estado from movimientos as m inner join cuentas as c on c.id=m.cuenta inner join bancos as b on b.id=c.banco where m.cliente=? and m.movimiento='Retiro' and m.estado<>'I' ",array($_SESSION['cliente']['cedula']));
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
                                    case "P":   $estado="En Proceso";   break;
                                }
                            ?>
                            <td align='center' ><?php echo $estado; ?></td>
                            <td width="25"><?php if ($Movimiento['estado']=="P"){?> <a id="BorrarRet" rel="<?php echo $Movimiento['id']; ?>" href="#" alt="Eliminar Retiro"><img src="../imagenes/eliminar.png" border='0'/></a><?php } ?></td>
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
