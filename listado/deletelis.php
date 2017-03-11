<?php
@session_start();
include("../includes/funcion.php");
include("../includes/classvd.php");
include("../includes/classdb.php");
$bd = new dbMysql();
$bd->dbConectar();
$fail='Proceso no Completado';
switch ($_POST['tabla']) {
    case 'clasificados':
        $msg='Clasificado Eliminado';
        $conClasi=$bd->dbConsultar("select imagenes from {$_POST['tabla']} where {$_POST['campo']}=?
            and length(replace(imagenes,'|',''))>0", array($_POST['valor']));
        if (!$bd->Error && $conClasi->num_rows > 0) {
            $imagenes = $conClasi->fetch_object();
            if (!borrarImagenes($imagenes->imagenes)) {
                die(json_encode(array('success'=>false, 'msg'=>'Error Eliminando las Imagenes')));
            }
        }
        break;
    case 'articulos':
        $msg='Articulo Eliminado';
        break;
}
$bd->dbBorrar("delete from {$_POST['tabla']} where {$_POST['campo']}=?", array($_POST['valor']));
if (!$bd->Error) {
    echo json_encode(array('success'=>true, 'msg'=> $msg));
} else {
    echo json_encode(array('false'=>true, 'msg'=> $bd->MsgError));
}
