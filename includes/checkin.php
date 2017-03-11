<?php
session_start();
function CheckCliente($bd)
{
    $CheckIn=0;
    $ConExist=$bd->dbConsultar(
        "select cedula from clientes where cedula=? and estado='A' limit 1",
        array($_SESSION['cliente']['cedula'])
    );
    if (!$bd->Error) {
        if ($ConExist->num_rows>0) {
            $CheckIn=1;
        }
    } else {
        echo "Disculpe, Base de Datos No Accesible";
        exit();
        return;
    }
    return $CheckIn;
}

function CheckOrigen()
{
    $dominio=$_SERVER['HTTP_HOST'];
    $viene=substr($_SERVER['HTTP_REFERER'], 0, strrpos($_SERVER['HTTP_REFERER'], "/"));
    $viene=substr($viene, 0, strrpos($viene, "/"));
    $cdefault="{$_SERVER['REQUEST_SCHEME']}://{$dominio}";
    $CheckIn=0;
    if (strcmp($viene, $cdefault)==0) {
        $CheckIn=1;
    }
    return $CheckIn;
}
