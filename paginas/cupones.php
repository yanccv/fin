<?php
    session_start();
    include("../includes/classdb.php");
    $bd= new dbMysql();
    $bd->dbConectar();
    //Hallar la Conversion y el Nombre de la Moneda en el Pais del Cliente
    $ConPaisMoneda=$bd->dbConsultar("SELECT m.cambio,m.moneda from paises as p inner join monedas as m on m.id=p.monedaoficial where p.id=? limit 1", array($_SESSION['cliente']['idpais']));
    if (!$bd->Error) {
        if ($ConPaisMoneda->num_rows>0) {
            $PaisMoneda=$ConPaisMoneda->fetch_object();
            if (isset($_GET['idcupon'])) {
                $conCupon=$bd->dbConsultar("select * from cupones where id=?", array($_GET['idcupon']));
                if (!$bd->Error) {
                    $Cupon=$conCupon->fetch_objec();
                    $Cupon->telefono=explode('|', $Cupon->telefono);
                } else {
                    echo $bd->MsgError;
                }
            }
            $conConfig=$bd->dbConsultar("select preciocupon,porcecupon from configuracion limit 1");
            if (!$bd->Error) {
                $Config=$conConfig->fetch_object();
            } else {
                echo $bd->MsgError;
            }
        } else {
            echo "Disculpe La Moneda Local No Ha Sido Fijada";
        }
    } else {
        echo $bd->MsgError;
    }
    //print_r($_SESSION);
    //echo '<pre>';
    //print_r($PaisMoneda);
    //echo '</pre>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Clientes.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Cupones Web</title>
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
        <div class="Articulo" id="IdArticulo" >
                <div class="TituloArticulo">Registro de Cupones Web</div>
                <div class='SeparadorArticuloInterno'></div>
                <div class="ContenidoArticulo" id="IdContenidoArticulo">
                    <form id="RegClasificado" name="RegClasificado" method="POST" enctype="multipart/form-data" action="pfranquiciados.php">
                    <input type="hidden" id="idform" name="idform" value="Cupones" />
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Titulo: </div>
                        <div class="CampoMedio">
                            <input id="titulo" name="titulo" type="text" value="<?php echo $Cuppon->titulo; ?>" placeholder="Maximo 100 Caracteres" maxlength="100"/>
                        </div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Dirección: </div>
                        <div class="CampoMuyLargo">
                            <input id="direccion" name="direccion" type="text" value="<?php echo $Cuppon->direccion; ?>" placeholder="Maximo 200 Caracteres" maxlength="200"/>
                        </div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Teléfono 1: </div>
                        <div class="CampoCorto">
                            <input id="tele1" name="tele1" type="text" maxlength="12" placeholder="####-#######" />
                        </div>
                        <div class="Etiqueta">Teléfono 2: </div>
                        <div class="CampoCorto">
                            <input id="tele2" name="tele2" type="text" maxlength="12" placeholder="####-#######" />
                        </div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Email: </div>
                        <div class="CampoMedio">
                            <input id="email" name="email" type="text" maxlength="100" placeholder="Maximo 100 Caracteres" />
                        </div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Precio x Cupon: </div>
                        <div class="CampoCorto">
                            <input id="solomonto" name="solomonto" type="text"  value="<?php echo round($Config->preciocupon * $PaisMoneda->cambio, 2).' '.$PaisMoneda->moneda; ?>" readonly='true' placeholder="0.00" />
                            <input id="preciocupon" name="preciocupon" type="hidden" value="<?php echo round($Config->preciocupon * $PaisMoneda->cambio, 2); ?>"/>
                            <input id="porcecupon" name="porcecupon" type="hidden" value="<?php echo $Config->porcecupon; ?>" />
                        </div>
                        <div class="Etiqueta">Cantidad de Cupones: </div>
                        <div class="CampoCorto">
                            <input id="cupones" name="cupones" type="text" maxlength="10" placeholder="0" />
                        </div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Monto Depositado: </div>
                        <div class="CampoCorto">
                            <input id="monto" name="monto" type="text" size="10" maxlength="10" placeholder="0.00" />
                        </div>
                        <div class="Etiqueta">Monto a Depositar: </div>
                        <div class="CampoCorto">
                            <input id="total" name="total" type="text" readonly='true' maxlength="10" placeholder="0.00" />
                        </div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Banco: </div>
                        <div class="CampoMedio">
                        <?php
                           echo $bd->dbComboSimple("select b.id,b.banco from bancos as b inner join cuentas as c on c.banco=b.id where b.estado='A' and b.pais=? and c.estado='A' and c.cliente is null group by b.id", array($_SESSION['cliente']['idpais'], $_SESSION['cliente']['cedula']), "CBanco", 0, array(1), null);
                        ?>
                        </div>
                        <div class="Etiqueta">Cuenta: </div>
                        <div class="CampoCorto">
                            <select id="Cuenta" name="Cuenta">
                                <option value="0"> Seleccione el Banco</option>
                            </select>
                        </div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="EtiquetaLarga">Nro de Deposito/Transferencia: </div>
                        <div class="CampoMedio">
                            <input id="numero" name="numero" type="text" maxlength="15" size="15" />
                        </div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto" id="LoadFotos">
                        <div class="DetaLoadImg">
                            <div class="CampoCorto">
                                <div class="botonInputFileModificado">
                                    <input type="file" class="inputImagenOculto" id="imagen[]" name="imagen[]"/>
                                    <div class="boton">Imagen de Publicidad</div>
                                </div>
                            </div>
                            <div class="CampoMedio">Nota: El tamaño maximo de la imagen de la publicidad es 345x450px</div>
                            <div class="Limpiador"></div>
                        </div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="FormFin">
                        <input type="submit" id="Enviar" name="Enviar"  value="<?php if ($found) { echo 'Actualizar Cupon'; } else { echo 'Registrar Cupon'; } ?>" />
                    </div>
                    </form>
                   <div id="info"></div>
                  <div class="Limpiador"></div>
                </div>

        </div>
    <!-- InstanceEndEditable --></div>
    <?php include('derechos.html'); ?>
</div>
</body>
<!-- InstanceEnd --></html>
