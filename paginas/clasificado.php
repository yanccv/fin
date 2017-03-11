<?php
    session_start();
    include("../includes/classdb.php");
    $bd= new dbMysql();
    $bd->dbConectar();
    //Hallar la Conversion y el Nombre de la Moneda en el Pais del Cliente
    $ConPaisMoneda=$bd->dbConsultar("SELECT m.cambio,m.moneda from paises as p inner join monedas as m on m.id=p.monedaoficial where p.id=? limit 1", array($_SESSION['cliente']['idpais']));
    if (!$bd->Error) {
        if ($ConPaisMoneda->num_rows>0) {
            $PaisMoneda=$ConPaisMoneda->fetch_array();
        } else {
            echo "Disculpe La Moneda Local No Ha Sido Fijada";
        }
    } else {
        echo $bd->MsgError;
    }
    //print_r($_SESSION);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Clientes.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Clasificados</title>
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
                <div class="TituloArticulo">Registro de Clasificados</div>
                <div class='SeparadorArticuloInterno'></div>
                <div class="ContenidoArticulo" id="IdContenidoArticulo">
                   <form id="RegClasificado" name="RegClasificado" method="POST" enctype="multipart/form-data" action="pfranquiciados.php">
                   <input type="hidden" id="idform" name="idform" value="RClasificado" />
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Categoria: </div>
                        <div class="CampoCorto">
                        <?php
                            echo $bd->dbComboSimple("select id, categoria from categorias", array(), "categoria", 0, array(1), null);
                        ?>
                        </div>
                        <div class="Etiqueta">Plan: </div>
                        <div class="CampoCorto">
                        <?php
                           //SELECT p.id,concat(p.dias,' Días', if (p.foto='S',', Con Foto',', Sin Foto'),', Por ',p.costo,'.') plan FROM publicaciones as p where ISNULL(tipo);
                            //echo $bd->dbComboSimple("select id,concat(dias,' Días',',',if(foto='S',' Con Foto',' Sin Foto'),', Costo: ',costo) from publicaciones where isnull(tipo) and pais=?",array($_SESSION['cliente']['idpais']),"CPlan",0,array(1),null);
                            //
                            echo $bd->dbComboSimple("SELECT p.id,concat(p.dias,' Días', if (p.foto='S',', Con Foto',', Sin Foto'),', Por ',p.costo*?,' ',?) plan FROM publicaciones as p where ISNULL(tipo)", array($PaisMoneda['cambio'], $PaisMoneda['moneda'], $_SESSION['cliente']['idpais']), "CPlan", 0, array(1), null);
                        ?>
                        </div>

                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Titulo: </div>
                        <div class="CampoMedio">
                            <input id="titulo" name="titulo" type="text" placeholder="Maximo 60 Caracteres" maxlength="60" size="60" />
                        </div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Descripción: </div>
                        <div class="CampoMedio">
                            <input id="descripcion" name="descripcion" type="text" placeholder="Maximo 1200 Caracteres" maxlength="1200" size="100" />
                        </div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Contacto: </div>
                        <div class="CampoMedio">
                            <input id="contacto" name="contacto" type="text" maxlength="150" placeholder="Maximo 150 Caracteres" size="100" />
                        </div>
                        <div class="Limpiador"></div>
                    </div>
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Estado: </div>
                        <div class="CampoMedio">
                        <?php
                            echo $bd->dbComboSimple("select id,estado from estados where pais=?", array($_SESSION['cliente']['idpais']), "estado", 0, array(1), null);
                        ?>
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
                    <div class="CampoCompleto" id="LoadFotos" style="visibility: hidden; display: none;" >
                        <div class="DetaLoadImg">
                            <div class="CampoCorto">
                                <div class="botonInputFileModificado">
                                    <input type="file" class="inputImagenOculto" id="imagen[]" name="imagen[]"/>
                                    <div class="boton">Buscar Imagen 1</div>
                                </div>
                            </div>
                            <div class="CampoCorto">
                                <div class="botonInputFileModificado">
                                    <input type="file" class="inputImagenOculto" id="imagen[]" name="imagen[]"/>
                                    <div class="boton">Buscar Imagen 2</div>
                                </div>
                            </div>
                            <div class="CampoCorto">
                                <div class="botonInputFileModificado">
                                    <input type="file" class="inputImagenOculto" id="imagen[]" name="imagen[]"/>
                                    <div class="boton">Buscar Imagen 3</div>
                                </div>
                            </div>
                            <div class="CampoCorto">
                                <div class="botonInputFileModificado">
                                    <input type="file" class="inputImagenOculto" id="imagen[]" name="imagen[]"/>
                                    <div class="boton">Buscar Imagen 4</div>
                                </div>
                            </div>
                            <div class="Limpiador"></div>
                        </div>
                        <div class="DetaLoadImg">
                            <div class="CampoCorto">
                                <div class="botonInputFileModificado">
                                    <input type="file" class="inputImagenOculto" id="imagen[]" name="imagen[]"/>
                                    <div class="boton">Buscar Imagen 5</div>
                                </div>
                            </div>
                            <div class="CampoCorto">
                                <div class="botonInputFileModificado">
                                    <input type="file" class="inputImagenOculto" id="imagen[]" name="imagen[]"/>
                                    <div class="boton">Buscar Imagen 6</div>
                                </div>
                            </div>
                            <div class="CampoCorto">
                                <div class="botonInputFileModificado">
                                    <input type="file" class="inputImagenOculto" id="imagen[]" name="imagen[]"/>
                                    <div class="boton">Buscar Imagen 7</div>
                                </div>
                            </div>
                            <div class="CampoCorto">
                                <div class="botonInputFileModificado">
                                    <input type="file" class="inputImagenOculto" id="imagen[]" name="imagen[]"/>
                                    <div class="boton">Buscar Imagen 8</div>
                                </div>
                            </div>
                            <div class="Limpiador"></div>
                        </div>
                        <div class="DetaLoadImg">
                            <div class="CampoCorto">
                                <div class="botonInputFileModificado">
                                    <input type="file" class="inputImagenOculto" id="imagen[]" name="imagen[]"/>
                                    <div class="boton">Buscar Imagen 9</div>
                                </div>
                            </div>
                            <div class="CampoCorto">
                                <div class="botonInputFileModificado">
                                    <input type="file" class="inputImagenOculto" id="imagen[]" name="imagen[]"/>
                                    <div class="boton">Buscar Imagen 10</div>
                                </div>
                            </div>
                            <div class="CampoCorto">
                                <div class="botonInputFileModificado">
                                    <input type="file" class="inputImagenOculto" id="imagen[]" name="imagen[]"/>
                                    <div class="boton">Buscar Imagen 11</div>
                                </div>
                            </div>
                            <div class="CampoCorto">
                                <div class="botonInputFileModificado">
                                    <input type="file" class="inputImagenOculto" id="imagen[]" name="imagen[]"/>
                                    <div class="boton">Buscar Imagen 12</div>
                                </div>
                            </div>
                            <div class="Limpiador"></div>
                        </div>

                        <div class="Limpiador"></div>
                    </div>
                    <div class="FormFin">
                        <input type="submit" id="Enviar" name="Enviar"  value="<?php if ($found) {
    echo 'Actualizar Clasificado';
} else {
    echo 'Registrar Clasificado';
} ?>" />
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
