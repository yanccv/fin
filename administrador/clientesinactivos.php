<?php
    session_start();
    if (empty($_SESSION['usuario']['login'])) {
        header("location: index.php");
    }
    include("../includes/classdb.php");
    include("../includes/funcion.php");
    if ($_POST['Limpiar']) {
        unset($_POST['cedula']);
        unset($_POST['nombre']);
        unset($_POST['apellido']);
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Administrativa.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Bienvenido Panel Administrativo de Fondo Interactivo de Negocios</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script type="text/javascript" src="../scripts/jquery/jquery.js"></script>
<script type="text/javascript" src="../scripts/jquery/jqueryui.js"></script>
<script type="text/javascript" src="../scripts/jquery/forms.js"></script>
<link href="../css/estructura.css" rel="stylesheet" type="text/css" />
<link href="../css/jqueryui.css" rel="stylesheet" type="text/css" />
<!-- InstanceEndEditable -->
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
          	<div class="FormTitulo">
            	Formulario de Busqueda de Clientes Inactivos

            </div>

            <div id="dialog-form" title="Listado de Hijos">
                <div id='Childs'>
                </div>

            </div>


            <div class="SeparadorArticuloInterno"></div>
            <form id="BuscarClientesInactivos" name="BuscarClientesInactivos" method="post">
            <div class="CampoCompleto">
            	<div class="EtiquetaCorta">Cedula: </div>
                <div class="CampoCorto">
                    <input name="cedula" type="text" id="cedula" value="<?php echo $_POST['cedula']; ?>" size="10" maxlength="20" />
                </div>
                <div class="Limpiador"></div>
            </div>
            <div class="CampoCompleto">
                <div class="EtiquetaCorta">Nombre: </div>
                <div class="CampoCorto">
                    <input name="nombre" type="text" id="nombre" value="<?php echo $_POST['nombre']; ?>" size="20" maxlength="30" />
                </div>
                <div class="EtiquetaCorta">Apellido: </div>
                <div class="CampoMedio">
                    <input name="apellido" type="text" id="apellido" value="<?php echo $_POST['apellido']; ?>" size="20" maxlength="30" />
                </div>
                <div class="Limpiador"></div>
            </div>
            <div class="FormFin">
            	<input name="Boton" id="Boton" value="Buscar" type="submit" />
                <input name="Limpiar" id="Limpiar" value="Ver Todos" type="submit" />
            </div>
            </form>
        </div>
        <div class="SeparadorArticuloExterno"></div>
        <div class="FormDatos">
          	<div class="FormTitulo">
            	Listado de Resultados
            </div>
            <div class="SeparadorArticuloInterno"></div>
        <?php
        $bd= new dbMysql();
        $bd->dbConectar();
        if ($_POST) {
            $filtro=null;
            if (!empty($_POST['cedula'])) {
                $filtro.=" c.cedula like '".(int) $_POST['cedula']."%'";
            }
            if (!empty($_POST['nombre'])) {
                $filtro.=" and c.nombre like '%". $bd->dbEscape($_POST['nombre'])."%' ";
            }
            if (!empty($_POST['apellido'])) {
                $filtro.=" and c.apellido like '%".$bd->dbEscape($_POST['apellido'])."%' ";
            }
            if (!empty($filtro)) {
                $filtro='where '.$filtro;
            }
            $conFranquiciados=$bd->dbConsultar("select c.cedula cliente,concat(c.nombre,' ',c.apellido) nombres,c.telefonos,c.email,c.fregistro,datediff(curdate(),c.fregistro) dias FROM franquiciados AS f RIGHT JOIN (select cedula,nombre,apellido,telefonos,email,fregistro,cinvita from clientes where estado='I' and not cinvita is null) AS c ON c.cedula= f.cliente {$filtro} order by dias desc");
        } else {
            //$conFranquiciados=$bd->dbConsultar("select c.cedula cliente,concat(c.nombre,' ',c.apellido) nombres,c.telefonos,c.email,c.fregistro,datediff(curdate(),c.fregistro) dias FROM franquiciados AS f RIGHT JOIN (select cedula,nombre,apellido,telefonos,email,fregistro,cinvita from clientes where estado='I' and not cinvita is null) AS c ON c.cedula= f.cliente order by dias desc");
        }
        if (!$bd->Error) {
            echo '<table>';
            echo '<thead>';
            echo '<tr><th align="center">Cedula</th><th>Cliente</th><th>Email</th><th align="center">Registro</th><th align="center">D&iacute;as</th><th align="center">Directos</th><th align="center">Activos</th><th align="center">Todos</th><th colspan="3" align="center">Opciones</th></tr>';
            echo '</thead>';
            while ($fila=$conFranquiciados->fetch_assoc()) {
                $conMonto=$bd->dbConsultar("select monto_base from movimientos where cliente=? and estado='A' order by fecha desc limit 1", array($fila['cliente']));
                if (!$bd->Error) {
                    $ultmonto=0;
                    if ($conMonto->num_rows>0) {
                        $filaMonto=$conMonto->fetch_assoc();
                        $ultmonto=sprintf('%4.2f', $filaMonto['monto_base']);
                    }
                } else {
                    echo $bd->MsgError;
                }
                $todos=0;
                $activos=0;
                $directos=0;
                $nivel=0;
                $porc=100;
                $monto=0;
                RecorrerAsociados($bd, $fila['cliente'], $todos, $activos, $directos, $nivel, $porc, $monto);
                echo "
                <tr id='{$fila['cliente']}'>
                    <td align='center'>
                        <a href='#' rel='{$fila['cliente']}' names='{$fila['nombres']}' class='show-details'>
                        {$fila['cliente']}
                        </a>
                    </td>
                    <td title='{$fila['telefonos']}'>{$fila['nombres']}</td>
                    <td>{$fila['email']}</td>
                    <td align='center'>".FTUser($fila['fregistro'])."</td>
                    <td align='center'>{$fila['dias']}</td>
                    <td align='center'>{$directos}</td>
                    <td align='center'>{$activos}</td>
                    <td align='center'>{$todos}</td>
                    <td align='center'>
                        <a href='editarcliente.php?cedula={$fila['cliente']}'>
                            <img src='../imagenes/editar.png' border='0' title='Editar'>
                        </a>
                    </td>
                    <td align='center'>
                        <a href='#' class='QuitarInactivo' rel='{$fila['cliente']}'>
                            <img src='../imagenes/eliminar.png' border='0' title='Eliminar'>
                        </a>
                    </td>
                    <td align='center'>
                        <a href='#' class='EliminarInactivo' rel='{$fila['cliente']}' >
                            <img src='../imagenes/eliminar.png' border='0' title='Borrado Completo' alt='Borrado Completo'>
                        </a>
                    </td>
                </tr>·";
            }
            echo '</table>';
        }
        ?>
        </div>
        <!-- InstanceEndEditable --></div>
    </div>
</body>
<!-- InstanceEnd --></html>
