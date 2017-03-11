<?php
#require_once('./includes/classdb.php');
@require_once './includes/classdb.php';
@require_once './includes/funcion.php';
$bd = new dbMysql();
$bd->dbConectar();
/*
if ($bd->dbFunction("call tableLastId('facturas','id',@result)")) {
    $conIndex = $bd->dbConsultar("select @result as indice");
    if (!$bd->Error) {
        $table=$conIndex->fetch_object();
        echo '<br>Indice: '.$table->indice;
    } else {
        echo $bd->MsgError;
    }
} else {
    echo $bd->MsgError;
}
*/

echo '<pre>';
$conMov=$bd->dbConsultar("select * from movimientos order by id");
if (!$bd->Error) {
    while ($rowMov = $conMov->fetch_object()) {
        echo '<hr>';
        echo '<br>Datos del Movimiento<br>';
        print_r($rowMov);
        echo '<br>FinDatos del Movimiento<br>';
        switch ($rowMov->franquicia) {
            case 'FPC':
                $conFra=$bd->dbConsultar(
                    "select * from franquiciados where cliente=? and inicio=?",
                    array("'".$rowMov->cliente."'", $rowMov->fautoriza)
                );
                if (!$bd->Error) {
                    while ($rowFra=$conFra->fetch_object()) {
                        $descripcion='Franquicia de Participacion de Capitales,  Desde '.
                        FUser($rowFra->inicio).' Hasta '.FUser($rowFra->fin);
                        echo $descripcion;
                        echo '<br><br>rowFra FPC<br>';
                        print_r($rowFra);
                        echo '<br>Fin RowFra FPC ';
                    }
                }
                break;
            case 'REC':
                //busca la participacion antiva en las fechas en las que se hizo la recapitalizacion
                $conFra=$bd->dbConsultar(
                    "select * from franquiciados where cliente=? and ? between inicio and fin limit 1",
                    array("'".$rowMov->cliente."'", $rowMov->fautoriza)
                );
                if (!$bd->Error) {
                    $rowFra=$conFra->fetch_object();
                    if ($conFra->num_rows > 0) {
                        echo 'Si Encontro';
                    } else {
                        echo 'No Encontro'.$bd->getSql();
                    }
                    echo 'rowFra REC ';
                    print_r($rowFra);
                    echo 'Fin rowFra REC ';
                } else {
                    echo $bd->MsgError;
                }
                break;
            case 'REN':
                //busca la participacion activa en las fechas en las que se hizo la recapitalizacion
                $conFra=$bd->dbConsultar(
                    "select * from franquiciados where cliente=? and ? between inicio and fin limit 1",
                    array("'".$rowMov->cliente."'", $rowMov->fautoriza)
                );
                if (!$bd->Error) {
                    $rowFra=$conFra->fetch_object();
                    if ($conFra->num_rows > 0) {
                        echo 'Si Encontro';
                    } else {
                        echo 'No Encontro'.$bd->getSql();
                    }
                    echo 'rowFra REN';
                    print_r($rowFra);
                    echo 'Fin rowFra REN ';
                } else {
                    echo $bd->MsgError;
                }
                break;
        }

        $bd->dbInsertar(
            "insert into factura (id,cliente,fecha,fautoriza,estado) values(lastid('factura'),?,?,?,?)",
            array($rowMov->cliente, $rowMov->fecha, $rowMov->fautoriza, $rowMov->estado)
        );
        if (!$bd->Error) {
            $idFactura=$bd->getLastID();
            $bd->AutoCommit(false);
            $bd->dbInsertar(
                "insert into detafactura (id,idfactura,codigo,descripcion,costo,cantidad)
                values(lastid('detafactura'),?,?,?,?,?)",
                array($idFactura, $rowMov->franquicia, $descripcion, $rowMov->monto_base, 1)
            );
            if (!$bd->Error) {
                $bd->dbInsertar(
                    "insert into detapago
                    (id,idfactura,tipo,cuenta,referencia,monto_base,monto_oficial,fecha,fautoriza,estado)
                    values(lastid('detapago'),?,1,?,?,?,?,?,?,?)",
                    array($idFactura, $rowMov->cuenta, $rowMov->referencia, $rowMov->monto_base, $rowMov->monto_oficial,
                    $rowMov->fecha, $rowMov->fautoriza, $rowMov->estado)
                );
                if (!$bd->Error) {
                    $bd->Commit();

                    echo '<br>Datos Franquicia';
                    print_r($rowFra);
                    echo '<br>Fin rowFra';
                    if (in_array($rowMov->franquicia, array('FPC', 'REN', 'REC'))) {
                        print_r($rowFra);
                        $bd->dbInsertar(
                            "insert into detafranquicia (idfranquicia,idfactura) values(?,?)",
                            array($rowFra->id, $idFactura)
                        );
                        echo '<br>id '.$rowFra->id;
                        echo '<br>idFactura '.$idFactura;
                    }
                } else {
                    $bd->RollBack();
                    echo $bd->MsgError;
                }
            } else {
                $bd->RollBack();
                echo $bd->MsgError;
            }
        }
        die();

        /*
        $bd->dbInsertar(
            "insert into detafactura (id,idfactura,codigo,descripcion,costo,cantidad) values(?,?,?,?)",
            array(lastid('detafactura'), '{idfactura}', $rowMov->cliente, $rowMov->fecha, $rowMov->fautoriza, $rowMov->estado)
        );
        $bd->dbInsertar(
            "insert into detapago (id,idfactura,tipo,cuenta,referencia,monto_base,monto_oficial,fecha,fautoriza,estado)
             values(lastid('detapago'),?,?,?,?,?,?,?,?,?)",
            array(lastid('detafactura'), '{idfactura}', 1, $rowMov->cuenta, $rowMov->referencia, $rowMov->monto_base, $rowMov->monto_oficial, $rowMov->fecha, $rowMov->fautoriza, $rowMov->estado)
        );
        */
        #$sql=
    }
}
echo '</pre>';
