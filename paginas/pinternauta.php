<?php
session_start();
include("../includes/funcion.php");
include("../includes/classvd.php");
include("../includes/classdb.php");
include("../includes/fmails.php");

$error=1;
$va= new Validar();
$bd = new dbMysql();
$bd->dbConectar();
switch ($_POST['idform']) {
    case "BDPais":
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
            }
            $msg['men']=$cadena;
        } else {
            $msg['men']=$bd->MsgError;
        }
        break;
    case "Correo":
        if ($va->letras($_POST['Nombre'], 3, 50, "Nombre")) {
            $errores[]=$va->error;
        }
        if ($va->longitud($_POST['Direccion'], 15, 100, "Direccion", "Caracteres")) {
            $errores[]=$va->error;
        }
        if ($va->telefono($_POST['Telefono'], 12, 12, "Telefono")) {
            $errores[]=$va->error;
        }
        if ($va->email($_POST['Email'], 15, 70, "Email")) {
            $errores[]=$va->error;
        }
        if ($va->longitud($_POST['Mensaje'], 15, 100, "Mensaje", "Caracteres")) {
            $errores[]=$va->error;
        }
        if (empty($errores)) {
            sleep(3);
            $Origen=$_POST['correo'];
            $Destino=$_POST['AreaCorreo']."@fondointeractivodenegocios.com.ve";
            $Nombre=$_POST['nombre']." ".$_POST['apellido'];
            $Cabecera=Mail_Cabecera($Origen, $Destino, $Nombre);
            $Cuerpo ="Nombre: ".$_POST['Nombre']."<br />";
            $Cuerpo.="Direcci�n: ".$_POST['Direccion']."<br />";
            $Cuerpo.="Tel�fono: ".$_POST['Telefono']."<br />";
            $Cuerpo.="Email: ".$_POST['Email']."<br />";
            $Cuerpo.="Asunto: ".$_POST['Mensaje']."<br />";
            if (@mail("fondointeractivodenegocios@gmail.com", "Contacto Web", $Cuerpo, $Cabecera)) {
                $error=0;
            } else {
                $msg['men']="Disculpe, Correo No Enviado";
            }
        } else {
            $msg['titulo']="Errores Por Corregir Antes de Continuar";
            $msg['men']="OBSERVACIONES\n".implode("\n", $errores)."";
        }
        break;
    case "RClasificado":
        if ($va->seleccion($_POST['categoria'], "Categoria")) {
            $errores[]=$va->error;
        }
        if ($va->longitud($_POST['titulo'], 5, 60, "Titulo", "Caracteres")) {
            $errores[]=$va->error;
        }
        if ($va->longitud($_POST['descripcion'], 5, 1200, "Descripci&oacute;n", "Caracteres")) {
            $errores[]=$va->error;
        }
        if ($va->longitud($_POST['contacto'], 5, 150, "Contacto", "Caracteres")) {
            $errores[]=$va->error;
        }
        if ($va->seleccion($_POST['CEstado'], "Estado")) {
            $errores[]=$va->error;
        }

        if ($va->alfa($_POST['cedula'], 6, 20, "Cedula")) {
            $errores[]=$va->error;
        }
        if ($va->letras($_POST['nombre'], 3, 35, "Nombre")) {
            $errores[]=$va->error;
        }
        if ($va->letras($_POST['apellido'], 3, 35, "Apellido")) {
            $errores[]=$va->error;
        }
        if ($va->email($_POST['email'], 15, 70, "Email")) {
            $errores[]=$va->error;
        }
        if ($va->telefono($_POST['phone'], 12, 12, "Tel&eacute;fono")) {
            $errores[]=$va->error;
        }


        if (empty($errores)) {
            $add2=$bd->dbInsertar(
                "insert into clasificados
                (id,categoria,cliente,idpais,idestado,titulo,descripcion,direccion,dias,fregistro,factivo,estado)
                values (lastid('clasificados'),?,'4628204',?,?,?,?,?,?,curdate(),curdate(),'A')",
                array($_POST['categoria'], $_POST['CPais2'], $_POST['CEstado'], $_POST['titulo'],
                $_POST['descripcion'], $_POST['contacto'], 17)
            );
            if (!$bd->Error) {
                $add2=$bd->dbInsertar(
                    "replace into clientesfree (cedula,nombre,apellido,telefono,email) values (?, ?, ?, ?, ?)",
                    array($_POST['cedula'], $_POST['nombre'], $_POST['apellido'], $_POST['phone'], $_POST['email'])
                );
                $Origen=$_POST['email'];
                $Destino="fondointeractivodenegocios@gmail.com";
                $Nombre=$_POST['nombre']." ".$_POST['apellido'];
                $Cabecera=Mail_Cabecera($Origen, $Destino, $Nombre, 'Fondo Interactivo de Negocios');
                $Cuerpo ="ID: ".$_POST['cedula']."<br />";
                $Cuerpo.="Nombre: ".$_POST['nombre']."<br />";
                $Cuerpo.="Apellido: ".$_POST['apellido']."<br />";
                $Cuerpo.="Teléfono: ".$_POST['telefono']."<br />";
                $Cuerpo.="Email: ".$_POST['email']."<br />";
                if (@mail("Fondo Interactivo de Negocios <fondointeractivodenegocios@gmail.com>", "Clasificado Free", $Cuerpo, $Cabecera)) {
                    $msg['men']="<br>Se ha enviado un correo a {$Origen}, si no lo visualizas <br>en tu bandeja".
                    " de entrada revisa la carpeta de correo no deseado";
                }
                $error=0;
                $msg['men']="Clasificado Registrado Correctamente".$msg['men'];
            } else {
                $msg['men']=$bd->MsgError.$bd->getSql();
            }
        } else {
            $msg['men']="OBSERVACIONES<ul><li>".implode("<li>", $errores)."</ul>";
        }
        break;
    default:
        $msg['men']="Seccion No Encontrada [".$_POST['idform']."]";
        break;
}
$msg['error']=$error;
echo json_encode($msg);
