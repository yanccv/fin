<?php
ini_set('error_reporting', E_ALL);
ini_set('display_error', true);
session_start();
include("../includes/funcion.php");
include("../includes/classvd.php");
include("../includes/classdb.php");
include("../includes/fmails.php");


/*
include("../class/areas.php");
include("../class/autores.php");
include("../class/carreras.php");
include("../class/editoriales.php");
include("../class/fuentes.php");
include("../class/libros.php");
include("../class/alumnos.php");
include("../class/prestamos.php");
include("../class/ejemplares.php");
include("../class/carnets.php");
include("../class/usuarios.php");
include("../class/configuraciones.php");
*/
//@include("../includes/funcion.php");
$error=1;
$va= new Validar();
$bd = new dbMysql();
$bd->dbConectar();
switch ($_POST['idform']) {
    case "ActualizarDatos":
        $tipo="A";
        $formato="inicial";
        if ($va->letras($_POST['nombre'], 3, 30, "Nombre")) {
            $errores[]=$va->error;
        }
        if ($va->letras($_POST['apellido'], 3, 30, "Apellido")) {
            $errores[]=$va->error;
        }
        if ($va->longitud($_POST['direccion'], 15, 100, "Direccion", "Caracteres")) {
            $errores[]=$va->error;
        }
        if ($va->email($_POST['correo'], 10, 70, "Email")) {
            $errores[]=$va->error;
        }
        if ($bd->Edad(FData($_POST['fnac']))<18) {
            $errores[]="Disculpe, Debes Ser mayor de Edad Para Inscribirte en la Franquicia de Participaci&oacute;n de Capitales".$bd->Edad(FData($_POST['fnac']));
        }

        if (empty($errores)) {
            $teles=$_POST['tele1']."|".$_POST['tele2']."|".$_POST['tele3'];
            $Texto="<strong>Cedula:</strong>".$_POST['cedula']."<br />".
                "<strong>Nombre:</strong>".$_POST['nombre']." ".$_POST['apellido']."<br />".
                "<strong>Direcci&oacute;n:</strong>".$_POST['direccion']."<br />".
                "<strong>Telefonos:</strong>";
            if (!empty($_POST['tele1'])) {
                $Texto.=$_POST['tele1'];
            }
            if (!empty($_POST['tele2'])) {
                $Texto.=" / ".$_POST['tele2'];
            }
            if (!empty($_POST['tele3'])) {
                $Texto.=" / ".$_POST['tele3'];
            }
            $Texto.="<strong>Direcci�n Electronica:</strong>".$_POST['correo']."<br />";
            $upd=$bd->dbActualizar("update clientes set nombre=?,apellido=?,fnac=?,direccion=?,telefonos=?,email=? where cedula=?", array($_POST['nombre'], $_POST['apellido'], FData($_POST['fnac']), $_POST['direccion'], $teles, $_POST['correo'], $_POST['cedula']));
            if ($bd->Error) {
                $msg['men']=$bd->MsgError.$bd->getSql();
            } else {
                $msg['men']=$upd."<br />".$men."<br />".Send_Mail($formato, $FConfig['correo'], $_POST['correo'], $_POST['nombre']." ".$_POST['apellido'], $Texto);
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>OBSERVACIONES</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
    break;
    case "CambiarClave":
        if ($va->alfa($_POST['claact'], 6, 15, "Clave Actual")) {
            $errores[]=$va->error;
        }
        if ($va->alfa($_POST['nuecla'], 6, 15, "Nueva Clave")) {
            $errores[]=$va->error;
        }
        if ($va->alfa($_POST['repcla'], 6, 15, "Repita Nueva Clave")) {
            $errores[]=$va->error;
        }
        if (strcmp($_POST['claact'], $_POST['nuecla'])==0) {
            $errores[]="Disculpe la Nueva Clave de Escritorio debe ser Diferente a la Clave de Escritorio Actual";
        }

        //$formato="activacion";
        if (empty($errores)) {
            $upd=$bd->dbActualizar("update clientes set cescritorio=? where cedula=? and cescritorio=?", array($_POST['nuecla'], $_SESSION['cliente']['cedula'], $_POST['claact']));
            if ($bd->Error) {
                $msg['men']=$bd->MsgError.$bd->getSql();
            } else {
                if ($bd->Affected>0) {
                    $msg['men']="Clave de Escritorio Actualizada Correctamente";
                    $error=0;
                } else {
                    $msg['men']="Disculpe Clave de Escritorio Actual Incorrecta";
                }
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>OBSERVACIONES</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
    break;
    case "CambiarClaveInvita":
        if ($va->alfa($_POST['nuecla'], 6, 15, "Nueva Clave")) {
            $errores[]=$va->error;
        }
        if ($va->alfa($_POST['repcla'], 6, 15, "Repita Nueva Clave")) {
            $errores[]=$va->error;
        }
        if (strcmp($_POST['nuecla'], $_POST['repcla'])!=0) {
            $errores[]='Las Claves deben ser iguales';
        }

        //$formato="activacion";
        if (empty($errores)) {
            $upd=$bd->dbActualizar("update clientes set cinvita=? where cedula='?'", array($_POST['nuecla'], $_SESSION['cliente']['cedula']));
            if ($bd->Error) {
                $msg['men']=$bd->MsgError.$bd->getSql();
            } else {
                if ($bd->Affected>0) {
                    $msg['men']="Clave de Invitaci&oacute;n Actualizada Correctamente";
                    $error=0;
                } else {
                    $msg['men']="Disculpe Clave de Invitaci&oacute;n Actual Incorrecta";
                }
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>OBSERVACIONES</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
    break;
    case "CCuenta":
        if ($va->seleccion($_POST['Banco'], "Banco")) {
            $errores[]=$va->error;
        }
        if ($va->numeros($_POST['cuenta'], 20, "", "Nro de Cuenta")) {
            $errores[]=$va->error;
        }
        if ($va->seleccion($_POST['tipo'], "Tipo de Cuenta")) {
            $errores[]=$va->error;
        }
        if (empty($errores)) {
            switch ($_POST['tform']) {
              case "A":
                 $pro=$bd->dbInsertar("insert into cuentas (id,banco,cuenta,tipo,cliente,estado) values(lastid('cuentas'),?,?,?,?,'A')", array($_POST['Banco'], $_POST['cuenta'], $_POST['tipo'], $_SESSION['cliente']['cedula']));
                 $UID=$bd->dbLastID("cuentas", "id");
              break;
              case "E":
                 $pro=$bd->dbActualizar("update cuentas set banco=?, cuenta=?, tipo=? where id=? and cliente=?", array($_POST['Banco'], $_POST['cuenta'], $_POST['tipo'], $_POST['id'], $_SESSION['cliente']['cedula']));
                 $UID=$_POST['id'];
              break;
              default:
                 $pro="No Se Proceso Ninguna Informacion";
              break;
           }
            if ($bd->Error) {
                $msg['men']=$bd->MsgError.$bd->getSql();
            } else {
                $ConBanco=$bd->dbConsultar("select banco from bancos where id=?", array($_POST['Banco']));
                if (!$bd->Error) {
                    if ($ConBanco->num_rows>0) {
                        $Banco=$ConBanco->fetch_array();
                    }
                }
                if ($_POST['tipo']=="A") {
                    $tipo="Ahorro";
                } else {
                    $tipo="Corriente";
                }

                $msg['fila']="<tr id='".$UID."'><td align='center'>".$UID."</td>".
                         "<td align='left'>".$Banco['banco']."</td>".
                         "<td align='center'>".$tipo."</td>".
                         "<td align='center' >".$_POST['cuenta']."</td>".
                         "<td align='center' >A</td>".
                         "<td width='25'><a id='EditarCue' href='#' rel='".$UID."' alt='Editar Cuenta'><img src='../imagenes/editar.png' border='0'/></a></td>".
                         "<td width='25'><a id='BorrarCue' href='#' rel='".$UID."' alt='Eliminar Cuenta'><img src='../imagenes/eliminar.png' border='0'/></a></td></tr>";

                $msg['men']=$pro;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>OBSERVACIONES</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
    break;

    case "CargarCuenta":
        $ConCuenta=$bd->dbConsultar("select banco,cuenta,tipo from cuentas where id=? and estado='A'", array($_POST['idcuenta']));
        if ($bd->Error) {
            $msg['men']=$bd->MsgError.$bd->getSql();
        } else {
            if ($ConCuenta->num_rows>0) {
                $Cuenta=$ConCuenta->fetch_array();
                $error=0;
                $msg['banco']=trim($Cuenta['banco']);
                $msg['cuenta']=$Cuenta['cuenta'];
                $msg['tipo']=$Cuenta['tipo'];
            } else {
                $msg['men']="Disculpe Cuenta No Encontrada";
            }
        }
    break;

    case "BorrarCuenta":
        $Act=$bd->dbActualizar("update cuentas set estado='I' where id=? and estado='A' and cliente=?", array($_POST['idcuenta'], $_SESSION['cliente']['cedula']));
        if ($bd->Error) {
            $msg['men']=$bd->MsgError.$bd->getSql();
        } else {
            if ($bd->Affected==1) {
                $error=0;
                $msg['men']="Cuenta Eliminada Correctamente";
            } else {
                $msg['men']="Disculpe Cuenta No Eliminada".$bd->getSql();
            }
        }
    break;

    case "BCuentas":
        if (($_POST['idf']=="CRecapitalizar") || ($_POST['idf']=="CRenovar") || ($_POST['idf']=="RClasificado") || ($_POST['idf']=="RBanners") || ($_POST['idf']=="Cupones")) {
            $ConCuentas=$bd->dbConsultar("select * from cuentas where cliente is null and estado='A' and banco=?", array($_POST['banco']));
        } else {
            $ConCuentas=$bd->dbConsultar("select * from cuentas where cliente=? and estado='A' and banco=?", array($_SESSION['cliente']['cedula'], $_POST['banco']));
        }
        if (!$bd->Error) {
            if ($ConCuentas->num_rows>0) {
                $error=0;
                $cadena="<option value='0'>Seleccion Cuenta</option>";
                while ($FCuentas=$ConCuentas->fetch_array()) {
                    $cadena.="<option value='".$FCuentas['id']."'>".$FCuentas['cuenta']."</option>";
                }
            } else {
                $cadena="<option value='0'>Disculpe No Hay Cuentas Cargadas en Este Banco</option>";
            }
            $msg['men']=$cadena;
        } else {
            $msg['men']=$bd->MsgError;
        }
    break;

    case "BAreas":
        $ConBanners=$bd->dbConsultar("
            select
                b.id,concat(if (ISNULL(b.posicion),'Banner Principal',concat('Despues del Articulo ',ar.titulo)),',  ', (b.cantidad-count(db.id)), ' Espacios Disponibles') posicion,b.cantidad
            from
                banners as b
                   inner join areas as a on a.id=b.idarea
                   left join articulos as ar on (ar.area=b.idarea and ar.id=b.posicion)
                   left join detabanner as db on (db.idbanner=b.id and db.estado='A')
            where
                b.idarea=?
            group
                by b.id
            having
                count(db.id)<b.cantidad
            order
                by b.idarea asc
            ", array($_POST['idarea']));
        if (!$bd->Error) {
            if ($ConBanners->num_rows>0) {
                $error=0;
                $cadena="<option value='0'>Seleccion Posici&oacute;n del Banner</option>";
                while ($FBanners=$ConBanners->fetch_array()) {
                    $cadena.="<option value='".$FBanners['id']."'>".$FBanners['posicion']."</option>";
                }
            } else {
                $cadena="<option value='0'>Disculpe No Hay Espacios Disponibles en esta seccion</option>";
                $error=0;
            }
            $msg['men']=$cadena;
        } else {
            $msg['men']=$bd->MsgError;
        }
    break;
    case "BDBanner":

        //Hallar la Conversion y el Nombre de la Moneda en el Pais del Cliente
        $ConPaisMoneda=$bd->dbConsultar("SELECT m.cambio,m.moneda from paises as p inner join monedas as m on m.id=p.monedaoficial where p.id=? limit 1", array($_SESSION['cliente']['idpais']));
        if (!$bd->Error) {
            if ($ConPaisMoneda->num_rows>0) {
                $PaisMoneda=$ConPaisMoneda->fetch_array();
            } else {
                $errores="Disculpe La Moneda Local No Ha Sido Fijada";
            }
        } else {
            $errores=$bd->MsgError;
        }
        if (empty($errores)) {
            $ConPlanes=$bd->dbConsultar("
               SELECT
                 p.id,concat(p.dias,' ".utf8_encode('D�as').", Por ',round(p.costo*?),' ',?) plan,concat('Ancho: ',b.ancho,'px y Alto: ',b.alto,'px') dimenciones,ancho,alto
             FROM
                 publicaciones as p
                   inner join banners as b on b.id=p.tipo
               where p.tipo=?;
               ", array($PaisMoneda['cambio'], $PaisMoneda['moneda'], $_POST['idbanner']));
            if (!$bd->Error) {
                if ($ConPlanes->num_rows>0) {
                    $error=0;
                    $cadena="<option value='0'>Seleccion Plan</option>";
                    while ($FPlanes=$ConPlanes->fetch_array()) {
                        $cadena.="<option value='".$FPlanes['id']."'>".$FPlanes['plan']."</option>";
                        $msg['dimenciones']=$FPlanes['dimenciones'];
                        $msg['ancho']=$FPlanes['ancho'];
                        $msg['alto']=$FPlanes['alto'];
                    }
                } else {
                    $cadena="<option value='0'>Disculpe No Hay Planes Definidos</option>";
                    $msg['dimenciones']="";
                    $msg['ancho']="xx";
                    $msg['alto']="";
                    $msg['men']=$cadena.$bd->getSql();
                    $error=0;
                }
                $msg['men']=$cadena;
            } else {
                $msg['men']=$bd->MsgError;
            }
        } else {
            $msg['men']="<option value='0'>".$errores."</option>";
        }

    break;
    case "BDPlan":  //Buscar los detalles del plan
        $ConPlan=$bd->dbConsultar("select foto from publicaciones where id=? limit 1", array($_POST['idplan']));
        if (!$bd->Error) {
            if ($ConPlan->num_rows>0) {
                $error=0;
                $Plan=$ConPlan->fetch_array();
                $msg['foto']=$Plan['foto'];
            }
        } else {
            $msg['men']=$bd->MsgError;
        }
    break;

    case "CRetiros":
        if ($va->seleccion($_POST['Cuenta'], "Cuenta")) {
            $errores[]=$va->error;
        }
        if ($va->montos($_POST['MontoRet'], "", 15, "MontRet")) {
            $errores[]=$va->error;
        }
        if ($_POST['MontoRet']>($_SESSION['cliente']['saldo']*$_POST['cambio'])) {
            $errores[]="Disculpe, El Monto Solicitado es Superior al Disponible";
        }
        if ($_POST['MontoRet']<=0) {
            $errores[]="Falta Indicar el Monto a Retirar";
        }
        if (empty($errores)) {
            $add=$bd->dbInsertar("insert into movimientos (id,cliente,cuenta,franquicia,movimiento,fecha,monto_oficial,monto_base,estado) values(lastid('movimientos'),?,?,'FCG','Retiro',curdate(),?,?,'P')", array($_SESSION['cliente']['cedula'], $_POST['Cuenta'], $_POST['MontoRet']*$_POST['cambio'], $_POST['MontoRet']));
            $UID=$bd->dbLastID("movimientos", "id");
            if ($bd->Error) {
                $msg['men']=$bd->MsgError.$bd->getSql();
            } else {
                $ConBanco=$bd->dbConsultar("select b.banco,c.cuenta from bancos as b inner join cuentas as c on c.banco=b.id where b.id=? and c.id", array($_POST['CBanco'], $_POST['Cuenta']));
                if (!$bd->Error) {
                    if ($ConBanco->num_rows>0) {
                        $Banco=$ConBanco->fetch_array();
                    }
                }

                 //$_SESSION['cliente']['saldo']=$_SESSION['cliente']['saldo']-($_POST['MontoRet']/$_POST['cambio']);
                 $Saldo=Saldo($bd, $_SESSION['cliente']['cedula']);
                $Diferido=SDiferido($bd, $_SESSION['cliente']['cedula']);
                $Disponible=$Saldo-$Diferido;
                $_SESSION['cliente']['saldo']=$Disponible;
                $_SESSION['cliente']['sdiferido']=$Diferido;
                $msg['saldo']=(double) $Disponible;
                $msg['diferido']=(double) $Diferido;
                $msg['fila']="<tr id='".$UID."'>".
                            "<td align='left'>".$Banco['banco']."</td>".
                            "<td align='center' >".$Banco['cuenta']."</td>".
                            "<td align='center' ></td>".
                            "<td align='center' >//</td>".
                            "<td align='center' >".number_format($_POST['MontoRet'], 2, ",", ".")."</td>".
                            "<td align='center' >En Proceso</td>".
                            "<td width='25'><a id='BorrarRet' href='#' rel='".$UID."' alt='Anular Retiro'><img src='../imagenes/eliminar.png' border='0'/></a></td></tr>";

                $msg['men']=$add;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>OBSERVACIONES</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
    break;
    case "BorrarRetiro":
        $Act=$bd->dbActualizar("update movimientos set estado='I' where id=? and estado='P' and cliente=?", array($_POST['idcuenta'], $_SESSION['cliente']['cedula']));
        if ($bd->Error) {
            $msg['men']=$bd->MsgError.$bd->getSql();
        } else {
            if ($bd->Affected==1) {
                $error=0;
                $Saldo=Saldo($bd, $_SESSION['cliente']['cedula']);
                $Diferido=SDiferido($bd, $_SESSION['cliente']['cedula']);
                $Disponible=$Saldo-$Diferido;
                $_SESSION['cliente']['saldo']=$Disponible;
                $_SESSION['cliente']['sdiferido']=$Diferido;
                $msg['saldo']=(double) $Disponible;
                $msg['diferido']=(double) $Diferido;

                $msg['men']="Retiro Anulado Correctamente";
            } else {
                $msg['men']="Disculpe Retiro No Anulado".$bd->getSql();
            }
        }
    break;
    case "RClasificado":
        if ($va->seleccion($_POST['categoria'], "Categoria")) {
            $errores[]=$va->error;
        }
        if ($va->seleccion($_POST['CPlan'], "Plan")) {
            $errores[]=$va->error;
        }
        if ($va->longitud($_POST['titulo'], 5, 60, "Titulo", "Caracteres")) {
            $errores[]=$va->error;
        }
        if ($va->longitud($_POST['descripcion'], 5, 1200, "Descripci&oacute;n", "Caracteres")) {
            $errores[]=$va->error;
        }
        if ($va->longitud($_POST['contacto'], 5, 150, "Direcci&oacute;n", "Caracteres")) {
            $errores[]=$va->error;
        }
        if ($va->seleccion($_POST['estado'], "Estado")) {
            $errores[]=$va->error;
        }
        if ($va->seleccion($_POST['CBanco'], "Banco")) {
            $errores[]=$va->error;
        }
        if ($va->seleccion($_POST['Cuenta'], "Cuenta")) {
            $errores[]=$va->error;
        }
        if ($va->alfa($_POST['numero'], 6, 15, "Deposito/Transferencia")) {
            $errores[]=$va->error;
        }

        $raiz="../clasificados/";

        $folder=uniqid(date("Ymdhis"))."/";
        $foto=array();
        $maxweight=512; //Peso maximo en Kb
        $maxheight=700; //Tama�o Maximo de Alto en Kb
        $maxwidth=850;  //Tama�o Maximo de Ancho en Kb

        //if (!empty($_POST['idfoto']))   $foto=$_POST['idfoto'];
        for ($i=0;$i<count($_FILES['imagen']['name']);$i++) {
            if (!empty($_FILES['imagen']['name'][$i])) {
                if ($va->file(substr($_FILES['imagen']['name'][$i], 0, strrpos($_FILES['imagen']['name'][$i], '.', -4)), 1, "", "Imagen ".($i+1))) {
                    $errores[]=$va->error;
                }
                $tipos=array("jpg","png","gif");

                $file[$i]=$_FILES['imagen']['name'][$i];
                $ext=strtolower(substr($_FILES['imagen']['name'][$i], (strrpos($_FILES['imagen']['name'][$i], '.', -4)+1)));
                switch ($ext) {
                    case "jpg":
                        $ima=@imagecreatefromjpeg($_FILES['imagen']['tmp_name'][$i]);
                        if (!$ima) {
                            $errores[]="Error Imagen Invalida, Intente con una imagen diferente";
                        }
                    break;
                    case "gif":
                        $ima=@imagecreatefromgif($_FILES['imagen']['tmp_name'][$i]);
                        if (!$ima) {
                            $errores[]="Error Imagen Invalida, Intente con una imagen diferente";
                        }

                    break;
                    case "png":
                        $ima=@imagecreatefrompng($_FILES['imagen']['tmp_name'][$i]);
                        if (!$ima) {
                            $errores[]="Error Imagen Invalida, Intente con una imagen diferente";
                        }

                    break;
                }
                unset($ima);
                $tipoext[$i]=$ext;
                if ($peso>$maxweight) {   //Maximo 5Kb
                    $errores[]="Disculpe Imagen ".($i+1)." Pesa $peso kb, El Peso Maximo Debe Ser $maxweight Kb";
                } elseif ($size[0]>$maxwidth || $size[1]>$maxheight) {    //Maximo de Ancho y Alto en px
                    $errores[]="Disculpe La Imagen ".($i+1)." es de [".$size[0]."x".$size[1]."]px, y debe ser de [$maxwidth x $maxheight]px";
                }
                $foto[$i]=$raiz.$folder.$file[$i];
            }
        }

        if (empty($errores)) {
            for ($i=0; $i<count($_FILES['imagen']['name']); $i++) {
                if (!is_dir($raiz.$folder)) {
                    mkdir($raiz.$folder, 0777);
                }
                if (!empty($_FILES['imagen']['name'][$i])) {
                    //echo $_FILES['imagen']['type'][$i];
                    //comprobamos si el archivo ha subido
                    if ($file[$i] && move_uploaded_file($_FILES['imagen']['tmp_name'][$i], $foto[$i])) {
                        sleep(1);//retrasamos la petici�n 3 segundos
                        $imagen.="Imagen $i Cargada Correctamente";//devolvemos el nombre del archivo para pintar la imagen
                        //vp_img($foto[$i],$raiz.$folder,$tipoext[$i]);
                        vp_img($foto[$i], $raiz.$folder, $_FILES['imagen']['name'][$i], $tipoext[$i]);
                    } else {
                        $imagen.="Imagen $i No Cargada";
                        $foto[$i]=null;
                    }
                    if (($i+1)==count($_FILES['imagen']['name'])) {
                        $imagen.".";
                    } else {
                        $imagen.=", ";
                    }
                    $imagenes.=$foto[$i]."|";
                }
            }

            //Hallar la Conversion y el Nombre de la Moneda en el Pais del Cliente
            $ConPaisMoneda=$bd->dbConsultar("SELECT m.cambio,m.moneda from paises as p inner join monedas as m on m.id=p.monedaoficial where p.id=? limit 1", array($_SESSION['cliente']['idpais']));
            if (!$bd->Error) {
                if ($ConPaisMoneda->num_rows>0) {
                    $PaisMoneda=$ConPaisMoneda->fetch_array();
                } else {
                    $errores="Disculpe La Moneda Local No Ha Sido Fijada";
                }
            } else {
                $errores=$bd->MsgError;
            }

            //Busqueda del Porcentaje Que El Cliente Paga a la Franquicia Por la Publicacion de la Publicidad
            $ConConfig=$bd->dbConsultar("select pppublicidad porcentaje from configuracion limit 1");
            if (!$bd->Error) {
                if ($ConConfig->num_rows>0) {
                    $Config=$ConConfig->fetch_array();
                }
            }

            //Buscar Los Datos del Plan
            $ConPlan=$bd->dbConsultar("select dias,(costo*?)*(?/100) costo,(costo *(?/100)) cbase from publicaciones where id=?", array($PaisMoneda['cambio'], $Config['porcentaje'], $Config['porcentaje'], $_POST['CPlan']));
            if (!$bd->Error) {
                if ($ConPlan->num_rows>0) {
                    $Plan=$ConPlan->fetch_array();
                }
            }

            //$imagenes=implode("|",$foto);
            $mapa=$_POST['lon']."|".$_POST['lat']."|".$_POST['zoom'];
            $add=$bd->dbInsertar("insert into movimientos (id,cliente,cuenta,referencia,franquicia,movimiento,fecha,monto_oficial,monto_base,estado) values(lastid('movimientos'),?,?,?,'CLA','Deposito',curdate(),?,?,'I')", array($_SESSION['cliente']['cedula'], $_POST['Cuenta'], $_POST['numero'], $Plan['costo'], $Plan['cbase']));
            if (!$bd->Error) {
                $add2=$bd->dbInsertar("insert into clasificados (id,categoria,cliente,movimiento,idpais,idestado,titulo,descripcion,direccion,dias,fregistro,imagenes,estado) values (lastid('clasificados'),?,?,(lastid('movimientos')-1),?,?,?,?,?,?,curdate(),?,'I')",
                array($_POST['categoria'], $_SESSION['cliente']['cedula'], $_SESSION['cliente']['idpais'], $_POST['estado'], $_POST['titulo'], $_POST['descripcion'], $_POST['contacto'], $_POST['CPlan'], $imagenes));
                if (!$bd->Error) {
                    $error=0;
                    $msg['men']="Deposito Registrado Correctamente<br />Clasificado Registrado Correctamente";
                } else {
                    $msg['men']=$bd->MsgError.$bd->getSql();
                    $bd->dbBorrar("delete from movimientos where id=?", array($idMovimiento));
                    for ($i=0; $i<count($foto); $i++) {
                        @unlink($foto[$i]);
                    }
                    @rmdir($raiz);
                }
            } else {
                $msg['men']=$bd->MsgError.$bd->getSql();
                for ($i=0; $i<count($foto); $i++) {
                    @unlink($foto[$i]);
                }
                @rmdir($raiz);
            }
        } else {
            $msg['men']="OBSERVACIONES<ul><li>".implode("<li>", $errores)."</ul>";
        }
    break;
    case "Cupones":
        if ($va->longitud($_POST['titulo'], 5, 60, "Titulo", "Caracteres")) {
            $errores[]=$va->error;
        }
        if ($va->longitud($_POST['direccion'], 5, 200, "Dirección", "Caracteres")) {
            $errores[]=$va->error;
        }
        if ($va->telefono($_POST['tele1'], 5, 12, "Teléfono 1")) {
            $errores[]=$va->error;
        }
        if ($va->telefono($_POST['tele2'], 5, 12, "Teléfono 2")) {
            $errores[]=$va->error;
        }
        if ($va->email($_POST['estado'], 10, 70, "Email")) {
            $errores[]=$va->error;
        }
        if ($va->numeros($_POST['cupones'], 1, 10, "Cantidad de Cupones")) {
            $errores[]=$va->error;
        } elseif ((int) $_POST['cupones']<=0) {
            $errores[]="Disculpe la cantidad de cupones no puede ser menor a cero (0)";
        }
        if ($va->seleccion($_POST['CBanco'], "Banco")) {
            $errores[]=$va->error;
        }
        if ($va->seleccion($_POST['Cuenta'], "Cuenta")) {
            $errores[]=$va->error;
        }
        if ($va->alfa($_POST['numero'], 6, 15, "Deposito/Transferencia")) {
            $errores[]=$va->error;
        }
        if ($va->numeros($_POST['monto'], 1, 15, "Monto Depositado")) {
            $errores[]=$va->error;
        }
        if (!isset($_FILES['imagen']['name']) || count($_FILES['imagen']['name'])==0) {
            $errores[]="Falta la Imagen de la Publicidad";
        }

        //Busqueda del Porcentaje Que El Cliente Paga a la Franquicia Por la Publicacion de la Publicidad
        $ConConfig=$bd->dbConsultar("select preciocupon,porcecupon from configuracion limit 1");
        if (!$bd->Error) {
            if ($ConConfig->num_rows>0) {
                $Config=$ConConfig->fetch_array();
            }
        }
        #if ((int) $_POST['cupones'] * $Config['preciocupon'])*0.60

        $raiz="../cupones/";

        $folder=uniqid(date("Ymdhis"))."/";
        $foto=array();
        $maxweight=450; //Peso maximo en Kb
        $maxheight=345; //Tamaño Maximo de Alto en Kb
        $maxwidth=150;  //Tamaño Maximo de Ancho en Kb

        //if (!empty($_POST['idfoto']))   $foto=$_POST['idfoto'];
        for ($i=0; $i<count($_FILES['imagen']['name']); $i++) {
            if (!empty($_FILES['imagen']['name'][$i])) {
                if ($va->file(substr($_FILES['imagen']['name'][$i], 0, strrpos($_FILES['imagen']['name'][$i], '.', -4)), 1, "", "Imagen ".($i+1))) {
                    $errores[]=$va->error;
                }
                $tipos=array("jpg","png","gif");

                $file[$i]=$_FILES['imagen']['name'][$i];
                $ext=strtolower(substr($_FILES['imagen']['name'][$i], (strrpos($_FILES['imagen']['name'][$i], '.', -4)+1)));
                switch ($ext) {
                    case "jpg":
                        $ima=@imagecreatefromjpeg($_FILES['imagen']['tmp_name'][$i]);
                        if (!$ima) {
                            $errores[]="Error Imagen Invalida, Intente con una imagen diferente";
                        }
                    break;
                    case "gif":
                        $ima=@imagecreatefromgif($_FILES['imagen']['tmp_name'][$i]);
                        if (!$ima) {
                            $errores[]="Error Imagen Invalida, Intente con una imagen diferente";
                        }

                    break;
                    case "png":
                        $ima=@imagecreatefrompng($_FILES['imagen']['tmp_name'][$i]);
                        if (!$ima) {
                            $errores[]="Error Imagen Invalida, Intente con una imagen diferente";
                        }
                    break;
                }
                unset($ima);
                $tipoext[$i]=$ext;
                if ($peso>$maxweight) {   //Maximo 5Kb
                    $errores[]="Disculpe Imagen ".($i+1)." Pesa $peso kb, El Peso Maximo Debe Ser $maxweight Kb";
                } elseif ($size[0]>$maxwidth || $size[1]>$maxheight) {    //Maximo de Ancho y Alto en px
                    $errores[]="Disculpe La Imagen ".($i+1)." es de [".$size[0]."x".$size[1]."]px, y debe ser de [$maxwidth x $maxheight]px";
                }
                $foto[$i]=$raiz.$folder.$file[$i];
            }
        }

        if (empty($errores)) {
            for ($i=0; $i<count($_FILES['imagen']['name']); $i++) {
                if (!is_dir($raiz.$folder)) {
                    mkdir($raiz.$folder, 0777);
                }
                if (!empty($_FILES['imagen']['name'][$i])) {
                    //echo $_FILES['imagen']['type'][$i];
                    //comprobamos si el archivo ha subido
                    if ($file[$i] && move_uploaded_file($_FILES['imagen']['tmp_name'][$i], $foto[$i])) {
                        sleep(1);//retrasamos la petici�n 3 segundos
                        $imagen.="Imagen $i Cargada Correctamente";//devolvemos el nombre del archivo para pintar la imagen
                        //vp_img($foto[$i],$raiz.$folder,$tipoext[$i]);
                        vp_img($foto[$i], $raiz.$folder, $_FILES['imagen']['name'][$i], $tipoext[$i]);
                    } else {
                        $imagen.="Imagen $i No Cargada";
                        $foto[$i]=null;
                    }
                    if (($i+1)==count($_FILES['imagen']['name'])) {
                        $imagen.".";
                    } else {
                        $imagen.=", ";
                    }
                    $imagenes.=$foto[$i]."|";
                }
            }

            //Hallar la Conversion y el Nombre de la Moneda en el Pais del Cliente
            $ConPaisMoneda=$bd->dbConsultar("SELECT m.cambio,m.moneda from paises as p inner join monedas as m on m.id=p.monedaoficial where p.id=? limit 1", array($_SESSION['cliente']['idpais']));
            if (!$bd->Error) {
                if ($ConPaisMoneda->num_rows>0) {
                    $PaisMoneda=$ConPaisMoneda->fetch_array();
                } else {
                    $errores="Disculpe La Moneda Local No Ha Sido Fijada";
                }
            } else {
                $errores=$bd->MsgError;
            }

            //Busqueda del Porcentaje Que El Cliente Paga a la Franquicia Por la Publicacion de la Publicidad
            $ConConfig=$bd->dbConsultar("select pppublicidad porcentaje from configuracion limit 1");
            if (!$bd->Error) {
                if ($ConConfig->num_rows>0) {
                    $Config=$ConConfig->fetch_array();
                }
            }

            //Buscar Los Datos del Plan
            $ConPlan=$bd->dbConsultar("select dias,(costo*?)*(?/100) costo,(costo *(?/100)) cbase from publicaciones where id=?", array($PaisMoneda['cambio'], $Config['porcentaje'], $Config['porcentaje'], $_POST['CPlan']));
            if (!$bd->Error) {
                if ($ConPlan->num_rows>0) {
                    $Plan=$ConPlan->fetch_array();
                }
            }

            //$imagenes=implode("|",$foto);
            $mapa=$_POST['lon']."|".$_POST['lat']."|".$_POST['zoom'];
            $add=$bd->dbInsertar("insert into movimientos (id,cliente,cuenta,referencia,franquicia,movimiento,fecha,monto_oficial,monto_base,estado) values(lastid('movimientos'),?,?,?,'CLA','Deposito',curdate(),?,?,'I')", array($_SESSION['cliente']['cedula'], $_POST['Cuenta'], $_POST['numero'], $Plan['costo'], $Plan['cbase']));
            if (!$bd->Error) {
                $add2=$bd->dbInsertar("insert into clasificados (id,categoria,cliente,movimiento,idpais,idestado,titulo,descripcion,direccion,dias,fregistro,imagenes,estado) values (lastid('clasificados'),?,?,(lastid('movimientos')-1),?,?,?,?,?,?,curdate(),?,'I')",
                array($_POST['categoria'], $_SESSION['cliente']['cedula'], $_SESSION['cliente']['idpais'], $_POST['estado'], $_POST['titulo'], $_POST['descripcion'], $_POST['contacto'], $_POST['CPlan'], $imagenes));
                if (!$bd->Error) {
                    $error=0;
                    $msg['men']="Deposito Registrado Correctamente<br />Clasificado Registrado Correctamente";
                } else {
                    $msg['men']=$bd->MsgError.$bd->getSql();
                    $bd->dbBorrar("delete from movimientos where id=?", array($idMovimiento));
                    for ($i=0; $i<count($foto); $i++) {
                        @unlink($foto[$i]);
                    }
                    @rmdir($raiz);
                }
            } else {
                $msg['men']=$bd->MsgError.$bd->getSql();
                for ($i=0; $i<count($foto); $i++) {
                    @unlink($foto[$i]);
                }
                @rmdir($raiz);
            }
        } else {
            $msg['men']="OBSERVACIONES<ul><li>".implode("<li>", $errores)."</ul>";
        }
    break;
    case "RBanners":
        if ($va->seleccion($_POST['CArea'], "Seccion")) {
            $errores[]=$va->error;
        }
        if ($va->seleccion($_POST['CBanner'], "Posicion")) {
            $errores[]=$va->error;
        }
        if ($va->seleccion($_POST['CPlanBanner'], "Plan")) {
            $errores[]=$va->error;
        }
        if ($va->longitud($_POST['titulo'], 5, 150, "Titulo", "Caracteres")) {
            $errores[]=$va->error;
        }
        if (!empty($_POST['enlace'])) {
            if ($va->longitud($_POST['enlace'], 5, 150, "Enlace", "Caracteres")) {
                $errores[]=$va->error;
            }
        }
        if ($va->seleccion($_POST['CBanco'], "Banco")) {
            $errores[]=$va->error;
        }
        if ($va->seleccion($_POST['Cuenta'], "Cuenta")) {
            $errores[]=$va->error;
        }
        if ($va->alfa($_POST['numero'], 6, 15, "Deposito/Transferencia")) {
            $errores[]=$va->error;
        }

        $raiz="../banners/";
        $folder=uniqid(date("Ymdhis"))."/";
        //$foto=array();
        $maxweight=512; //Peso maximo en Kb
        $maxheight=$_POST['alto']; //Tama�o Maximo de Alto en px
        $maxwidth=$_POST['ancho'];  //Tama�o Maximo de Ancho en px

        //if (!empty($_POST['idfoto']))   $foto=$_POST['idfoto'];
        if (!empty($_FILES['imagen']['name'])) {
            if ($va->file(substr($_FILES['imagen']['name'], 0, strrpos($_FILES['imagen']['name'], '.', -4)), 1, "", "Imagen ")) {
                $errores[]=$va->error;
            }
            $tipos=array("jpg","png","gif");
            $file=$_FILES['imagen']['name'];
            $ext=strtolower(substr($_FILES['imagen']['name'], (strrpos($_FILES['imagen']['name'], '.', -4)+1)));
            switch ($ext) {
                case "jpg":
                    $ima=@imagecreatefromjpeg($_FILES['imagen']['tmp_name']);
                    if (!$ima) {
                        $errores[]="Error Imagen Invalida, Intente con una imagen diferente";
                    } else {
                        $size=getimagesize($_FILES['imagen']['tmp_name']);
                    }
                break;
                case "gif":
                    $ima=@imagecreatefromgif($_FILES['imagen']['tmp_name']);
                    if (!$ima) {
                        $errores[]="Error Imagen Invalida, Intente con una imagen diferente";
                    } else {
                        $size=getimagesize($_FILES['imagen']['tmp_name']);
                    }
                break;
                case "png":
                    $ima=@imagecreatefrompng($_FILES['imagen']['tmp_name']);
                    if (!$ima) {
                        $errores[]="Error Imagen Invalida, Intente con una imagen diferente";
                    } else {
                        $size=getimagesize($_FILES['imagen']['tmp_name']);
                    }
                break;
            }

            unset($ima);
            $tipoext=$ext;
            $peso=round($_FILES['imagen']['size']/1024);

            if ($peso>$maxweight) {   //Maximo 5Kb
                $errores[]="Disculpe Imagen  Pesa $peso kb, El Peso Maximo Debe Ser $maxweight Kb";
            } elseif ($size[0]>$maxwidth || $size[1]>$maxheight) {    //Maximo de Ancho y Alto en px
                $errores[]="Disculpe La Imagen es de [".$size[0]."x".$size[1]."]px, y debe ser de [$maxwidth x $maxheight]px";
            }
            $foto=$raiz.$folder.$file;
        }
        if (empty($errores)) {
            if (!is_dir($raiz.$folder)) {
                mkdir($raiz.$folder, 0777);
            }
            if (!empty($_FILES['imagen']['name'])) {
                //comprobamos si el archivo ha subido
                   if ($file && move_uploaded_file($_FILES['imagen']['tmp_name'], $foto)) {
                       sleep(1);//retrasamos la petici�n 3 segundos
                       $imagen="Imagen Cargada Correctamente";
                       vp_img($foto, $raiz.$folder, $_FILES['imagen']['name'], $tipoext);
                   } else {
                       $imagen="Imagen No Cargada";
                       $foto=null;
                   }
                $imagenes=$foto;
            }

           //Hallar la Conversion y el Nombre de la Moneda en el Pais del Cliente
           $ConPaisMoneda=$bd->dbConsultar("SELECT m.cambio,m.moneda from paises as p inner join monedas as m on m.id=p.monedaoficial where p.id=? limit 1", array($_SESSION['cliente']['idpais']));
            if (!$bd->Error) {
                if ($ConPaisMoneda->num_rows>0) {
                    $PaisMoneda=$ConPaisMoneda->fetch_array();
                } else {
                    $errores[]="Disculpe La Moneda Local No Ha Sido Fijada";
                }
            } else {
                $errores[]=$bd->MsgError;
            }

            //Busqueda del Porcentaje Que El Cliente Paga a la Franquicia Por la Publicacion de la Publicidad
            $ConConfig=$bd->dbConsultar("select pppublicidad porcentaje from configuracion limit 1");
            if (!$bd->Error) {
                if ($ConConfig->num_rows>0) {
                    $Config=$ConConfig->fetch_array();
                } else {
                    $errores[]="No Hay Una Configuracion Definida";
                }
            }

            //Buscar Los Datos del Plan
            $ConPlan=$bd->dbConsultar("select dias,(costo*?)*(?/100) costo,(costo*(?/100)) cbase from publicaciones where id=?", array($PaisMoneda['cambio'], $Config['porcentaje'], $Config['porcentaje'], $_POST['CPlanBanner']));
            if (!$bd->Error) {
                if ($ConPlan->num_rows>0) {
                    $Plan=$ConPlan->fetch_array();
                } else {
                    $errores[]="Faltan Datos Del Plan";
                }
            }
            if (empty($errores)) {
                $add=$bd->dbInsertar("insert into movimientos (id,cliente,cuenta,referencia,franquicia,movimiento,fecha,monto_oficial,monto_base,estado) values(lastid('movimientos'),?,?,?,'BAN','Deposito',curdate(),?,?,'I')", array($_SESSION['cliente']['cedula'], $_POST['Cuenta'], $_POST['numero'], $Plan['costo'], $Plan['cbase']));
                if (!$bd->Error) {
                    $add2=$bd->dbInsertar("insert into detabanner (id,idbanner,idcliente,idplan,titulo,banner,enlace,estado) values (lastid('detabanner'),?,?,?,?,?,?,'I')",
                   array($_POST['CBanner'], $_SESSION['cliente']['cedula'], $_POST['CPlanBanner'], $_POST['titulo'], $imagenes, $_POST['enlace']));
                    if (!$bd->Error) {
                        $add3=$bd->dbInsertar("insert into debamovi (movimiento,detabanners) values((lastid('movimientos')-1),(lastid('detabanner')-1))", array());
                        if (!$bd->Error) {
                            $error=0;
                            $msg['men']="Deposito Registrado Correctamente<br />Banner Registrado Correctamente";
                        } else {
                            $msg['men']=$bd->MsgError;
                            $bd->dbBorrar("delete from movimientos where id=(lastid('movimientos')-1)", array());
                            $bd->dbBorrar("delete from detabanner  where id=(lastid('detabanner')-1)", array());
                            @unlink($foto[$i]);
                            @rmdir($raiz.$folder);
                        }
                    } else {
                        $msg['men']=$bd->MsgError;
                        $bd->dbBorrar("delete from movimientos where id=(lastid('movimientos')-1)", array());
                        @unlink($foto[$i]);
                        @rmdir($raiz.$folder);
                    }
                } else {
                    $msg['men']=$bd->MsgError;

                    @unlink($foto);
                    @rmdir($raiz.$folder);
                }
            } else {
                $msg['men']="OBSERVACIONES<ul><li>".implode("<li>", $errores)."</ul>";
            }
        } else {
            $msg['men']="OBSERVACIONES<ul><li>".implode("<li>", $errores)."</ul>";
        }
    break;
    case "CRecapitalizar":
        $MontoBase=(double) round($_POST['MontoDep']/$_POST['cambio'], 2);
        $MontoOficial=(double) round($_POST['MontoDep'], 2);
        if ($va->seleccion($_POST['Cuenta'], "Cuenta")) {
            $errores[]=$va->error;
        }
        if ($va->alfa($_POST['referencia'], 6, 15, "Nro de Deposito o Referencia")) {
            $errores[]=$va->error;
        }
        if ($va->montos($_POST['MontoDep'], "", 15, "MontDep")) {
            $errores[]=$va->error;
        }
        if ($_POST['MontoDep']/$_POST['cambio']>$_POST['capital']) {
            $errores[]="Disculpe el Monto de la Recapitalizacion Supera el Limite de Participaci&oacute;n de la Franquicia";
        }
        //$MontoBase=round($_POST['MontoDep']/$_POST['cambio'],2);
        if ($_POST['MontoDep']<=0) {
            $errores[]="Falta Indicar el Monto a Recapitalizar";
        }
        if (empty($errores)) {
            $add=$bd->dbInsertar("insert into movimientos (id,cliente,cuenta,referencia,franquicia,movimiento,fecha,monto_oficial,monto_base,estado) values(lastid('movimientos'),?,?,?,'REC','Deposito',?,?,?,'R')", array($_SESSION['cliente']['cedula'], $_POST['Cuenta'], $_POST['referencia'], FData($_POST['fecha']), $MontoOficial, $MontoBase));
            $UID=$bd->dbLastID("movimientos", "id");
            if ($bd->Error) {
                $msg['men']=$bd->MsgError.$bd->getSql();
            } else {
                $ConBanco=$bd->dbConsultar("select b.banco,c.cuenta from bancos as b inner join cuentas as c on c.banco=b.id where b.id=? and c.id", array($_POST['CBanco'], $_POST['Cuenta']));
                if (!$bd->Error) {
                    if ($ConBanco->num_rows>0) {
                        $Banco=$ConBanco->fetch_array();
                    }
                }

                $msg['fila']="<tr id='".$UID."'>".
                            "<td align='left'>".$Banco['banco']."</td>".
                            "<td align='center' >".$Banco['cuenta']."</td>".
                            "<td align='center' ></td>".
                            "<td align='center' >//</td>".
                            "<td align='center' >".number_format($_POST['MontoDep'], 2, ",", ".")."</td>".
                            "<td align='center' >En Proceso</td>".
                            "<td width='25'><a id='BorrarRet' href='#' rel='".$UID."' alt='Anular Retiro'><img src='../imagenes/eliminar.png' border='0'/></a></td></tr>";

                $msg['men']=$add;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>OBSERVACIONES</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
    break;
    case "BorrarRecapi":
        $Act=$bd->dbActualizar("update movimientos set estado='I' where id=? and estado='R' and cliente=?", array($_POST['idcuenta'], $_SESSION['cliente']['cedula']));
        if ($bd->Error) {
            $msg['men']=$bd->MsgError.$bd->getSql();
        } else {
            if ($bd->Affected==1) {
                $error=0;
                $msg['men']="Deposito Anulado Correctamente";
            } else {
                $msg['men']="Disculpe Desposito No Anulado".$bd->getSql();
            }
        }
    break;
    case "CRenovar":
        $MontoBase=(double) round($_POST['MontoDep']/$_POST['cambio'], 2);
        $MontoOficial=(double) round($_POST['MontoDep'], 2);
        if ($va->seleccion($_POST['Cuenta'], "Cuenta")) {
            $errores[]=$va->error;
        }
        if ($va->alfa($_POST['referencia'], 6, 15, "Nro de Deposito o Referencia")) {
            $errores[]=$va->error;
        }
        if ($va->montos($_POST['MontoDep'], "", 15, "MontDep")) {
            $errores[]=$va->error;
        }
        if ($MontoBase<$_POST['minimo']) {
            $errores[]="Disculpe el Monto Minimo de Renovacion es ".$_POST['minimo'];
        }
        if ($_POST['MontoDep']/$_POST['cambio']>$_POST['capital']) {
            $errores[]="Disculpe el Monto de la Recapitalizacion Supera el Limite de Participaci&oacute;n de la Franquicia";
        }
        if ($_POST['MontoDep']<=0) {
            $errores[]="Falta Indicar el Monto a Recapitalizar";
        }
        if (empty($errores)) {
            $add=$bd->dbInsertar("insert into movimientos (id,cliente,cuenta,referencia,franquicia,movimiento,fecha,monto_oficial,monto_base,estado) values(lastid('movimientos'),?,?,?,'REN','Deposito',?,?,?,'V')", array($_SESSION['cliente']['cedula'], $_POST['Cuenta'], $_POST['referencia'], FData($_POST['fecha']), $MontoOficial, $MontoBase));
            $UID=$bd->dbLastID("movimientos", "id");
            if ($bd->Error) {
                $msg['men']=$bd->MsgError.$bd->getSql();
            } else {
                $msg['men']=$add;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>OBSERVACIONES</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
    break;
    case "BorrarRenova":
        $Act=$bd->dbActualizar("update movimientos set estado='I' where id=? and estado='V' and cliente=?", array($_POST['idcuenta'], $_SESSION['cliente']['cedula']));
        if ($bd->Error) {
            $msg['men']=$bd->MsgError.$bd->getSql();
        } else {
            if ($bd->Affected==1) {
                $error=0;
                $msg['men']="Deposito Anulado Correctamente";
            } else {
                $msg['men']="Disculpe Desposito No Anulado".$bd->getSql();
            }
        }
    break;
    case "FPMAfiliarse":
        if ($va->seleccion($_POST['terminos'], "Terminos")) {
            $errores[]="Debes Aceptar los Terminos para Continuar";
        }
        if (empty($errores)) {
            $add=$bd->dbActualizar("update clientes set fpm=1 where cedula=? and estado='A'", array($_SESSION['cliente']['cedula']));
            if ($bd->Error) {
                $msg['men']=$bd->MsgError;
            } else {
                session_start();
                $_SESSION['cliente']['fpm']=1;
                $msg['men']=$add;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>OBSERVACIONES</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
    break;


    default:
        $msg['men']="Seccion No Encontrada [".$_POST['idform']."]";
    break;
}
$msg['error']=$error;
echo json_encode($msg);
