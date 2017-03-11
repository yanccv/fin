<?php
session_start();
error_reporting('E_ALL');
ini_set('display_error', 1);
/*
if ($_POST['idform']!="login"){
      include("check.php");
   checkar($_SERVER['SCRIPT_NAME'],$_SESSION['usuario']['nivel'],$_SERVER['HTTP_REFERER']);
}
*/
//    include("../includes/funciones.php");

include("../includes/classdb.php");
include("../includes/classvd.php");
include("../includes/funcion.php");
include("../includes/fmails.php");
include("../includes/mails.php");
//@include("../includes/funcion.php");
$error=1;
$va = new Validar();
$mail = new Mails();
switch ($_POST['idform']) {
    case "areas":
        $bd = new dbMysql();
        $bd->dbConectar();
        if ($va->letras($_POST['area'], 3, 50, "Area")) {
            $errores[]=$va->error;
        }
        if ($va->seleccion($_POST['submenu'], "Sub Menu")) {
            $errores[]=$va->error;
        }
        if (empty($errores)) {
            switch ($_POST['tipoform']) {
                case "N":
                    //$bd->dbActualizar("update articulos set orden=orden+1 where orden>?",array($_POST['orden']));
                    $res=$bd->dbInsertar(
                        "insert into areas (id,area,msubmenu,usuario) values(lastid('areas'),?,?,?)",
                        array($_POST['area'], $_POST['submenu'], $_SESSION['usuario']['login'])
                    );
                    break;
                case "E":
                    $res=$bd->dbActualizar(
                        "update areas set area=?, msubmenu=?, usuario=? where id=?",
                        array($_POST['area'], $_POST['submenu'], $_SESSION['usuario']['login'], $_POST['id'])
                    );
                    break;
                default:
                    break;
            }
            if ($bd->Error) {
                $msg['men']=$bd->MsgError;
                $error=$bd->Error;
            } else {
                $msg['men']=$res;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "articulos":
        $bd = new dbMysql();
        $bd->dbConectar();
        if ($va->seleccion($_POST['area'], "Area")) {
            $errores[]=$va->error;
        }
        if ($va->alfa($_POST['tmenu'], 5, 25, "Titulo del SubMenu")) {
            $errores[]=$va->error;
        }
        if ($va->longitud($_POST['titulo'], 10, 150, "Titulo", "Caracteres")) {
            $errores[]=$va->error;
        }
        if ($va->longitud($_POST['contenido'], 15, "", "Contenido", "Caracteres")) {
            $errores[]=$va->error;
        }
        //print_r($errores);
        if (empty($errores)) {
            switch ($_POST['tipoform']) {
                case "N":
                    $bd->dbActualizar("update articulos set orden=orden+1 where orden>?", array($_POST['orden']));
                    $res=$bd->dbInsertar(
                        "insert into articulos (id,area,usuario,tmenu,titulo,contenido,fcreacion,fmodificacion,".
                        "orden,estado) values(lastid('articulos'),?,?,?,?,'".$bd->dbEscape($_POST['contenido'])."',
                        curdate(),curdate(),?,'A')",
                        array($_POST['area'], $_SESSION['usuario']['login'], $_POST['tmenu'], $_POST['titulo'],
                        $_POST['area'], ($_POST['orden']+1))
                    );
                    break;
                case "E":
                    if ($_POST['aorden']>$_POST['orden']) {
                        $bd->dbActualizar(
                            "update articulos set orden=orden+1 where orden<? and orden>? and area=?",
                            array($_POST['aorden'], $_POST['orden'], $_POST['area'])
                        );
                        $_POST['orden']++;
                    } elseif ($_POST['aorden']<$_POST['orden']) {
                        $bd->dbActualizar(
                            "update articulos set orden=orden-1 where orden>? and orden<=? and area=?",
                            array($_POST['aorden'], $_POST['orden'], $_POST['area'])
                        );
                    }
                    $res=$bd->dbActualizar(
                        "update articulos set tmenu=?, titulo=?, contenido='".$bd->dbEscape($_POST['contenido'])."',
                        fmodificacion=curdate(),orden=? where area=? and id=?",
                        array($_POST['tmenu'], $_POST['titulo'], $_POST['orden'], $_POST['area'], $_POST['id'])
                    );
                    break;
                default:
                    break;
            }
            if ($bd->Error) {
                $msg['men']=$bd->MsgError;
                $error=$bd->Error;
            } else {
                $msg['men']=$res;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "bannareas":
        $bd = new dbMysql();
        $bd->dbConectar();
        $Area=(int) $_POST['id'];

        //Validaciones de la Imagen
        $raiz="../files/$Area/Banner/";
        if (!is_dir($raiz)) {
            @mkdir($raiz, 0757, true);
        }
        $tipos=array("jpg","png","gif");
        $k=1;
        for ($i=0; $i<count($_FILES['img']['name']); $i++) {
            $file=$_FILES['img']['name'][$i];
            if (!empty($file)) {
                //$va->letras()
                $nomfile=substr($_FILES['img']['name'][$i], 0, strrpos($_FILES['img']['name'][$i], '.', -4));
                if ($va->file($nomfile, 1, "", "")) {
                    $errores[]="Disculpe, El Nombre del Archivo Posee Caracteres No Validdos";
                }
                $ext=strtolower(substr($_FILES['img']['name'][$i], (strrpos($_FILES['img']['name'][$i], '.', -4)+1)));
                if (!in_array($ext, $tipos)) {
                    $errores[]="Formato de Imagen Invalido, Solo (jpg,png,gif)";
                } else {
                    $peso=round($_FILES['img']['size'][$i]/1024, 2);
                    $size=getimagesize($_FILES['img']['tmp_name'][$i]);
                    if ($peso>100) {   //Maximo 100Kb
                        $errores[]="Disculpe Imagen Muy Pesada, Maximo 50Kb";
                    } elseif ($size[0]!=900 || $size[1]!=250) {    //Maximo de Ancho y Alto de 100px
                        $errores[]="Disculpe Imagen No Cumple con las Dimensiones Correctas [900x250]px";
                    } else {
                        $foto[$k]=$raiz.$file;
                        $tmp[$k]=$_FILES['img']['tmp_name'][$i];
                        $k++;
                    }
                }
            }
        }

        if (empty($errores)) {
            $error=0;
            for ($i=0; $i<count($_POST['oldimg']); $i++) {
                $ruta.=":".$_POST['oldimg'][$i];
            }
            for ($i=1; $i<$k; $i++) {
                if (move_uploaded_file($tmp[$i], $foto[$i])) {
                    sleep(1);//retrasamos la petici�n 3 segundos
                    $imagen.="<center>Archivo $i Cargado</center><br />";
                    //devolvemos el nombre del archivo para pintar la imagen
                    $ruta.=":".$foto[$i];
                } else {
                    $imagen.="<center>Archivo $i No Cargado</center><br />";
                }
            }
            $res=$bd->dbActualizar("update areas set banners=? where id=?", array($ruta, $Area));
            if ($bd->Error) {
                $msg['men']=$bd->MsgError;
                $error=$bd->Error;
            } else {
                $msg['men']=$imagen."<br />".$res;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }


        $bd->dbDesconectar();
        break;
    //DEBanners
    case "categorias":
        $bd = new dbMysql();
        $bd->dbConectar();
        if ($va->letras($_POST['categoria'], 3, 50, "Categoria")) {
            $errores[]=$va->error;
        }
        if (empty($errores)) {
            switch ($_POST['tipoform']) {
                case "N":
                    $res=$bd->dbInsertar(
                        "insert into categorias (id,categoria) values(lastid('categorias'),?)",
                        array($_POST['categoria'])
                    );
                    break;
                case "E":
                    $res=$bd->dbActualizar(
                        "update categorias set categoria=? where id=?",
                        array($_POST['categoria'], (int) $_POST['id'])
                    );
                    break;
                default:
                    break;
            }
            if ($bd->Error) {
                $msg['men']=$bd->MsgError;
                $error=$bd->Error;
            } else {
                $msg['men']=$res;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "bancos":
        $bd = new dbMysql();
        $bd->dbConectar();
        if ($va->letras($_POST['banco'], 3, 50, "Banco")) {
            $errores[]=$va->error;
        }
        if ($_POST['pais']==0) {
            $_POST['pais']=null;
        }
        /*
        if ($va->seleccion($_POST['pais'], "Pais")) {
            $errores[]=$va->error;
        }
        */
        if (empty($errores)) {
            switch ($_POST['tipoform']) {
                case "N":
                    $res=$bd->dbInsertar(
                        "insert into bancos (id,banco,pais,estado) values(lastid('bancos'),?,?,'A')",
                        array($_POST['banco'], $_POST['pais'])
                    );
                    break;
                case "E":
                    $res=$bd->dbActualizar(
                        "update bancos set banco=?,pais=? where id=?",
                        array($_POST['banco'], $_POST['pais'], (int) $_POST['id'])
                    );
                    break;
                default:
                    break;
            }
            if ($bd->Error) {
                $msg['men']=$bd->MsgError;
                $error=$bd->Error;
            } else {
                $msg['men']=$res;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "Cuentas":
        $bd = new dbMysql();
        $bd->dbConectar();
        if ($va->seleccion($_POST['banco'], "Banco")) {
            $errores[]=$va->error;
        }
        if ($va->seleccion($_POST['tipo'], "tipo")) {
            $errores[]=$va->error;
        }
        if ($_POST['banco']!=31) {
            if ($va->numeros($_POST['cuenta'], 20, 20, "Cuenta")) {
                $errores[]=$va->error;
            }
        } else {
            if ($va->email($_POST['cuenta'], 10, "", "Cuenta")) {
                $errores[]=$va->error;
            }
        }
        if (empty($errores)) {
            switch ($_POST['tipoform']) {
                case "N":
                    $res=$bd->dbInsertar(
                        "insert into cuentas (id,banco,cuenta,titular,tipo,estado) values(lastid('cuentas'),?,?,?,?,'A')",
                        array($_POST['banco'], $_POST['cuenta'], $_POST['titular'], $_POST['tipo'])
                    );
                    break;
                case "E":
                    $res=$bd->dbActualizar(
                        "update cuentas set banco=?,cuenta='?',titular=?,tipo=? where id=?",
                        array($_POST['banco'], $_POST['cuenta'], $_POST['titular'], $_POST['tipo'], (int) $_POST['id'])
                    );
                    break;
                default:
                    $bd->Error=true;
                    $bd->MsgError="Disculpe Acceso Invalido";
                    break;
            }
            if ($bd->Error) {
                $msg['men']=$bd->MsgError;
                $error=$bd->Error;
            } else {
                $msg['men']=$res;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "Baremos":
        $bd = new dbMysql();
        $bd->dbConectar();
        if (count($_POST['id'])<=0 && count($_POST['idn'])<=0) {
            $errores[]="Disculpe Debe Ingresar Los Parametros de Los Baremos";
        }
        if (empty($errores)) {
            $bd->dbBorrar("truncate baremos", array());
            $bd->AutoCommit(false);

            for ($i=0; $i<count($_POST['id']); $i++) {
                $bd->dbInsertar(
                    "insert into baremos (id,franquicia,monto,porcentaje,usuario) values($i,'FPC',?,?,?)",
                    array(
                        $_POST['monto'][$_POST['id'][$i]],
                        $_POST['porce'][$_POST['id'][$i]],
                        $_SESSION['usuario']['login']
                    )
                );
            }
            $i++;
            for ($j=0; $j<count($_POST['idn']); $j++) {
                $bd->dbInsertar(
                    "insert into baremos (id,franquicia,monto,porcentaje,usuario) values($i,'FPC',?,?,?)",
                    array($_POST['monton'][$j], $_POST['porcen'][$j], $_SESSION['usuario']['login'])
                );
                $i++;
            }
            if ($bd->Error) {
                $msg['men']=$bd->MsgError.$bd->getSql();
                $error=$bd->Error;
                $bd->RollBack();
            } else {
                $msg['men']="Baremos Actualizados";
                $error=0;
                $bd->Commit();
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "Monedas":
        $bd = new dbMysql();
        $bd->dbConectar();

        if ($va->letras($_POST['moneda'], 3, 50, "Moneda")) {
            $errores[]=$va->error;
        }
        if ($va->montos($_POST['cambio'], 1, "", "Tasa de Cambio")) {
            $errores[]=$va->error;
        }
        if (empty($errores)) {
            switch ($_POST['tipoform']) {
                case "N":
                    $res=$bd->dbInsertar(
                        "insert into monedas (id,moneda,cambio,monedabase,estado) values(lastid('monedas'),?,?,?,'A')",
                        array($_POST['moneda'], $_POST['cambio'], $_POST['base'])
                    );
                    break;
                case "E":
                    $res=$bd->dbActualizar(
                        "update monedas set moneda=? where id=?",
                        array($_POST['moneda'], $_POST['id'])
                    );
                    break;
                default:
                    $res= $bd->MsgError="Proceso Invalido";
                    break;
            }
            if ($bd->Error) {
                $msg['men']=$bd->MsgError;
                $error=$bd->Error;
            } else {
                $msg['men']=$res;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "Paises":
        $bd = new dbMysql();
        $bd->dbConectar();

        if ($va->letras($_POST['pais'], 3, 50, "Pais")) {
            $errores[]=$va->error;
        }
        if (empty($errores)) {
            switch ($_POST['tipoform']) {
                case "N":
                    $res=$bd->dbInsertar(
                        "insert into paises (id,pais,monedaoficial) values(lastid('paises'),?,?)",
                        array($_POST['pais'], $_POST['moneda'])
                    );
                    break;
                case "E":
                    $res=$bd->dbActualizar(
                        "update paises set pais=?,monedaoficial=? where id=?",
                        array($_POST['pais'], $_POST['moneda'], $_POST['id'])
                    );
                    break;
                default:
                    $res= $bd->MsgError="Proceso Invalido";
                    break;
            }
            if ($bd->Error) {
                $msg['men']=$bd->MsgError;
                $error=$bd->Error;
            } else {
                $msg['men']=$res;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "Estados":
        $bd = new dbMysql();
        $bd->dbConectar();
        if ($va->seleccion($_POST['pais'], "Pais")) {
            $errores[]=$va->error;
        }
        if ($va->letras($_POST['estado'], 3, 50, "Estado")) {
            $errores[]=$va->error;
        }
        if (empty($errores)) {
            switch ($_POST['tipoform']) {
                case "N":
                    $res=$bd->dbInsertar(
                        "insert into estados (id,pais,estado) values(lastid('estados'),?,?)",
                        array($_POST['pais'], $_POST['estado'])
                    );
                    break;
                case "E":
                    $res=$bd->dbActualizar(
                        "update estados set pais=?, estado=? where id=?",
                        array($_POST['pais'], $_POST['estado'], $_POST['id'])
                    );
                    break;
                default:
                    $res= $bd->MsgError="Proceso Invalido";
                    break;
            }
            if ($bd->Error) {
                $msg['men']=$bd->MsgError;
                $error=$bd->Error;
            } else {
                $msg['men']=$res;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "CambioMonetario":
        $bd = new dbMysql();
        $bd->dbConectar();
        for ($i=0; $i<count($_POST['cambio']); $i++) {
            if ($va->montos($_POST['cambio'][$i], 1, "", "Tasa de Cambio ".($i+1))) {
                $errores[]=$va->error;
            }
        }

        if (empty($errores)) {
            $bd->AutoCommit(false);
            for ($i=0; $i<count($_POST['cambio']); $i++) {
                $res=$bd->dbActualizar(
                    "update monedas set cambio=? where id=?",
                    array($_POST['cambio'][$i], $_POST['monedas'][$i])
                );
            }
            if ($bd->Error) {
                $msg['men']=$bd->MsgError;
                $bd->RollBack();
            } else {
                $msg['men']=$res;
                $error=0;
                $bd->Commit();
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "Configurar":
        $bd = new dbMysql();
        $bd->dbConectar();
        if ($va->numeros($_POST['dias'], 1, "", "Dias")) {
            $errores[]=$va->error;
        }
        if ($va->montos($_POST['minimo'], 1, "", "Minimo Apertura")) {
            $errores[]=$va->error;
        }
        if ($va->montos($_POST['minreg'], 1, "", "Minimo Registro")) {
            $errores[]=$va->error;
        }
        if ($va->montos($_POST['minren'], 1, "", "Minimo Renovacion")) {
            $errores[]=$va->error;
        }
        if ($va->montos($_POST['mmaximo'], 1, "", "Monto Maximo")) {
            $errores[]=$va->error;
        }
        if ($va->email($_POST['correo'], 10, "", "Email")) {
            $errores[]=$va->error;
        }
        if ($va->montos($_POST['pago'], 1, "", " de Pago de Publicidad")) {
            $errores[]=$va->error;
        }
        if ($va->alfa($_POST['marquesina'], '', 250, " Marqusina")) {
            $errores[]=$va->error;
        }

        if (empty($errores)) {
            $ConConf=$bd->dbConsultar("select id from configuracion limit 1");
            if (!$bd->Error) {
                if ($ConConf->num_rows>0) {
                    $Conf=$ConConf->fetch_array();
                    $id=$Conf['id'];
                    $res=$bd->dbActualizar(
                        "update configuracion set tiempoactivo=?, minimoinicial=?, minimoregistro=?,
                        minimorenova=?, mmaximo=?, monedabase=?, conveniofpc=?, conveniofpm=?, correo=?,
                        pppublicidad=?,marquesina=?, usuario=?, maxciclo=?, preciocupon=?, porcecupon=?,fechaesc=?,cuponesc=? where id=?",
                        array($_POST['dias'], $_POST['minimo'], $_POST['minreg'], $_POST['minren'], $_POST['mmaximo'],
                        $_POST['moneda'], $_POST['fpc'], $_POST['fpm'], $_POST['correo'], $_POST['pago'],
                        $_POST['marquesina'], "'".$_SESSION['usuario']['login']."'", $_POST['maxciclo'],
                        $_POST['preciocupon'], $_POST['porcecupon'], FTData($_POST['fechaesc']), $_POST['cuponesc'], $id)
                    );
                } else {
                    $id=1;
                    $res=$bd->dbActualizar(
                        "insert into configuracion (id,tiempoactivo, minimoinicial, minimoregistro,
                        minimorenova, mmaximo, monedabase, conveniofpc, conveniofpm, correo,
                        pppublicidad, usuario, maxciclo, preciocupon, porcecupon,fechaesc,cuponesc) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                        array($id, $_POST['dias'], $_POST['minimo'], $_POST['minreg'], $_POST['minren'],
                        $_POST['mmaximo'], $_POST['moneda'], $_POST['fpc'], $_POST['fpm'], $_POST['correo'],
                        $_POST['pago'], $_POST['marquesina'], "'".$_SESSION['usuario']['login']."'", $_POST['maxciclo'],
                        $_POST['preciocupon'],  $_POST['porcecupon'], FTData($_POST['fechaesc']), $_POST['cuponesc'])
                    );
                }

                if ($bd->Error) {
                    $msg['men']=$bd->MsgError.$bd->getSql();
                    $error=$bd->Error;
                } else {
                    $msg['men']=$res;
                    $error=0;
                }
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "BAreas":
        $bd = new dbMysql();
        $bd->dbConectar();
        $ConBanners=$bd->dbConsultar("
            select
                b.id,concat(if (ISNULL(b.posicion),'Banner Principal',concat('Despues del Articulo ',ar.titulo)),',  ',
                 (b.cantidad-count(db.id)), ' Espacios Disponibles') posicion,b.cantidad
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
        $bd = new dbMysql();
        $bd->dbConectar();

        //Hallar la Conversion y el Nombre de la Moneda en el Pais del Cliente
        $ConPaisMoneda=$bd->dbConsultar(
            "SELECT m.cambio,m.moneda from paises as p inner join monedas as m on m.id=p.monedaoficial
            where p.id=? limit 1",
            array($_SESSION['cliente']['idpais'])
        );
        if (!$bd->Error) {
            if ($ConPaisMoneda->num_rows>0) {
                $PaisMoneda=$ConPaisMoneda->fetch_array();
            } else {
                $errores="Disculpe La Moneda Local No Ha Sido Fijada";
            }
        } else {
            $errores=$bd->MsgError;
        }

        $ConPlanes=$bd->dbConsultar("
            SELECT
               p.id,concat('Publicar Durante ',p.dias,' Dias, Por ',round(p.costo),' ', m.moneda) plan,
               concat('Ancho: ',ancho,'px y Alto: ',alto,'px') dimenciones,ancho,alto
            FROM
               publicaciones as p
	              inner join paises as pa on pa.id=p.pais
	              inner join monedas as m on m.id=pa.monedaoficial
                  inner join banners as b on b.id=p.tipo
            where p.tipo=? and pa.id=?;
            ", array($_POST['idbanner'], $_POST['idpais']));
        if (!$bd->Error) {
            if ($ConPlanes->num_rows>0) {
                $error=0;
                $cadena="<option value='0'>Seleccion Plan</option>";
                while ($FPlanes=$ConPlanes->fetch_array()) {
                    $msg['dimenciones']=$FPlanes['dimenciones'];
                    $msg['ancho']=$FPlanes['ancho'];
                    $msg['alto']=$FPlanes['alto'];
                    $cadena.="<option value='".$FPlanes['id']."'>".$FPlanes['plan']."</option>";
                }
            } else {
                $cadena="<option value='0'>Disculpe No Hay Planes Definidos</option>";
                $msg['dimenciones']="";
                $msg['ancho']="";
                $msg['alto']="";


                $error=0;
            }
            $msg['men']=$cadena;
        } else {
            $msg['men']=$bd->MsgError;
        }
        break;
    case "ABanner":
        $bd = new dbMysql();
        $bd->dbConectar();
        if (!empty($_POST['id'])) {
            $bd->AutoCommit(false);
            $upd=$bd->dbActualizar(
                "update detabanner set desde=curdate(),estado='A' where id=?",
                array((int) $_POST['id'])
            );
            $upd=$bd->dbActualizar(
                "update movimientos set fautoriza=curdate(), estado='A' where id=
                (select movimiento from debamovi where detabanners=? limit 1)",
                array((int) $_POST['id'])
            );

            if (!$bd->Error) {
                $msg['men']=$upd;
                $error=0;
                $bd->Commit();
            } else {
                $msg['men']=$bd->MsgError;
                $bd->RollBack();
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>Disculpe No Envio Ningun Dato</center>";
        }
        $bd->dbDesconectar();
        break;
    case "AClasificado":
        $bd = new dbMysql();
        $bd->dbConectar();
        if (!empty($_POST['id'])) {
            $upd=$bd->dbActualizar(
                "update clasificados set factivo=curdate(),estado='A' where id=?",
                array((int) $_POST['id'])
            );
            if (!$bd->Error) {
                $msg['men']=$upd;
                $error=0;
            } else {
                $msg['men']=$bd->MsgError;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>Disculpe No Envio Ningun Dato</center>";
        }
        $bd->dbDesconectar();
        break;
    case "GClasificado":
        if ($va->seleccion($_POST['categoria'], "Categoria")) {
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
        if ($va->seleccion($_POST['Estado'], "Estado")) {
            $errores[]=$va->error;
        }

        $bd = new dbMysql();
        $bd->dbConectar();
        $ConImagen=$bd->dbConsultar("select imagenes from clasificados where id=?", array((int) $_POST['id']));
        if (!$bd->Error) {
            $Imagenes=$ConImagen->fetch_array();
            $folder=obtener_carpeta($Imagenes[0]);
            if (substr($Imagenes[0], -1)<>"|") {
                $Imagenes[0].="|";
            }
            //echo $Imagenes[0];
            //stripos(""$Imagenes[0])
        }
        //echo $folder;
        $raiz="../clasificados/";
        //echo "Carpeta: [".$folder."]<br /><br /><br /><br /><br /><br />";
        //$folder=uniqid(date("Ymdhis")).DIRECTORY_SEPARATOR;
        $foto=array();
        $maxweight=512; //Peso maximo en Kb
        $maxheight=700; //Tama�o Maximo de Alto en Kb
        $maxwidth=850;  //Tama�o Maximo de Ancho en Kb

        for ($i=0; $i<count($_FILES['imagen']['name']); $i++) {
            if (!empty($_FILES['imagen']['name'][$i])) {
                if ($va->file(
                    substr($_FILES['imagen']['name'][$i], 0, strrpos($_FILES['imagen']['name'][$i], '.', -4)),
                    1,
                    "",
                    "Imagen ".($i+1)
                )) {
                    $errores[]=$va->error;
                }
                $tipos=array("jpg","png","gif");
                $file[$i]=$_FILES['imagen']['name'][$i];
                $ext=strtolower(substr(
                    $_FILES['imagen']['name'][$i],
                    (strrpos($_FILES['imagen']['name'][$i], '.', -4)+1)
                ));
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
                    $errores[]="Disculpe La Imagen ".($i+1)." es de [".$size[0]."x".
                        $size[1]."]px, y debe ser de [$maxwidth x $maxheight]px";
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
                        $imagen.="Imagen $i Cargada Correctamente";
                        //devolvemos el nombre del archivo para pintar la imagen
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
            if (!$bd->Error) {
                $add2=$bd->dbActualizar(
                    "update clasificados set categoria=?,idpais=?,idestado=?,titulo=?,descripcion=?,
                    direccion=?,imagenes=? where id=?",
                    array($_POST['categoria'], $_POST['BPais'], $_POST['Estado'], $_POST['titulo'],
                    $_POST['descripcion'], $_POST['contacto'], $Imagenes[0].$imagenes, (int) ($_POST['id']))
                );
                //echo $bd->getSql();
                if (!$bd->Error) {
                    $error=0;
                    $msg['men']="Clasificado Actualizado Correctamente";
                } else {
                    $msg['men']=$bd->MsgError.$bd->getSql();
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
    case "GBanner":
        if ($va->longitud($_POST['titulo'], 5, 150, "Titulo", "Caracteres")) {
            $errores[]=$va->error;
        }
        if ($va->longitud($_POST['enlace'], 5, 150, "Enlace", "Caracteres")) {
            $errores[]=$va->error;
        }

        $bd = new dbMysql();
        $bd->dbConectar();
        $raiz="../banners/";
        $folder=uniqid(date("Ymdhis"))."/";
        //$foto=array();
        $maxweight=512; //Peso maximo en Kb
        $maxheight=$_POST['alto']; //Tama�o Maximo de Alto en px
        $maxwidth=$_POST['ancho'];  //Tama�o Maximo de Ancho en px

        //if (!empty($_POST['idfoto']))   $foto=$_POST['idfoto'];
        if (!empty($_FILES['imagen']['name'])) {
            if ($va->file(
                substr($_FILES['imagen']['name'], 0, strrpos($_FILES['imagen']['name'], '.', -4)),
                1,
                "",
                "Imagen "
            )) {
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
            } elseif ($size[0]<>$maxwidth || $size[1]<>$maxheight) {    //Maximo de Ancho y Alto en px
                $errores[]="Disculpe La Imagen es de [".$size[0]."x".$size[1].
                    "]px, y debe ser de [$maxwidth x $maxheight]px";
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

            //Buscar Los Datos del Plan
            /*
            $ConPlan=$bd->dbConsultar("select dias,costo from publicaciones where id=?",array($_POST['CPlanBanner']));
            if (!$bd->Error){
                if ($ConPlan->num_rows>0){
                    $Plan=$ConPlan->fetch_array();
                }
            }
            */
            if (!empty($imagenes)) {
                $banner="banner='".$imagenes."',";
            }
            $add=$bd->dbActualizar(
                "update detabanner set titulo=?,".$banner."enlace=? where id=?",
                array($_POST['titulo'], $_POST['enlace'], (int) $_POST['id'])
            );
            if (!$bd->Error) {
                $error=0;
                $msg['men']="Banner Actualizado Correctamente";
            } else {
                $msg['men']=$bd->MsgError;
                @unlink($foto);
                @rmdir($raiz.$folder);
            }
        } else {
            $msg['men']="OBSERVACIONES<ul><li>".implode("<li>", $errores)."</ul>";
        }
        break;
    case "EliminarIMGClas":
        $bd = new dbMysql();
        $bd->dbConectar();
        $ConImg=$bd->dbConsultar("select imagenes from clasificados where id=?", array($_POST['clasificado']));
        $enc=false;
        if (!$bd->Error) {
            if ($ConImg->num_rows>0) {
                $Img=$ConImg->fetch_array();
                $imagenes=explode("|", $Img['imagenes']);
                $j=0;
                for ($i=0; $i<count($imagenes); $i++) {
                    if (!empty($imagenes[$i])) {
                        if (substr($imagenes[$i], strrpos($imagenes[$i], "/")+1)==$_POST['imagen'] && !$enc) {
                            $imagenborrada=$imagenes[$i];
                            $imagenvpborrada=substr($imagenes[$i], 0, strrpos($imagenes[$i], "/")+1).
                                "vp/".$_POST['imagen'];
                            $imagenes[$i]="";
                            $enc=true;
                            //break;
                        } else {
                            $newimagenes[$j]=$imagenes[$i];
                            $j++;
                        }
                    }
                }
                if ($enc) {
                    $imagen=implode("|", $newimagenes);
                    $upd=$bd->dbActualizar(
                        "update clasificados set imagenes=? where id=?",
                        array($imagen, $_POST['clasificado'])
                    );
                    if (!$bd->Error) {
                        @unlink($imagenborrada);
                        @unlink($imagenvpborrada);
                        $msg['men']="Foto Borrada ";
                        $error=0;
                    }
                } else {
                    $msg['men']="Imagen No Borrada [".$_POST['imagen']."]";
                }
            } else {
                $msg['men']="No Se Encontraron Datos";
            }
        }
        $bd->dbDesconectar();
        break;

    case "EliminarIMGBann":
        $bd = new dbMysql();
        $bd->dbConectar();
        $ConImg=$bd->dbConsultar("select banner from detabanner where id=?", array($_POST['idbanner']));
        $enc=false;
        if (!$bd->Error) {
            if ($ConImg->num_rows>0) {
                $Img=$ConImg->fetch_array();
                $imagenes=$Img['banner'];
                if (!empty($imagenes)) {
                    if (substr($imagenes, strrpos($imagenes, "/")+1)==$_POST['imagen'] && !$enc) {
                        $imagenborrada=$imagenes;
                        $folder=substr($imagenes, 0, strrpos($imagenes, "/")+1);
                        $imagenvpborrada=$folder."vp/".$_POST['imagen'];

                        $upd=$bd->dbActualizar(
                            "update detabanner set banner=null where id=?",
                            array($_POST['idbanner'])
                        );
                        if (!$bd->Error) {
                            @unlink($imagenborrada);
                            @unlink($imagenvpborrada);
                            @rmdir($folder."vp");
                            @rmdir($folder);
                            $msg['men']="Imagen Borrada ";
                            $error=0;
                        } else {
                            $msg['men']="Imagen No Borrada [".$_POST['imagen']."] ".$bd->getSql();
                        }
                    }
                }
            } else {
                $msg['men']="No Se Encontraron Datos";
            }
        }
        $bd->dbDesconectar();
        break;

    case "RPublicaciones":
        $bd = new dbMysql();
        $bd->dbConectar();
        if ($va->seleccion($_POST['tipo'], "Aplicar A")) {
            $errores[]=$va->error;
        }
        if ($_POST['tipo']=="B") {
            if ($va->seleccion($_POST['IdBanner'], "Banner")) {
                $errores[]=$va->error;
            }
            $tipo=$_POST['IdBanner'];
        }
        if ((int) $_POST['dias']<=0) {
            $errores[]="Indique la Cantidad de Dias";
        }
        if ($va->montos($_POST['costo'], 1, 15, "Costo")) {
            $errores[]=$va->error;
        }
        if ($_POST['tipo']=="C") {
            if ($va->seleccion($_POST['foto'], "Con Foto")) {
                $errores[]=$va->error;
            }
            $tipo=null;
        }

        if (empty($errores)) {
            switch ($_POST['tipoform']) {
                case "N":
                    $res=$bd->dbInsertar(
                        "insert into publicaciones (id,tipo,dias,costo,foto) values(lastid('publicaciones'),?,?,?,?)",
                        array($tipo, $_POST['dias'], $_POST['costo'], $_POST['foto'])
                    );
                    break;
                case "E":
                    $res=$bd->dbActualizar(
                        "update publicaciones set dias=?,costo=?,foto=? where id=?",
                        array($_POST['dias'], $_POST['costo'], $_POST['foto'], (int) $_POST['id'])
                    );
                    break;
                default:
                    break;
            }
            if ($bd->Error) {
                $msg['men']=$bd->MsgError;
                $error=$bd->Error;
            } else {
                $msg['men']=$res;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "borrarimg":
        $bd = new dbMysql();
        $bd->dbConectar();
        $IdArea=(int) $_POST['area'];
        $ConArea=$bd->dbConsultar("select banners from areas where id=?", array($IdArea));
        if (!$bd->Error) {
            if ($ConArea->num_rows>0) {
                $Area=$ConArea->fetch_array();
                if (@unlink($_POST['imagen'])) {
                    $Banners=$Area['banners'];
                    $upd=$bd->dbActualizar(
                        "update areas set banners=? where id=?",
                        array(str_ireplace(":".$_POST['imagen'], "", $Banners), $IdArea)
                    );
                    if ($bd->Error) {
                        $msg['men']=$bd->MsgError.$bd->getSql();
                    } else {
                        $error=0;
                        $msg['men']=$upd;
                    }
                } else {
                    $msg['men']="Disculpe, Imagen No Eliminada";
                }
            }
        } else {
            $msg['men']=$bd->MsgError;
        }
        $bd->dbDesconectar();
        break;
    case "DEBanners":
        $bd = new dbMysql();
        $bd->dbConectar();
        if ($va->seleccion($_POST['BArea'], "Area")) {
            $errores[]=$va->error;
        }
        if ($_POST['BAArticulos']==0) {
            $_POST['BAArticulos']=null;
        }
        if ($va->numeros($_POST['ancho'], 1, "", "Ancho")) {
            $errores[]=$va->error;
        }
        if ($va->numeros($_POST['alto'], 1, "", "Alto")) {
            $errores[]=$va->error;
        }
        if (empty($_POST['rotativo'])) {
            $_POST['cantidad']=1;
            $_POST['rotativo']="N";
        }

        $raiz="../banners/";
        $folder=uniqid(date("Ymdhis"))."/";
        $maxweight=512; //Peso maximo en Kb
        $maxheight=$_POST['alto']; //Tama�o Maximo de Alto en px
        $maxwidth=$_POST['ancho'];  //Tama�o Maximo de Ancho en px

        if (!empty($_FILES['imagen']['name'])) {
            if ($va->file(
                substr($_FILES['imagen']['name'], 0, strrpos($_FILES['imagen']['name'], '.', -4)),
                1,
                "",
                "Imagen "
            )) {
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
                $errores[]="Disculpe La Imagen es de [".$size[0]."x".$size[1].
                    "]px, y debe ser de [$maxwidth x $maxheight]px";
            }
            $foto=$raiz.$folder.$file;
        }



        if ($_POST['cantidad']<1) {
            $cantidad =1;
        } else {
            $cantidad=(int) $_POST['cantidad'];
        }
        //if ($va->seleccion($_POST['submenu'],"Sub Menu"))   $errores[]=$va->error;
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


            switch ($_POST['tipoform']) {
                case "N":
                    $res=$bd->dbInsertar(
                        "insert into banners (id,idarea,posicion,ancho,alto,rotativo,cantidad,usuario,imagen)
                        values(lastid('banners'),?,?,?,?,?,?,?,?)",
                        array($_POST['BArea'], $_POST['BAArticulos'], $_POST['ancho'], $_POST['alto'],
                        $_POST['rotativo'], $cantidad, $_SESSION['usuario']['login'], $imagenes)
                    );
                    break;
                case "E":
                    if (!empty($imagenes)) {
                        $imagen=",imagen='".$imagenes."'";
                    } else {
                        $imagen="";
                    }
                    $res=$bd->dbActualizar(
                        "update banners set idarea=?, posicion=?, ancho=?, alto=?,rotativo=?,cantidad=?".$imagen.
                        " where id=?",
                        array($_POST['BArea'], $_POST['BAArticulos'], $_POST['ancho'], $_POST['alto'],
                        $_POST['rotativo'], $cantidad, (int) $_POST['id'])
                    );
                    break;
                default:
                    break;
            }
            if ($bd->Error) {
                $msg['men']=$bd->MsgError.$bd->getSql();
                $error=$bd->Error;
            } else {
                $msg['men']=$res;
                $error=0;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "BanArticulos":
        $bd = new dbMysql();
        $bd->dbConectar();
        $IdArea=(int) $_POST['area'];
        $ConArticulo=$bd->dbConsultar(
            "select id,concat('Despues del Articulo > ',titulo) as titulo from articulos where estado='A' and area=?",
            array($IdArea)
        );
        if (!$bd->Error) {
            if ($ConArticulo->num_rows>0) {
                $error=0;
                $cadena.="<option value='0'>Banner Principal</option>";
                while ($Articulo=$ConArticulo->fetch_array()) {
                    $cadena.="<option value='".$Articulo['id']."'>".$Articulo['titulo']."</option>";
                }
                $msg['men']=$cadena;
            } else {
                $msg['men']="No Hay Registros";
            }
        } else {
            $msg['men']=$bd->MsgError;
        }
        $bd->dbDesconectar();
        break;
     //BanArticulos
    case "Liquidez":
        $bd = new dbMysql();
        $bd->dbConectar();
        $bd->dbActualizar("update franquiciados set estado='I' where fin <curdate() and estado='A'", array());
        $bd->AutoCommit(false);
        $Result=RecorrerArbol($bd);
        if (is_int($Result)) {
            $msg['men']="La Liquidacion Mensual a Sido Generada";
        }
        $bd->Commit();
        $bd->dbDesconectar();
        break;
    case "activardep":
        $bd = new dbMysql();
        $bd->dbConectar();
        if (empty($_POST['deposito'])) {
            $errores[]="Active el Check del Deposito Que Desee Activar";
        }
        if (empty($_POST['cedula'])) {
            $errores[]="Disculpe Cliente No Encontrado";
        }
        if ($va->fecha($_POST['desde'], "", "", "Fecha de Inicio")) {
            $errores[]=$va->error;
        }
        if ($va->fecha($_POST['hasta'], $_POST['desde'], "", "Fecha de Culminaci&oacute;n")) {
            $errores[]=$va->error;
        }
        for ($i=0; $i<count($_POST['deposito']); $i++) {
            if ($va->fecha($_POST['fefectivo'][$_POST['deposito'][$i]], "", "", "Fecha de Culminaci&oacute;n")) {
                $errores[]=$va->error;
            }
        }

        /*Busqueda de la Informaci�n del Cliente Para Enviarlo en el Correo*/
        $ConCliente=$bd->dbConsultar(
            "select
                c.cedula,c.nombre,c.apellido,date_format(c.fregistro,'%d/%m/%Y') fregistro,p.pais,
                c.pais idpais,p.monedaoficial idmoneda, m.moneda, m.cambio,c.minimoap,c.email
            from clientes as c
                inner join paises as p on c.pais=p.id inner join monedas as m on p.monedaoficial=m.id
            where c.cedula=? limit 1",
            array("'".$_POST['cedula']."'")
        );
        if (!$bd->Error) {
            $FCliente=$ConCliente->fetch_array();
        } else {
            $errores[]=$bd->MsgError;
        }

        if (empty($errores)) {
            $suma=0;
            $depositos=null;
            $bd->AutoCommit(false);
            for ($i=0; $i<count($_POST['deposito']); $i++) {
                $Cambio=CambioMonetario($bd, $FCliente['idmoneda'], $_POST['fefectivo'][$_POST['deposito'][$i]]);
                $Monedabase=round($_POST['local'][$_POST['deposito'][$i]]/$Cambio, 2);
                if (is_numeric($_POST['deposito'][$i]) || is_double($_POST['deposito'][$i])) {
                    $Referencia="'".$_POST['deposito'][$i]."'";
                } else {
                    $Referencia=$_POST['deposito'][$i];
                }
                $bd->dbActualizar(
                    "update movimientos set monto_base=?,fautoriza=? where referencia=? and cliente=? and estado='N'",
                    array(
                        $Monedabase,
                        FData($_POST['fefectivo'][$_POST['deposito'][$i]]),
                        $Referencia,
                        "'".$_POST['cedula']."'"
                    )
                );
                if ($bd->Error) {
                    $errores[]="Error xxx".$bd->MsgError.$bd->getSql()."xx";
                    $bd->RollBack();
                }
                $suma+=$Monedabase;
                if ($i>0) {
                    $depositos.=", '".$_POST['deposito'][$i]."'";
                } else {
                    $depositos="'".$_POST['deposito'][$i]."'";
                }
            }
            $bd->Commit();
            $suma=round($suma, 2);

            if ($suma<$_POST['minimo']) {
                $errores[]="Disculpe el Monto de los Depositos es menor al monto minimo de apertura";
            }
        }
        //print_r($errores);
        if (empty($errores)) {
            /*Busqueda de los Datos de Configuracion para Obtener el Corrreo*/
            $ConConfig=$bd->dbConsultar(
                "select
                    c.tiempoactivo dias,c.minimoinicial minimo,c.mmaximo maximo,c.minimoregistro mregistro,
                    c.minimorenova mrenova,m.moneda,c.correo
                from configuracion as c
                    inner join monedas as m on m.id=c.monedabase
                where c.id=1
                limit 1"
            );
            if (!$bd->Error) {
                $FConfig=$ConConfig->fetch_array();
            } else {
                $msg['men']=$bd->MsgError;
            }

            $ConDepositos=$bd->dbConsultar(
                "SELECT b.banco,c.cuenta,d.referencia,d.fecha,d.monto_oficial,d.monto_base
                FROM  movimientos as d
                    inner join cuentas as c on c.id=d.cuenta
                    inner join bancos as b on b.id=c.banco
                where d.estado='N' and d.cliente=? and referencia in($depositos)",
                array("'".$_POST['cedula']."'")
            );
            if (!$bd->Error) {
                $transacciones=array();
                if ($ConDepositos->num_rows>0) {
                    $transacciones['title']='Listado de Transacciones Tomandas en Cuenta para la Activación';
                    $transacciones['details']=array();
                    /*
                    $DDepositos="<table align='center' width='100%' border='1'>";
                    $DDepositos.="<tr align='center' style='font-weight:bold;'><td>Banco</td><td>Cuenta</td>".
                    $DDepositos.="<td>Referencia</td><td>Fecha</td><td>".$FCliente['moneda']."</td><td>".
                    $DDepositos.=$FConfig['moneda']."</td></tr>";
                    */
                    $sumamoneda=0;
                    $sumamonedabase=0;
                    while ($FDepositos=$ConDepositos->fetch_array()) {
                        array_push($transacciones['details'], array(
                            'Banco'=>$FDepositos['banco'],
                            'Cuenta'=>$FDepositos['cuenta'],
                            'Referencia'=>$FDepositos['referencia'],
                            'Fecha'=>FUser($FDepositos['fecha']),
                            $FCliente['moneda']=>$FDepositos['monto_oficial'],
                            $FConfig['moneda']=>$FDepositos['monto_base']
                        ));
                        $sumamoneda+=$FDepositos['monto_oficial'];
                        $sumamonedabase+=$FDepositos['monto_base'];
                        /*
                        $DDepositos.="<tr><td align='center'>".$FDepositos['banco']."</td><td align='center'>";
                        $DDepositos.=$FDepositos['cuenta']."</td><td align='center'>".$FDepositos['numero']."</td>";
                        $DDepositos.="<td align='center'>".FUser($FDepositos['fecha'])."</td><td align='right'>".
                        $DDepositos.=$FDepositos['monto_oficial']."</td><td align='right'>".$FDepositos['monto_base'].
                        $DDepositos.="</td></tr>";

                        */
                    }
                    /*
                    $DDepositos.="<tr><td colspan='4' align='right' style='font-weight:bold;'>Total</td>";
                    $DDepositos.="<td align='right'>$sumamoneda</td><td align='right'>$sumamonedabase</td>";
                    $DDepositos.="</tr></table>";
                    */
                }
                //$FCliente=$ConCliente->fetch_array();
            } else {
                $msg['men']=$bd->MsgError;
            }
            $CEscritorio=$bd->GenAlfa(12);


            //Creacion de Nuevo Registro de Participacion
            if (empty($msg)) {
                $bd->AutoCommit(false);
                $res=$bd->dbInsertar(
                    "insert into franquiciados (id,cliente,franquicia,inicio,fin,monto,estado)
                    values(lastid('franquiciados'),?,'FPC',?,?,?,'A')",
                    array("'".$_POST['cedula']."'", FData($_POST['desde']), FData($_POST['hasta']), $suma)
                );
                if ($bd->Error) {
                    $msg['men']="Error Insertando ".$bd->MsgError;
                } else {
                    //Actualizo Los Depositos Cambiando el Estatus a 'A'
                    $upd=$bd->dbActualizar(
                        "update movimientos set estado='A', fautoriza=curdate() where cliente=? and estado='N'
                        and franquicia='FPC' and referencia in ($depositos)",
                        array("'".$_POST['cedula']."'")
                    );
                    if (!$bd->Error) {
                        if ($bd->Commit()) {
                            $bd->AutoCommit(true);
                            $upd2=$bd->dbActualizar(
                                "update clientes set cescritorio=?, estado='A' where cedula=?",
                                array($CEscritorio, "'".$_POST['cedula']."'")
                            );
                            if (!$bd->Error) {
                                $msg['men']=$res. " <br /> Los Depositos han sido Autorizados<br /> ";
                                /*
                                print_r(array(
                                    array(
                                        'nombre'=>$FCliente['apellido'].', '.$FCliente['nombre'],
                                        'mail'=>$FCliente['email']
                                    ),
                                    array(
                                        'nombre'=>'Fondo Interactivo de Negocios',
                                        'mail'=>$FConfig['correo']
                                    ),
                                    'Carta de Activación',
                                    carta_activacion(array(
                                        'nombre'=>$FCliente['apellido'].', '.$FCliente['nombre'],
                                        'cescritorio'=>$CEscritorio,
                                        'transacciones'=>$transacciones
                                    ))
                                ));
                                */
                                $msg['men'].="Clave de Escritorio Generada <br />".
                                $mail->send(
                                    array(
                                        'nombre'=>'Fondo Interactivo de Negocios',
                                        'mail'=>$FConfig['correo']
                                    ),
                                    array(
                                        'nombre'=>$FCliente['apellido'].', '.$FCliente['nombre'],
                                        'mail'=>$FCliente['email']
                                    ),
                                    'Carta de Activación',
                                    carta_activacion(array(
                                        'nombre'=>$FCliente['apellido'].', '.$FCliente['nombre'],
                                        'cescritorio'=>$CEscritorio,
                                        'desde'=>$_POST['desde'],
                                        'hasta'=>$_POST['hasta'],
                                        'transacciones'=>$transacciones,
                                        'totalmoficial'=>$sumamoneda,
                                        'totalmbase'=>$sumamonedabase
                                    ))
                                );
                                $error=0;
                            } else {
                                $msg['men']="Error ".$bd->MsgError;
                            }
                        } else {
                            $msg['men']="No Se Pudo Completar la Transaccion";
                        }
                    } else {
                        $bd->RollBack();
                        $bd->AutoCommit(true);
                        $msg['men']="Error".$bd->MsgError;
                    }
                }
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "retirar":
        $bd = new dbMysql();
        $bd->dbConectar();
        for ($i=0; $i<count($_POST['retiros']); $i++) {
            if ($va->fecha(
                $_POST['fefectivo'][$_POST['retiros'][$i]],
                "",
                "",
                "Fecha de Pago en Retiro Por ".$_POST['base'][$_POST['retiros'][$i]]
            )) {
                $errores[]=$va->error;
            }
            if ($va->alfa(
                $_POST['referencia'][$_POST['retiros'][$i]],
                6,
                15,
                "Referencia en Retiro Por ".$_POST['base'][$_POST['retiros'][$i]]
            )) {
                $errores[]=$va->error;
            }
        }

            /*Busqueda de la Informaci�n del Cliente Para Enviarlo en el Correo*/
        $ConCliente=$bd->dbConsultar(
            "select c.cedula,c.nombre,c.apellido,date_format(c.fregistro,'%d/%m/%Y') fregistro,p.pais,
            c.pais idpais,p.monedaoficial idmoneda, m.moneda, m.cambio,c.minimoap from clientes as c
            inner join paises as p on c.pais=p.id inner join monedas as m on p.monedaoficial=m.id
            where c.cedula=? limit 1",
            array($_POST['cedula'])
        );
        if (!$bd->Error) {
            $FCliente=$ConCliente->fetch_array();
        } else {
            $errores[]=$bd->MsgError;
        }

        if (empty($errores)) {
            $suma=0;
            $retiros=null;
            $bd->AutoCommit(false);
            for ($i=0; $i<count($_POST['retiros']); $i++) {
                $Cambio=CambioMonetario($bd, $FCliente['idmoneda'], $_POST['fefectivo'][$_POST['retiros'][$i]]);
                $Monedabase=round($_POST['base'][$_POST['retiros'][$i]]*$Cambio, 2);
                if (is_integer($_POST['referencia'][$_POST['retiros'][$i]]) ||
                    is_double($_POST['referencia'][$_POST['retiros'][$i]])
                ) {
                    $Referencia="'".$_POST['referencia'][$_POST['retiros'][$i]]."'";
                } else {
                    $Referencia=$_POST['referencia'][$_POST['retiros'][$i]];
                }
                $bd->dbActualizar(
                    "update movimientos set monto_oficial=?,fautoriza=?,referencia=? where id=? and cliente=?
                    and estado='P' and movimiento='Retiro'",
                    array(
                        $Monedabase,
                        FData($_POST['fefectivo'][$_POST['retiros'][$i]]),
                        $Referencia,
                        $_POST['retiros'][$i],
                        "'".$_POST['cedula']."'"
                    )
                );
                //echo $bd->getSql();
                if ($bd->Error) {
                    $errores[]=$bd->MsgError;
                    $bd->RollBack();
                }
                $suma+=$Monedabase;
                if ($i>0) {
                    $retiros.=", '".$_POST['retiros'][$i]."'";
                } else {
                    $retiros="'".$_POST['retiros'][$i]."'";
                }
            }
            $bd->Commit();
            $bd->AutoCommit(true);
        }

        if (empty($errores)) {
            /*Busqueda de los Datos de Configuracion para Obtener el Corrreo*/
            $ConConfig=$bd->dbConsultar(
                "SELECT c.tiempoactivo dias,c.minimoinicial minimo,c.mmaximo maximo,c.minimoregistro mregistro,
                c.minimorenova mrenova,m.moneda,c.correo  FROM configuracion AS c INNER JOIN monedas AS m
                ON m.id=c.monedabase WHERE c.id=1 LIMIT 1"
            );
            if (!$bd->Error) {
                $FConfig=$ConConfig->fetch_array();
            } else {
                $msg['men']=$bd->MsgError;
            }

            $ConDepositos=$bd->dbConsultar(
                "SELECT c.banco,c.cuenta,d.referencia,d.fecha,d.monto_oficial,d.monto_base FROM  movimientos AS d
                INNER JOIN cuentas AS c ON c.id=d.cuenta WHERE d.estado='P' AND d.cliente=? AND d.id IN($retiros)",
                array("'".$_POST['cedula']."'")
            );
            if (!$bd->Error) {
                if ($ConDepositos->num_rows>0) {
                    $DDepositos="<table align='center' width='100%' border='1'>";
                    $DDepositos.="<tr align='center' style='font-weight:bold;'><td>Banco</td><td>Cuenta</td>".
                        "<td>Referencia</td><td>Fecha</td><td>".$FCliente['moneda']."</td><td>".
                        $FConfig['moneda']."</td></tr>";
                    $sumamoneda=0;
                    $sumamonedabase=0;
                    while ($FDepositos=$ConDepositos->fetch_array()) {
                        $DDepositos.="<tr><td align='center'>".$FDepositos['banco']."</td><td align='center'>".
                            $FDepositos['cuenta']."</td><td align='center'>".$FDepositos['numero'].
                            "</td><td align='center'>".FUser($FDepositos['fecha'])."</td><td align='right'>".
                            $FDepositos['monto_oficial']."</td><td align='right'>".$FDepositos['monto_base'].
                            "</td></tr>";
                        $sumamoneda+=$FDepositos['monto_oficial'];
                        $sumamonedabase+=$FDepositos['monto_base'];
                    }
                    $DDepositos.="<tr><td colspan='4' align='right' style='font-weight:bold;'>Total</td>".
                    "<td align='right'>$sumamoneda</td><td align='right'>$sumamonedabase</td></tr></table>";
                }
            } else {
                $msg['men']=$bd->MsgError.$bd->getSql();
            }

            $Texto="<center><strong>Retiro de Fondos de la Franquicia de Participaci&oacute;n ".
                "de Capitales</strong></center>";
            $Texto.="<br />A continuaci&oacute;n se presenta una relacion de Depositos abonados en sus ".
                "respectivas cuentas, de retiros solicitados de la Franquicia<br />".$DDepositos."<br />";
            if (empty($msg)) {
                //Actualizo Los Depositos Cambiando el Estatus a 'A'
                $upd=$bd->dbActualizar(
                    "update movimientos set estado='A' where cliente=? and estado='P' and franquicia='FCG' and ".
                    "movimiento='Retiro' and id in ($retiros)",
                    array("'".$_POST['cedula']."'")
                );
                if (!$bd->Error) {
                    $msg['men']="Los Retiros Seleccionados Fueron Procesados Correctamente ".$Texto;
                    $error=0;
                } else {
                    $msg['men']="No Se Pudo Completar la Transaccion ".$bd->getSql().$bd->MsgError;
                }
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        //print_r($msg);
        $bd->dbDesconectar();
        break;
    case "recapi":
        $bd = new dbMysql();
        $bd->dbConectar();
        if (count($_POST['deposito'])<=0) {
            $errores[]="Disculpe No Hay Ningun Deposito Seleccionado";
        }
        for ($i=0; $i<count($_POST['deposito']); $i++) {
            if ($va->fecha($_POST['fefectivo'][$_POST['deposito'][$i]], "", "", "Fecha del Abono")) {
                $errores[]=$va->error;
            }
        }

        /*Busqueda de la Informaci�n del Cliente Para Enviarlo en el Correo*/
        $ConCliente=$bd->dbConsultar(
            "select c.cedula,c.nombre,c.apellido,date_format(c.fregistro,'%d/%m/%Y') fregistro,p.pais,
            c.pais idpais,p.monedaoficial idmoneda, m.moneda, m.cambio,c.minimoap from clientes as c
            inner join paises as p on c.pais=p.id inner join monedas as m on p.monedaoficial=m.id
            where c.cedula=? limit 1",
            array("'".$_POST['cedula']."'")
        );
        if (!$bd->Error) {
            $FCliente=$ConCliente->fetch_array();
        } else {
            $errores[]=$bd->MsgError.$bd->getSql();
        }

        if (empty($errores)) {
            $suma=0;
            $depositos=null;
            $bd->AutoCommit(false);
            for ($i=0; $i<count($_POST['deposito']); $i++) {
                $Cambio=CambioMonetario($bd, $FCliente['idmoneda'], $_POST['fefectivo'][$_POST['deposito'][$i]]);
                $Monedabase=round($_POST['local'][$_POST['deposito'][$i]]/$Cambio, 2);
                if (is_integer($_POST['deposito'][$i]) || is_double($_POST['deposito'][$i])) {
                    $Referencia="'".$_POST['deposito'][$i]."'";
                } else {
                    $Referencia=$_POST['deposito'][$i];
                }
                $bd->dbActualizar(
                    "update movimientos set monto_base=?,fautoriza=? where referencia=? and cliente=? and estado='R'",
                    array(
                        $Monedabase,
                        FData($_POST['fefectivo'][$_POST['deposito'][$i]]),
                        "'".$Referencia."'",
                        "'".$_POST['cedula']."'"
                    )
                );
                if ($bd->Error) {
                    $errores[]=$bd->MsgError.$bd->getSql();
                    $bd->RollBack();
                }
                $suma+=$Monedabase;
                if ($i>0) {
                    $depositos.=", '".$_POST['deposito'][$i]."'";
                } else {
                    $depositos="'".$_POST['deposito'][$i]."'";
                }
            }
            $bd->Commit();
            $suma=round($suma, 2);
        }

        //print_r($errores);
        if (empty($errores)) {
            /*Busqueda de los Datos de Configuracion para Obtener el Corrreo*/
            $ConConfig=$bd->dbConsultar(
                "SELECT c.tiempoactivo dias,c.minimoinicial minimo,c.mmaximo maximo,c.minimoregistro mregistro,
                c.minimorenova mrenova,m.moneda,c.correo  from configuracion as c inner join monedas as m on
                m.id=c.monedabase where c.id=1 limit 1"
            );
            if (!$bd->Error) {
                $FConfig=$ConConfig->fetch_array();
            } else {
                $msg['men']=$bd->MsgError.$bd->getSql();
            }

            $ConDepositos=$bd->dbConsultar(
                "SELECT b.banco,c.cuenta,d.referencia,d.fecha,d.monto_oficial,d.monto_base FROM movimientos as d
                inner join cuentas as c on c.id=d.cuenta inner join bancos as b on c.banco=b.id where d.estado='R'
                and d.cliente=? and referencia in($depositos)",
                array("'".$_POST['cedula']."'")
            );
            if (!$bd->Error) {
                if ($ConDepositos->num_rows>0) {
                    $DDepositos="<table align='center' width='100%' border='1'>";
                    $DDepositos.="<tr align='center' style='font-weight:bold;'><td>Banco</td><td>Cuenta</td>".
                        "<td>Referencia</td><td>Fecha</td><td>".$FCliente['moneda']."</td><td>".$FConfig['moneda'].
                        "</td></tr>";
                    $sumamoneda=0;
                    $sumamonedabase=0;
                    while ($FDepositos=$ConDepositos->fetch_array()) {
                        $DDepositos.="<tr><td align='center'>".$FDepositos['banco']."</td><td align='center'>".
                            $FDepositos['cuenta']."</td><td align='center'>".$FDepositos['referencia'].
                            "</td><td align='center'>".FUser($FDepositos['fecha'])."</td><td align='right'>".
                            $FDepositos['monto_oficial']."</td><td align='right'>".$FDepositos['monto_base'].
                            "</td></tr>";
                        $sumamoneda+=$FDepositos['monto_oficial'];
                        $sumamonedabase+=$FDepositos['monto_base'];
                    }
                    $DDepositos.="<tr><td colspan='4' align='right' style='font-weight:bold;'>Total</td>".
                        "<td align='right'>$sumamoneda</td><td align='right'>$sumamonedabase</td></tr></table>";
                }
            } else {
                $msg['men']=$bd->MsgError;
            }

            $Texto="<center><strong>Recapitalizacion de Franquicia de Participaci&oacute;n de Capitales".
                "</strong></center>";
            $Texto.="<br /><br />A continuaci&oacute;n se presenta la relaci&oacute;n del o los depositos ".
                "utilizados para recapitalizar la Participaci&oacute;n <br /><br />".$DDepositos."<br />";

            //Creacion de Nuevo Registro de Participacion
            if (empty($msg)) {
                $bd->AutoCommit(false);
                $res=$bd->dbInsertar(
                    "update franquiciados set monto=monto+? where cliente=? and curdate() between inicio and fin
                    and estado='A'",
                    array($suma, "'".$_POST['cedula']."'")
                );
                if ($bd->Error) {
                    $msg['men']="Error Actualizando ".$bd->MsgError;
                } else {
                    //Actualizo Los Depositos Cambiando el Estatus a 'A'
                    $upd=$bd->dbActualizar(
                        "update movimientos set estado='A' where cliente=? and estado='R' and franquicia='REC' ".
                        "and referencia in ($depositos)",
                        array("'".$_POST['cedula']."'")
                    );
                    if (!$bd->Error) {
                        if ($bd->Commit()) {
                            $msg['men']=$res. " <br /> Los Depositos han sido Autorizados, ".
                                "La participaci&oacute;n ha Sido Recapitalizada ".$Texto;
                            $error=0;
                        } else {
                            $bd->RollBack();
                            $msg['men']="No Se Pudo Completar la Transaccion";
                        }
                    } else {
                        $msg['men']="Error".$bd->MsgError;
                    }
                }
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "renovar":
        $bd = new dbMysql();
        $bd->dbConectar();
        if (count($_POST['deposito'])<=0) {
            $errores[]="Disculpe No Hay Ningun Deposito Seleccionado";
        }
        for ($i=0; $i<count($_POST['deposito']); $i++) {
            if ($va->fecha($_POST['fefectivo'][$_POST['deposito'][$i]], "", "", "Fecha del Abono")) {
                $errores[]=$va->error;
            }
        }

        /*Busqueda de la Informaci�n del Cliente Para Enviarlo en el Correo*/
        $ConCliente=$bd->dbConsultar(
            "select c.cedula,c.nombre,c.apellido,date_format(c.fregistro,'%d/%m/%Y') fregistro,p.pais,
            c.pais idpais,p.monedaoficial idmoneda, m.moneda, m.cambio,c.minimoap from clientes as c
            inner join paises as p on c.pais=p.id inner join monedas as m on p.monedaoficial=m.id
            where c.cedula=? limit 1",
            array("'".$_POST['cedula']."'")
        );
        if (!$bd->Error) {
            $FCliente=$ConCliente->fetch_array();
        } else {
            $errores[]=$bd->MsgError.$bd->getSql();
        }
        if (empty($errores)) {
            $suma=0;
            $depositos=null;
            $fechas=array();
            $bd->AutoCommit(false);
            for ($i=0; $i<count($_POST['deposito']); $i++) {
                array_push($fechas, preg_replace('/([^0-9])/', '', FData($_POST['fefectivo'][$_POST['deposito'][$i]])));
                $Cambio=CambioMonetario($bd, $FCliente['idmoneda'], $_POST['fefectivo'][$_POST['deposito'][$i]]);
                $Monedabase=round($_POST['local'][$_POST['deposito'][$i]]/$Cambio, 2);
                if (is_integer($_POST['deposito'][$i]) || is_double($_POST['deposito'][$i])) {
                    $Referencia="'".$_POST['deposito'][$i]."'";
                } else {
                    $Referencia=$_POST['deposito'][$i];
                }
                $bd->dbActualizar(
                    "update movimientos set monto_base=?,fautoriza=? where referencia=? and cliente=? and
                    estado='V' and franquicia='REN'",
                    array(
                        $Monedabase,
                        FData($_POST['fefectivo'][$_POST['deposito'][$i]]),
                        "'".$Referencia."'",
                        "'".$_POST['cedula']."'"
                    )
                );
                if ($bd->Error) {
                    $errores[]=$bd->MsgError.$bd->getSql();
                    $bd->RollBack();
                }
                $suma+=$Monedabase;
                if ($i>0) {
                    $depositos.=", '".$_POST['deposito'][$i]."'";
                } else {
                    $depositos="'".$_POST['deposito'][$i]."'";
                }
            }
            $fechaini=max($fechas);
            $fechaini=substr($fechaini, 0, 4).'/'.substr($fechaini, 4, 2).'/'.substr($fechaini, 6, 2);
            $fechafin=strtotime('+1 year', strtotime($fechaini)) ;
            $fechafin=date('Y-m-d', $fechafin);
            $bd->Commit();
            $suma=round($suma, 2);
        }

        if (empty($errores)) {
            /*Busqueda de los Datos de Configuracion para Obtener el Corrreo*/
            $ConConfig=$bd->dbConsultar(
                "select c.tiempoactivo dias,c.minimoinicial minimo,c.mmaximo maximo,c.minimoregistro mregistro,
                c.minimorenova mrenova,m.moneda,c.correo from configuracion as c inner join monedas as m on
                m.id=c.monedabase where c.id=1 limit 1"
            );
            if (!$bd->Error) {
                $FConfig=$ConConfig->fetch_array();
            } else {
                $msg['men']=$bd->MsgError.$bd->getSql();
            }

            $ConDepositos=$bd->dbConsultar(
                "SELECT b.banco,c.cuenta,d.referencia,d.fecha,d.monto_oficial,d.monto_base FROM
                movimientos as d inner join cuentas as c on c.id=d.cuenta inner join bancos as b
                on c.banco=b.id where d.estado='V' and d.franquicia='REN' and d.cliente=? and
                referencia in($depositos)",
                array("'".$_POST['cedula']."'")
            );
            if (!$bd->Error) {
                if ($ConDepositos->num_rows>0) {
                    $DDepositos="<table align='center' width='100%' border='1'>";
                    $DDepositos.="<tr align='center' style='font-weight:bold;'><td>Banco</td><td>Cuenta</td>".
                        "<td>Referencia</td><td>Fecha</td><td>".$FCliente['moneda']."</td><td>".
                        $FConfig['moneda']."</td></tr>";
                    $sumamoneda=0;
                    $sumamonedabase=0;
                    while ($FDepositos=$ConDepositos->fetch_array()) {
                        $DDepositos.="<tr><td align='center'>".$FDepositos['banco']."</td><td align='center'>".
                            $FDepositos['cuenta']."</td><td align='center'>".$FDepositos['referencia'].
                            "</td><td align='center'>".FUser($FDepositos['fecha'])."</td><td align='right'>".
                            $FDepositos['monto_oficial']."</td><td align='right'>".$FDepositos['monto_base'].
                            "</td></tr>";
                        $sumamoneda+=$FDepositos['monto_oficial'];
                        $sumamonedabase+=$FDepositos['monto_base'];
                    }
                    $DDepositos.="<tr><td colspan='4' align='right' style='font-weight:bold;'>Total</td>".
                        "<td align='right'>$sumamoneda</td><td align='right'>$sumamonedabase</td>".
                        "</tr></table>";
                }
            } else {
                $msg['men']=$bd->MsgError;
            }

            $Texto="<center><strong>Renovacion de la Franquicia de Participaci&oacute;n de Capitales</strong></center>";
            $Texto.="<br /><br />A continuaci&oacute;n se presenta la relaci&oacute;n del o los depositos ".
                "utilizados para renovar la Participaci&oacute;n <br /><br />".$DDepositos."<br />";

            //Creacion de Nuevo Registro de Participacion
            if (empty($msg)) {
                $bd->AutoCommit(false);
                $res=$bd->dbActualizar("update franquiciados set estado='I' where cliente=? ", array("'".$_POST['cedula']."'"));
                $res=$bd->dbInsertar(
                    "insert into franquiciados (cliente,franquicia,inicio,fin,monto,estado) values(?,?,?,?,?,'A') ",
                    array("'".$_POST['cedula']."'", 'FPC', $fechaini, $fechafin, $suma)
                );
                if ($bd->Error) {
                    $msg['men']="Error Insertando ".$bd->MsgError;
                } else {
                    //Actualizo Los Depositos Cambiando el Estatus a 'A'
                    $upd=$bd->dbActualizar(
                        "update movimientos set estado='A' where cliente=? and estado='V' and franquicia='REN'
                        and referencia in ($depositos)",
                        array("'".$_POST['cedula']."'")
                    );
                    if (!$bd->Error) {
                        if ($bd->Commit()) {
                            $msg['men']=$res. " <br /> Los Depositos han sido Autorizados, ".
                                "La participaci&oacute;n ha Sido Renovada ".$Texto;
                            $error=0;
                        } else {
                            $bd->RollBack();
                            $msg['men']="No Se Pudo Completar la Transaccion";
                        }
                    } else {
                        $msg['men']="Error".$bd->MsgError;
                    }
                }
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        $bd->dbDesconectar();
        break;
    case "BDPais":
        $bd = new dbMysql();
        $bd->dbConectar();
        $ConEstados=$bd->dbConsultar("select id,estado from estados where pais=?", array($_POST['Pais']));
        if (!$bd->Error) {
            if ($ConEstados->num_rows>0) {
                $error=0;
                $cadena="<option value='0'>Seleccione Estado</option>";
                while ($FEstados=$ConEstados->fetch_array()) {
                    $cadena.="<option value='".$FEstados['id']."'>".$FEstados['estado']."</option>";
                }
            } else {
                $cadena="<option value='0'>Disculpe No Hay Estados o Departamentos Cargados en Este Pais</option>".
                    $bd->getSql();
                $error=0;
            }
            $msg['men']=$cadena;
        } else {
            $msg['men']=$bd->MsgError;
        }
        break;

    case "Login":
        if ($va->alfa($_POST['login'], 6, 15, "Login")) {
            $errores[]=$va->error;
        }
        if ($va->alfa($_POST['clave'], 6, 15, "Clave")) {
            $errores[]=$va->error;
        }
        if (empty($errores)) {
            $bd = new dbMysql();
            $bd->dbConectar();
            $ConUsuario=$bd->dbConsultar(
                "select login,nombre from usuarios where login=? and clave=?",
                array($_POST['login'], md5($_POST['clave']))
            );
            if (!$bd->Error) {
                if ($ConUsuario->num_rows>0) {
                    $Usuario=$ConUsuario->fetch_array();
                    $_SESSION['usuario']['login']=$Usuario['login'];
                    $_SESSION['usuario']['nombre']=$Usuario['nombre'];
                    $error=0;
                } else {
                    $msg['men']="Disculpe, Login o Clave Incorrectos";
                }
            } else {
                $msg['men']=$bd->MsgError;
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="<center>ERROR CAMPOS INCORRECTOS</center><ul><li>".implode("<li>", $errores)."</ul>";
        }
        break;
    case "RetirarAsociado":
        $bd = new dbMysql();
        $bd->dbConectar();
        if ($bd->dbFunction("call RemoveAsociado(?);", array("'".$_POST['cliente']."'"))) {
            $error=0;
            $msg['men']='Cliente Retirado de la Red';
        } else {
            $msg['men']='Proceso no Realizado';
        }
        break;
    case "EliminarAsociado":
        $bd = new dbMysql();
        $bd->dbConectar();
        if ($bd->dbFunction("call RemoveAsociado(?)", array("'".$_POST['cliente']."'"))) {
            echo "{".$bd->MsgError."}".$bd->getSql();
            $bd->dbBorrar("delete from clientes where cedula=?", array("'".$_POST['cliente']."'"));
            if (!$bd->Error) {
                $error=0;
                $msg['men']='Cliente Eliminado';
            } else {
                $msg['men']=$bd->MsgError.$bd->getSql();
            }
        } else {
            $msg['men']='Proceso no Realizado';
        }
        break;
    case "VerHijos":
        $bd = new dbMysql();
        $bd->dbConectar();
        $ConPass=$bd->dbConsultar(
            "select cinvita,if(length(cescritorio)>0,cescritorio,'No Disponible') cescritorio
            from clientes where cedula=?",
            array("'".$_POST['cliente']."'")
        );
        if (!$bd->Error) {
            $pass=$ConPass->fetch_object();
        }
        $ConChilds=$bd->dbConsultar(
            "select c.cedula,concat(c.nombre,' ',c.apellido) nombres,c.telefonos,c.email,
                f.estado,ifnull(f.monto,0) monto,f.inicio,f.fin
            from clientes as c
                left join (
                    select f.* from franquiciados f inner join (
                        select cliente,max(inicio) inicio from franquiciados group by cliente
                    ) f0 on (f0.cliente=f.cliente and f0.inicio=f.inicio)
                ) as f on c.cedula=f.cliente
            where asociador=? order by f.estado desc",
            array("'".$_POST['cliente']."'")
        );
        if (!$bd->Error) {
            $table='Total '.$ConChilds->num_rows.'<div class="FormDatosModal"><div class="FormTitulo">Claves de Acceso</div><div '.
                'class="SeparadorArticuloInterno"></div>';
            $table.='<table><thead><tr><th align="center">Clave de Invitaci&oacute;n: '.$pass->cinvita.
                '</th><th align="center">Clave de Escritorio: '.$pass->cescritorio.'</th></tr></thead></table>';
            $table.='<div class="FormDatosModal"><div class="FormTitulo">Listado de Hijos Activos e Inactivos</div>'.
                '<div class="SeparadorArticuloInterno"></div>';
            $table.= '<table>';
            $table.= '<thead><tr><th align="center">Cedula</th><th>Cliente</th><th>Email</th><th align="center">'.
                'Participaci&oacute;n</th></tr></thead>';
            $table.= '<tbody>';
            if ($ConChilds->num_rows>0) {
                while ($fila=$ConChilds->fetch_assoc()) {
                    $table.= '<tr><td align="center">'.$fila['cedula'].'</td><td align="center" title="'.
                    $fila['telefonos'].'">'.$fila['nombres'].'</td><td>'.$fila['email'].
                    '</td><td align="center" title="'.FUser($fila['inicio']).'-'.FUser($fila['fin']).
                    '">'.$fila['monto'].'</td></tr>';
                }
            } else {
                $msg['men']="Disculpe, Login o Clave Incorrectos";
            }
            $error=0;
            $table.= '</tbody>';
            $table.= '</table>';
            $table.= '</div>';

            $msg['men']=$table;
        } else {
            $msg['men']=$bd->MsgError;
        }
        break;
    case "ActualizarDatos":
        $bd = new dbMysql();
        $bd->dbConectar();
        $tipo="A";
        $formato="inicial";
        $ConConfig=$bd->dbConsultar(
            "select c.tiempoactivo dias,c.minimoinicial minimo,c.mmaximo maximo,c.minimoregistro mregistro,
            c.minimorenova mrenova,m.moneda,c.correo  from configuracion as c inner join monedas as m
            on m.id=c.monedabase where c.id=1 limit 1"
        );
        if (!$bd->Error) {
            $FConfig=$ConConfig->fetch_array();
        } else {
            $msg['men']=$bd->MsgError;
        }

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
            $errores[]="Disculpe, Debes Ser mayor de Edad Para Inscribirte en la Franquicia de Participacion de Capitales".$bd->Edad(FData($_POST['fnac']));
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
            $Texto.="<strong>Dirección Electronica:</strong>".$_POST['correo']."<br />";

            $upd=$bd->dbActualizar(
                "update clientes set nombre=?,apellido=?,fnac=?,direccion=?,telefonos=?,email=? where cedula=?",
                array($_POST['nombre'], $_POST['apellido'], FData($_POST['fnac']), $_POST['direccion'], $teles, $_POST['correo'], "'".$_POST['cedula']."'")
            );
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
    default:
        $msg['men']="Seccion No Encontrada [".$_POST['idform']."]";
        break;
}
$msg['error']=$error;
//print_r($msg);
echo json_encode($msg);
