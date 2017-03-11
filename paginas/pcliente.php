<?php
session_start();
//print_r($_POST);
//print_r($_FILES);
/*
if ($_POST['idform']!="login"){
      include("check.php");
   checkar($_SERVER['SCRIPT_NAME'],$_SESSION['usuario']['nivel'],$_SERVER['HTTP_REFERER']);
}
*/
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
    case "RFPInicial":
        $tipo="A";
        $formato="inicial";
        /*  Busqueda de Configuracion */
        $ConConfig=$bd->dbConsultar("select c.tiempoactivo dias,c.minimoinicial minimo,c.mmaximo maximo,c.minimoregistro mregistro,c.minimorenova mrenova,m.moneda,c.correo,c.fechaesc,c.cuponesc  from configuracion as c inner join monedas as m on m.id=c.monedabase where c.id=1");
        if (!$bd->Error) {
            $FConfig=$ConConfig->fetch_array();
        }
        $minimo=$FConfig['minimo'];
        //Busqueda a ver Si Esta Registrado
        $ConCliente=$bd->dbConsultar("select cedula,datediff(curdate(),fupdate) diferencia,asociador,estado from clientes where cedula=?", array($_POST['cedula']));
        if ($bd->Error) {
            $errores[]=$bd->MsgError;
        } else {
            //Busqueda a Ver si ya esta Registrado
            if ($ConCliente->num_rows>0) {
                $tipo="E";
                $Cliente=$ConCliente->fetch_array();
                // Buscar a ver si Esta Inactivado
                if (strcmp($Cliente['estado'], "I")==0 && !empty($Cliente['asociador'])) {
                    //Si Esta Inactivo Xq Se le Vencio Cuando Alguna Vez estuvo activo
                    $ConClienteActivo=$bd->dbConsultar("select id from franquiciados where cliente=?", array($_POST['cedula']));
                    if (!$bd->Error) {
                        if ($ConClienteActivo->num_rows>0) {
                            //El Cliente Se Activo Pero Dejo Vencer Su Participacion
                            $men="Disculpe, Hemos detectado que ya has formado parte de la franquicia de participaci&oacute;n de capitales, pero no renovaste a tiempo, por ello tu monto minimo de apertura es de ".$FConfig['mrenova']." ".$FConfig['moneda']."  y dispones de ".$FConfig['dias']." dias para activar la participaci&oacute;n";
                            $minimo=$FConfig['mrenova'];
                            $formato="renova";
                        } else {
                            //El Cliente No Se A Activado Nunca
                            //Revisar Si Ya Se Agoto el Tiempo Habilitado Para la Activacion del Cliente
                            if ($Cliente['diferencia']>$FConfig['dias']) {
                                //Se le agoto el tiempo para Activarse
                                $men="Disculpe, Hemos detectado que ya te habias registrado, pero no te activaste en el tiempo habilitado para hacerlo, por ello tu monto minimo para iniciar la participacion es de ".$FConfig['mregistro']." ".$FConfig['moneda']." y dispones de ".$FConfig['dias']." dias para activar la participaci&oacute;n";
                                $minimo=$FConfig['mregistro'];
                                $formato="registro";
                            } else {
                                //Aun Dispone de Tiempo Para Activarse y No Se Deben Actualizar los Datos
                                $errores[]="Disculpe, este participante ya esta registrado pero no se ha activado, dispone de ".($FConfig['dias']-$Cliente['diferencia'])." dias para <strong>Activar su Participaci&oacute;n</strong>";
                            }
                        }
                    }
                } elseif (strcmp($Cliente['estado'], "A")==0) {
                    //El Cliente Ya Esta Activo
                    $errores[]="Disculpe, registro no procesado, este participante ya forma parte de la Franquicia";
                }
            }
        }
        if (strcmp($_POST['asociador'], $_POST['cedula'])==0) {
            $errores[]="Disculpe, No te puedes Autoinvitar";
        }

        if (empty($errores)) {
            if ($va->alfa($_POST['cedula'], 6, 20, "Cedula  o ID")) {
                $errores[]=$va->error;
            }
            if ($va->letras($_POST['nombre'], 3, 30, "Nombre")) {
                $errores[]=$va->error;
            }
            if ($va->letras($_POST['apellido'], 3, 30, "Apellido")) {
                $errores[]=$va->error;
            }
            if ($va->fecha($_POST['fnac'], "", "", "Fec de Nacimiento")) {
                $errores[]=$va->error;
            }

            if ($va->longitud($_POST['direccion'], 15, 100, "Direccion", "Caracteres")) {
                $errores[]=$va->error;
            }
            if (!empty($_POST['tele1'])) {
                if ($va->telefono($_POST['tele1'], 12, 12, "Telefono 1")) {
                    $errores[]=$va->error;
                }
            }
            if (!empty($_POST['tele2'])) {
                if ($va->telefono($_POST['tele2'], 12, 12, "Telefono 2")) {
                    $errores[]=$va->error;
                }
            }
            if (!empty($_POST['tele3'])) {
                if ($va->telefono($_POST['tele2'], 12, 12, "Telefono 3")) {
                    $errores[]=$va->error;
                }
            }

            if ($va->seleccion($_POST['pais'], "Pais")) {
                $errores[]=$va->error;
            }

            if ($va->email($_POST['correo'], 10, 70, "Email")) {
                $errores[]=$va->error;
            }
            //echo $bd->Edad(FData($_POST['fnac']));
            if ($bd->Edad(FData($_POST['fnac']))<18) {
                $errores[]="Disculpe, Debes Ser mayor de Edad Para Inscribirte en la Franquicia de Participaci&oacute;n de Capitales";
            }
        }
        //$tipo
        if (empty($errores)) {
            $teles=$_POST['tele1']."|".$_POST['tele2']."|".$_POST['tele3'];
            $cinvita=$bd->GenAlfa(12);
            $cconexion=$bd->GenAlfa(8);
            //Busqueda de las Cuentas Del Pais en el Que se Registro el participante para ser adjuntajas en el Correo
            $ConCuentas=$bd->dbConsultar("select b.banco Banco,c.titular Titular,c.cuenta Cuenta,c.tipo Tipo from cuentas as c inner join bancos as b on b.id=c.banco where  b.pais=? and cliente is null", array($_POST['pais']));
            if (!$bd->Error) {
                $cuentas=array(
                    'title'=>'CUENTAS DISPONNIBLES PARA REALIZAR DEPOSITOS O TRANSFERENCIAS',
                    'details'=>array()
                );
                #$Cuentas="<br /><br /><center>CUENTAS DISPONNIBLES PARA REALIZAR DEPOSITOS O TRANSFERENCIAS</center><br />";
                while ($FCuentas=$ConCuentas->fetch_assoc()) {
                    array_push($cuentas['details'], $FCuentas);
                    /*
                    if ($FCuentas['tipo']=="A") {
                        $tipoCuenta="Cuenta de Ahorro";
                    } elseif ($FCuentas['tipo']=="C") {
                        $tipoCuenta="Cuenta Corriente";
                    }
                    $Cuentas.="<strong>".$FCuentas['banco']."</strong> ".$tipoCuenta." " .$FCuentas['cuenta']."<br />";
                    */
                }
            } else {
                $errores[]=$bd->MsgError;
            }
            if (empty($errores)) {
                /*
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
                $Texto.="<strong>Direcci�n Electronica:</strong>".$_POST['correo']."<br />".
                    "<strong>Clave de Invitaci�n:</strong>".$cinvita."<br />".
                    "<strong>Clave de Conexi�n:</strong>".$cconexion."<br />";
                $Texto.=$Cuentas;
                */

                $ConCuentas=$bd->dbConsultar("select cedula,concat(apellido,', ',nombre) nombre from clientes where cedula=?", array("'".$_POST['asociador']."'"));
                if (!$bd->Error) {
                    $Asociador=$ConCuentas->fetch_object();
                    $Texto=carta_iniciacion(array(
                        'nombre'=>$_POST['apellido'].', '.$_POST['nombre'],
                        'cinvita'=>$cinvita,
                        'cconexion'=>$cconexion,
                        'asociador'=>$Asociador->nombre,
                        'fecha'=>FUser(substr($FConfig['fechaesc'], 0, 10)),
                        'hora'=>trim(substr($FConfig['fechaesc'], 11)),
                        'cupon'=>$FConfig['cuponesc'],
                        'cuentas'=>$cuentas
                    ));
                    switch ($tipo) {
                        case "A":
                            $add=$bd->dbInsertar("insert into clientes (cedula,nombre,apellido,fnac,direccion,telefonos,email,asociador,cinvita,cconexion,fregistro,fupdate,minimoap,pais,fpc,fpm,estado) values(?,?,?,?,?,?,?,?,?,?,curdate(),curdate(),?,?,1,0,'I')", array($_POST['cedula'], $_POST['nombre'], $_POST['apellido'], FData($_POST['fnac']), $_POST['direccion'], $teles, $_POST['correo'], $_POST['asociador'], $cinvita, $cconexion, $minimo, $_POST['pais']));
                            if ($bd->Error) {
                                $msg['men']=$bd->MsgError.$bd->getSql();
                            } else {
                                $msg['men']=$add ."<br />" . Send_Mail($formato, $FConfig['correo'], $_POST['correo'], $_POST['nombre']." ".$_POST['apellido'], $Texto);
                                $error=0;
                            }
                            break;
                        case "E":
                            $upd=$bd->dbActualizar("update clientes set nombre=?,apellido=?,fnac=?,direccion=?,telefonos=?,email=?,asociador=?,cinvita=?,cconexion=?,fupdate=curdate(),minimoap=?,pais=?,fpc=1,fpm=0,estado='I' where cedula=?", array($_POST['nombre'], $_POST['apellido'], FData($_POST['fnac']), $_POST['direccion'], $teles, $_POST['correo'], "'".$_POST['asociador']."'", $cinvita, $cconexion, $minimo, $_POST['pais'], "'".$_POST['cedula']."'"));
                            if ($bd->Error) {
                                $msg['men']=$bd->MsgError.$bd->getSql();
                            } else {
                                $msg['men']=$upd."<br />".$men."<br />".Send_Mail($formato, $FConfig['correo'], $_POST['correo'], $_POST['nombre']." ".$_POST['apellido'], $Texto);
                                $error=0;
                            }
                            break;
                        default:
                            $msg['men']="Registro No Agregado, Intente Nuevamente";
                            break;
                    }
                } else {
                    $msg['men']=$bd->MsgError;
                }
            } else {
                $msg['titulo']="Errores Por Corregir Antes de Continuar";
                $msg['men']="<center>OBSERVACIONES</center><ul><li>".implode("<li>", $errores)."</ul>";
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>OBSERVACIONES</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        break;
    case "RFPCActivar":
        /*Busco a Ver si el deposito ya se encuentra registrado */
        $ConDepositos=$bd->dbConsultar("select * from movimientos where cuenta=? and referencia=? and movimiento='Deposito' and monto_oficial=?", array($_POST['Cuenta'], $_POST['nroref'], $_POST['MontoDep']));
        if (!$bd->Error) {
            if ($ConDepositos->num_rows>0) {
                $errores[]="Disculpe este Nro de Deposito o Referencia de Transaccion Ya Esta Registrada";
            }
        } else {
            $errores[]=$bd->MsgError;
        }

        /*Busqueda de los Datos de Configuracion */
        $ConConfig=$bd->dbConsultar("select c.tiempoactivo dias,c.minimoinicial minimo,c.mmaximo maximo,c.minimoregistro mregistro,c.minimorenova mrenova,m.moneda,c.correo  from configuracion as c inner join monedas as m on m.id=c.monedabase where c.id=1 limit 1");
        if (!$bd->Error) {
            $FConfig=$ConConfig->fetch_array();
           //print_r($FConfig);
        } else {
            $errores[]=$bd->MsgError;
        }

        /*Busqueda del Monto Ya Depositado Registrado, Que No Se Ha Activado*/
        $ConMontoDep=$bd->dbConsultar("select sum(monto_oficial) monto,sum(monto_base) montobase from movimientos where cliente=? and franquicia='FPC' and estado='N' and movimiento='Deposito' group by cliente", array($_POST['cedula']));
        if (!$bd->Error) {
            $Depositado=$ConMontoDep->fetch_array();
        } else {
            $errores[]=$bd->MsgError;
        }

        /*Busqueda de la Informaci�n del Cliente*/
        $ConCliente=$bd->dbConsultar("select c.cedula,concat(c.nombre,' ',c.apellido) nombre,c.minimoap,c.email,m.moneda,m.cambio from clientes as c inner join paises as p on c.pais=p.id inner join monedas as m on p.monedaoficial=m.id where cedula=? limit 1", array($_POST['cedula']));
        if (!$bd->Error) {
            $FCliente=$ConCliente->fetch_array();
        }
        //echo $FConfig['maximo']."----".$FCliente['cambio'];
        $MonMinOficial=$FCliente['minimoap']*$FCliente['cambio'];
        $MonMinaDep=$MonMinOficial-$Depositado['monto'];
        $MonMaxOficial=$FConfig['maximo']*$FCliente['cambio'];
        $MonMaxaDep=$MonMaxOficial-$Depositado['monto'];
        if ($_POST['MontoDep']<$MonMinaDep) {
            $errores[]="El Monto Minimo de Deposito es de ".$MonMinaDep." ".$FCliente['moneda'];
        }
        if ($_POST['MontoDep']>$MonMaxaDep) {
            $errores[]="El Monto Maximo de Deposito es de ".$MonMaxaDep." ".$FCliente['moneda'];
        }

        //Valido la informacion Enviada
        if (empty($errores)) {
            if ($va->numeros($_POST['cedula'], 6, 20, "Cedula o ID")) {
                $errores[]=$va->error;
            }
            if ($va->seleccion($_POST['Cuenta'], "Cuenta")) {
                $errores[]=$va->error;
            }
            if ($va->alfa($_POST['nroref'], 6, 15, "Deposito o Referencia")) {
                $errores[]=$va->error;
            }
            if ($va->montos($_POST['MontoDep'], 3, 15, "Monto")) {
                $errores[]=$va->error;
            }
            if ($va->fecha($_POST['fecha'], "", "", "Fecha")) {
                $errores[]=$va->error;
            }
            if (strcmp($_POST['Enviar'], "Registrar")!=0) {
                $errores[]="Debes Aceptar los terminos y condiciones para poder registrarte";
            }
        }

        $formato="activacion";
        if (empty($errores)) {
            /* Busqueda del banco */
           $ConBanco=$bd->dbConsultar("select b.banco,c.cuenta from cuentas as c inner join bancos as b on (b.id=c.banco) where c.id=? limit 1", array($_POST['Cuenta']));
            if (!$bd->Error) {
                if ($ConBanco->num_rows<=0) {
                    $errores[]="Disculpe Banco Seleccionado No Registrado";
                } else {
                    $FBanco=$ConBanco->fetch_array();
                }
            }

            /*Creacion del Cuerpo del Correo Que se Enviara */
            $Texto="<strong>Banco:</strong>".$FBanco['banco']."<br />".
                  "<strong>Cuenta:</strong>".$FBanco['cuenta']."<br />".
                  "<strong>Nro de Deposito o Referencia:</strong>".$_POST['nroref']."<br />".
                  "<strong>Cuenta:</strong>".$_POST['fecha']."<br />".
                  "<strong>Monto:</strong>".$_POST['MontoDep'].$FCliente['moneda']."<br /><br />".

            $add=$bd->dbInsertar("insert into movimientos (id,cliente,cuenta,referencia,franquicia,movimiento,fecha,monto_oficial,monto_base,estado) values(lastid('movimientos'),?,?,?,'FPC','Deposito',?,?,?,'N')", array($_POST['cedula'], $_POST['Cuenta'], $_POST['nroref'], FData($_POST['fecha']), $_POST['MontoDep'], $_POST['MonBase']));
            if ($bd->Error) {
                $msg['men']=$bd->MsgError.$bd->getSql();
            } else {
                $msg['men']=$add ."<br />" . Send_Mail($formato, $FConfig['correo'], $FCliente['email'], $FCliente['nombre'], $Texto);
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>OBSERVACIONES</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        break;
    case "BCuentas":
        $ConCuentas=$bd->dbConsultar("select * from cuentas where cliente is null and banco=?", array($_POST['banco']));
        if (!$bd->Error) {
            if ($ConCuentas->num_rows>0) {
                $error=0;
                $cadena="<option value='0'>Seleccion Cuenta</option>";
                while ($FCuentas=$ConCuentas->fetch_array()) {
                    $cadena.="<option value='".$FCuentas['id']."'>".$FCuentas['cuenta']."</option>";
                }
            } else {
                $cadena="<option value=0>Disculpe No Hay Cuentas Cargadas en Este Banco</option>";
            }
            $msg['men']=$cadena;
        } else {
            $msg['men']=$bd->MsgError;
        }
    break;

    default:
        $msg['men']="Seccion No Encontrada [".$_POST['idform']."]";
        break;
}
$msg['error']=$error;
echo json_encode($msg);
