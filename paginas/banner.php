<?php
    session_start();
	 include("../includes/classdb.php");
    $bd= new dbMysql();
    $bd->dbConectar();
    //Hallar la Conversion y el Nombre de la Moneda en el Pais del Cliente
    $ConPaisMoneda=$bd->dbConsultar("SELECT m.cambio,m.moneda from paises as p inner join monedas as m on m.id=p.monedaoficial where p.id=? limit 1",array($_SESSION['cliente']['idpais']));
    if (!$bd->Error){
      if ($ConPaisMoneda->num_rows>0){
         $PaisMoneda=$ConPaisMoneda->fetch_array();
      }else{
         echo "Disculpe La Moneda Local No Ha Sido Fijada";
      }
    }else{
      echo $bd->MsgError;
    }
        
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Clientes.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Documento sin título</title>
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
                <div class="TituloArticulo">Registro de Banners</div>
                <div class='SeparadorArticuloInterno'></div>                
                <div class="ContenidoArticulo" id="IdContenidoArticulo">
                   <form id="RegClasificado" name="RegClasificado" method="POST" enctype="multipart/form-data" action="pfranquiciados.php">
                   <input type="hidden" id="idform" name="idform" value="RBanners" />                
                   <input type="hidden" id="ancho" name="ancho" value="" />
                   <input type="hidden" id="alto" name="alto" value="" />
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Sección: </div>
                        <div class="CampoCorto">
                        <?php
                            echo $bd->dbComboSimple("select id,area from areas order by id asc",array(),"CArea",0,array(1),null);                            
                        ?>
                        </div>                    
                        <div class="Limpiador"></div>                                            
                    </div> 
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Posición: </div>
                        <div class="CampoCorto">
                            <select id="CBanner" name="CBanner">
                                <option value="0">Seleccione Sección</option>                            
                            </select>

                        </div>                    
                        <div class="Limpiador"></div>                                            
                    </div>
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Dimenciones: </div>
                        <div class="CampoMedio" id="Dimenciones">                            
                        </div>
                        <div class="Limpiador"></div>                    
                    </div>                    
                    <div class="CampoCompleto">
                        <div class="EtiquetaCorta">Plan: </div>
                        <div class="CampoCorto">
                            <select id="CPlanBanner" name="CPlanBanner">
                                <option value="0">Seleccione Posición</option>                            
                            </select>

                        </div>                    
                        <div class="Limpiador"></div>                                            
                    </div>                                         
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Titulo del Banner: </div>
                        <div class="CampoMedio">
                            <input id="titulo" name="titulo" type="text" placeholder="Identificación del Banner" maxlength="60" size="60" />
                        </div>
                        <div class="Limpiador"></div>                    
                    </div>   
                    <div class="CampoCompleto">
                        <div class="Etiqueta">Enlace del Banner: </div>
                        <div class="CampoMedio">
                            <input id="enlace" placeholder="Debe anteponer http:// " name="enlace" type="text" maxlength="60" size="60" />
                        </div>
                        <div class="Limpiador"></div>                    
                    </div>
                    <div class="SubTituloArticulo">
                    Imagen del Banner
                    </div>                    
                    <div class="DetaLoadImg">                      
                        <center>                      
                        <div class="botonInputFileModificado">
                            <input type="file" class="inputImagenOculto" id="imagen" name="imagen"/>
                            <div class="boton">Buscar Imagen</div>    
                        </div>  
                        </center>                                               
                    <div class="Limpiador"></div>
                    </div>
                    
                    <div class="SubTituloArticulo">
                    Datos de la Transferencia o Deposito
                    </div>
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
                    <div class="FormFin">
                        <input type="submit" id="Enviar" name="Enviar"  value="Registrar Banner" />
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
