<?php
@session_start();
function fullcero($num, $max = 7)
{
    for ($i=0; $i<($max-strlen($num)); $i++) {
        $cad.="0";
    }
    return $cad.$num;
}

function FData($fecha)
{
    return substr($fecha, 6, 4).substr($fecha, 2, 4).substr($fecha, 0, 2);
}
function FUser($fecha)
{
    return substr($fecha, 8, 2)."/".substr($fecha, 5, 2)."/".substr($fecha, 0, 4);
}
function FTData($fecha)
{
    return substr($fecha, 6, 4).substr($fecha, 2, 4).substr($fecha, 0, 2).substr($fecha, 10);
}
function FTUser($fecha)
{
    return substr($fecha, 8, 2).substr($fecha, 4, 4).substr($fecha, 0, 4).substr($fecha, 10);
}

/*
function RecorrerAsociados($bd,$asociador,&$todos,&$activos,&$directos,&$nivel){
    $ConAsociados=$bd->dbConsultar("select cedula,estado from clientes where asociador=?",array($asociador));
    if ($bd->Error){
      echo $bd->MsgError;
      exit();
    }else{
      if ($ConAsociados->num_rows>0){
        if ($nivel==0)  $directos=$ConAsociados->num_rows;
        while ($FAsociado=$ConAsociados->fetch_array()){
          if ($FAsociado['estado']=='A')    $activos++;
          $todos++;
          RecorrerAsociados($bd,$FAsociado['cedula'],$todos,$activos,$directos,$nivel);
          //echo $FAsociado['cedula'].",".$FAsociado['estado']."<br>";
        }
      }
    }
}
*/
function RecorrerAsociados($bd, $asociador, &$todos, &$activos, &$directos, &$nivel, &$porc, &$monto)
{
    //$ConAsociados=$bd->dbConsultar("select cedula,estado from clientes where asociador=?",array($asociador));
    $ConAsociados=$bd->dbConsultar(
        "select c.cedula,c.estado,ROUND((f.monto/12),2) as monto from clientes as c left join franquiciados as f
        on f.cliente=c.cedula where c.asociador=?",
        array($asociador)
    );
    $directos2=0;
    if ($bd->Error) {
        echo $bd->MsgError;
        exit();
    } else {
        if ($ConAsociados->num_rows>0) {
            if ($nivel==0) {
                $directos=$ConAsociados->num_rows;
            }
            while ($FAsociado=$ConAsociados->fetch_array()) {
                if ($FAsociado['estado']=='A') {
                    $activos++;
                    $monto+=$FAsociado['monto']*(($porc/2)/100);
              //echo $FAsociado['cedula']."-".$monto."-".($porc/2)."<br />";
                }
                $todos++;
                RecorrerAsociados(
                    $bd,
                    $FAsociado['cedula'],
                    $todos,
                    $activos,
                    $directos2,
                    $nivel,
                    round(($porc/2), 2),
                    $monto
                );
                //echo $FAsociado['cedula'].",".$FAsociado['estado']."<br>";
            }
        }
    }
}

function RecorrerAsociadosPublicidad($bd, $asociador, &$nivelp, &$porcp, &$montop)
{
    //$ConAsociados=$bd->dbConsultar("select cedula,estado from clientes where asociador=?",array($asociador));
    $ConAsociados=$bd->dbConsultar(
        "select c.cedula,c.estado,c.fpm from clientes as c left join franquiciados as f on f.cliente=c.cedula
        where c.asociador=?",
        array($asociador)
    );
    if ($bd->Error) {
        echo $bd->MsgError;
        exit();
    } else {
        if ($ConAsociados->num_rows>0) {
            while ($FAsociado=$ConAsociados->fetch_array()) {
                //echo "Asociado: ".$FAsociado['cedula'].", Nivel: ".$nivelp;
                if ($FAsociado['estado']=='A' && $FAsociado['fpm']==1) {
                    $ConMonto=$bd->dbConsultar(
                        "select sum(monto_base) as monto from movimientos where movimiento='Deposito' and
                        franquicia in ('BAN','CLA') and estado='A' and month(fautoriza)=? and year(fautoriza)=?
                        and cliente=?",
                        array(date("m"), date("Y"), $FAsociado['cedula'])
                    );
                    //echo $bd->getSql()."<br />";
                    if ($bd->Error) {
                        echo $bd->MsgError;
                        exit();
                    } else {
                        $FMonto=$ConMonto->fetch_array();
                        $montop+=$FMonto['monto']*(($porcp/2)/100);
                        //echo "Asociado: ".$FAsociado['cedula'].", Pertenece a FPM ".$FMonto['monto'].
                        //"*".(($porcp/2)/100)."=".$montop."<br />";
                        //$porcp=round(($porcp/2),2);
                        RecorrerAsociadosPublicidad($bd, $FAsociado['cedula'], $nivelp, round($porcp/2, 2), $montop);
                    }
                } else {
                    //echo "Asociado: ".$FAsociado['cedula'].", No Pertenece a FPM , Porcentaje: ".
                    //$porcp.",  Monto: ".$montop."<br />";
                    RecorrerAsociadosPublicidad($bd, $FAsociado['cedula'], $nivelp, $porcp, $montop);
                }
                //echo "<br />".$FAsociado['cedula']." Nivel: ".$nivel.
                //" NewPorc: ".$newporc." Monto: ".$montop."<br />";
                //RecorrerAsociadosPublicidad($bd,$FAsociado['cedula'],$nivelp,$porcp,$montop);
            }
        }
    }
}


function PorcentajeParticipacion($bd, $Monto)
{
    $ConBaremo=$bd->dbConsultar(
        "(select * from baremos where monto>=? limit 1) UNION
        (select * from baremos where monto<=? order by monto desc limit 1)",
        array($Monto, $Monto)
    );
    if (!$bd->Error) {
        if ($ConBaremo->num_rows>0) {
            $n=0;
            while ($FilaBaremo=$ConBaremo->fetch_array()) {
                $n++;
                $Vls[$n]=$FilaBaremo['monto'];
                $Pls[$n]=$FilaBaremo['porcentaje'];
            }
            if ($n==1) {
                $x=$Pls[1];
            } else {
                $x=((($Monto-$Vls[2])*($Pls[1]-$Pls[2]))/($Vls[1]-$Vls[2]))+$Pls[2];
            }
        }
        return $x;
    } else {
        echo $bd->MsgError.$bd->getSql();
    }
}
function PorcentajePublicidad($bd)
{
    $ConPorce=$bd->dbConsultar("select pppublicidad from configuracion limit 1", array());
    if (!$bd->Error) {
        if ($ConPorce->num_rows>0) {
            $FilaPorce=$ConPorce->fetch_array();
            return $FilaPorce['pppublicidad'];
        } else {
            echo "No Se Ha Definido Porcentaje Para Publicidad";
            exit();
        }
    } else {
        echo $bd->MsgError.$bd->getSql();
    }
}

function RecorrerArbol($bd, $cedula = null)
{
    if (is_null($cedula)) {
        $ConAsociado=$bd->dbConsultar("
            select c.cedula,f.monto,m.cambio,c.fpm from clientes as c inner join franquiciados as f
            on f.cliente=c.cedula inner join paises as p on p.id=c.pais inner join monedas as m on
            m.id=p.monedaoficial where c.estado='A' and f.estado='A' and f.inicio<curdate() and f.fin>=curdate()
            and c.asociador is null
        ");
    } else {
        $ConAsociado=$bd->dbConsultar(
            "select c.cedula,f.monto,m.cambio,c.fpm from clientes as c inner join franquiciados as f
            on f.cliente=c.cedula inner join paises as p on p.id=c.pais inner join monedas as m on
            m.id=p.monedaoficial where c.estado='A' and f.estado='A' and f.inicio<curdate() and f.fin>=curdate()
            and c.asociador=?",
            array($cedula)
        );
    }
    $PorcentajePub=PorcentajePublicidad($bd);
    if (!$bd->Error) {
        if ($ConAsociado->num_rows>0) {
            while ($Asociado=$ConAsociado->fetch_array()) {
                $todos=0;
                $activos=0;
                $directos=0;
                $nivel=0;
                $nivelp=0;
                $porc=100;
                $porcp=100;
                $monto=0;
                $montop=0;
                RecorrerAsociados($bd, $Asociado['cedula'], $todos, $activos, $directos, $nivel, $porc, $monto);
                RecorrerAsociadosPublicidad($bd, $Asociado['cedula'], $nivelp, $porcp, $montop);
                RecorrerArbol($bd, $Asociado['cedula']);
                $Porcentaje=round(PorcentajeParticipacion($bd, $Asociado['monto']), 2);
                $Monto=$monto*($Porcentaje/100);
                $MontoP=$montop*($PorcentajePub/100);

                if ($Asociado['fpm']==1) {
                    $MontoOficial=round(($Monto*$Asociado['cambio'])+($MontoP*$Asociado['cambio']), 2);
                    $MontoBase=round($Monto + $MontoP, 2);
                } else {
                    $MontoOficial=round($Monto*$Asociado['cambio'], 2);
                    $MontoBase=round($Monto, 2);
                }

                $bd->dbInsertar(
                    "insert into movimientos (id,cliente,referencia,franquicia,movimiento,fecha,fautoriza,
                    monto_oficial,monto_base,estado) values(lastid('movimientos'),?,lastmv('Liquidez','".
                    $Asociado['cedula']."'),'FCG','Liquidez',curdate(),curdate(),?,?,'A')",
                    array($Asociado['cedula'], $MontoOficial, $MontoBase)
                );
                if ($bd->Error) {
                    $bd->RollBack();
                    echo $bd->MsgError.$bd->getSql();
                }
                /*
               if ($Asociado['fpm']==1){
                  $bd->dbInsertar("insert into movimientos (id,cliente,referencia,franquicia,movimiento,fecha,fautoriza,
                  monto_oficial,monto_base,estado) values(lastid('movimientos'),?,lastmv('Liquidez','".
                  $Asociado['cedula']."'),'FPM','Liquidez',curdate(),curdate(),?,?,'A')",
                  array($Asociado['cedula'],$MontoP*$Asociado['cambio'],$MontoP));

                  echo $bd->getSql()."<br />";
                  if ($bd->Error){
                     $bd->RollBack();
                     echo $bd->MsgError."x".$bd->getSql();
                  }
               }
               */
            }
        }
        return 0;
    } else {
        return $bd->MsgError."0";
    }
}


function Saldo($bd, $cedula)
{
    $ConSaldo=$bd->dbConsultar(
        "select f.cliente,(select sum(ml.monto_base) from movimientos as ml where ml.cliente=f.cliente and
        ml.movimiento='Liquidez' and ml.fautoriza between f.inicio and f.fin) Liquidez,(select sum(ml.monto_base)
        from movimientos as ml where ml.cliente=f.cliente and ml.movimiento='Retiro' and
        ml.fautoriza between f.inicio and f.fin) Retiros from franquiciados as f inner join movimientos as m
        on m.cliente=f.cliente where f.cliente=? GROUP BY f.cliente",
        array($cedula)
    );
    if (!$bd->Error) {
        $FSaldo=$ConSaldo->fetch_array();
        return round((double) ($FSaldo['Liquidez']-$FSaldo['Retiros']), 2);
    } else {
        echo $bd->MsgError;
        exit();
    }
}

function SDiferido($bd, $cedula)
{
    $ConDiferido=$bd->dbConsultar(
        "select sum(m.monto_base) mbase from movimientos as m where fautoriza is null and m.cliente=? and estado='P'",
        array($cedula)
    );
    if (!$bd->Error) {
        $Diferido=$ConDiferido->fetch_array();
        return $Diferido['mbase'];
    } else {
        echo $bd->MsgError;
    }
}

function CDiferido($bd, $cedula)
{
    $ConDiferido=$bd->dbConsultar(
        "select sum(m.monto_oficial) moficial, sum(m.monto_base) mbase from movimientos as m where fautoriza is null
        and m.cliente=? and estado='R' and franquicia='REC'",
        array($cedula)
    );
    //echo $bd->getSql();
    if (!$bd->Error) {
        return $ConDiferido->fetch_array();
        //return $Diferido['mbase'];
    } else {
        echo $bd->MsgError;
    }
}

function RDiferido($bd, $cedula)
{
    $ConDiferido=$bd->dbConsultar(
        "select sum(m.monto_oficial) moficial, sum(m.monto_base) mbase from movimientos as m where fautoriza is null
        and m.cliente=? and estado='V' and franquicia='REN'",
        array($cedula)
    );
    //echo $bd->getSql();
    if (!$bd->Error) {
        return $ConDiferido->fetch_array();
        //return $Diferido['mbase'];
    } else {
        echo $bd->MsgError;
    }
}
function CambioMonetario($bd, $moneda, $fecha)
{
    $ConCambio=$bd->dbConsultar(
        "select cambio from hmonedas where (? between desde and hasta) or (?>=desde and hasta is null) and id=? ",
        array(FData($fecha), FData($fecha), $moneda)
    );
    if (!$bd->Error) {
        if ($ConCambio->num_rows>0) {
            $Cambio=$ConCambio->fetch_array();
            return $Cambio['cambio'];
        } else {
            return 0;
        }
    } else {
        echo $bd->MsgError;
    }
}

function vp_img($roriginal, $folder, $nombre, $tipo)
{
    $folder=$folder."/vp/";
    $size=getimagesize($roriginal);
    $max=100;
    if ($size[0]>100) {
        $max=$size[0];
    } elseif ($size[1]>$max) {
        $max=$size[1];
    }

    $maxvp=(100*100)/$max;
    //echo $maxvp;
    $vpsize[0]=(int) $size[0]*($maxvp/100);
    $vpsize[1]=(int) $size[1]*($maxvp/100);
    if (!is_dir($folder)) {
        mkdir($folder, 0777);
    }
    switch ($tipo) {
        case "jpg":
            $coriginal = imagecreatefromjpeg($roriginal);
            $ivp = imagecreatetruecolor($vpsize[0], $vpsize[1]);
            imagecopyresampled($ivp, $coriginal, 0, 0, 0, 0, $vpsize[0], $vpsize[1], $size[0], $size[1]);
            imagejpeg($ivp, $folder.$nombre, 90);
            //echo "jpg";
            break;
        case "png":
            $coriginal = @imagecreatefrompng($roriginal);
            $ivp = @imagecreatetruecolor($vpsize[0], $vpsize[1]);
            @imagecopyresampled($ivp, $coriginal, 0, 0, 0, 0, $vpsize[0], $vpsize[1], $size[0], $size[1]);
            @imagepng($ivp, $folder.$nombre, 9);

            //echo "Png";
            break;
        case "gif":
            $coriginal = @imagecreatefromgif($roriginal);
            $ivp = @imagecreatetruecolor($vpsize[0], $vpsize[1]);
            @imagecopyresampled($ivp, $coriginal, 0, 0, 0, 0, $vpsize[0], $vpsize[1], $size[0], $size[1]);
            @imagegif($ivp, $folder.$nombre);

            //echo "gif";
            break;
    }
}

function obtener_carpeta($original)
{
    if (is_null($original)) {
        return uniqid(date("Ymdhis"))."/";
    }
    $pos=stripos($original, "|");
    if (empty($pos)) {
        $pos=strlen($original);
    }
    $pos1=strrpos(substr($original, 0, $pos), "/");
    $pos2=strrpos(substr($original, 0, $pos1), "/");
    $folder=substr($original, $pos2+1, ($pos1-$pos2));
    return $folder;
}
function borrarImagenes($imagenes, $folder)
{
    $imagenes = array_filter(explode('|', $imagenes));
    foreach ($imagenes as $key => $rutaimagen) {
        $rutavpimagen = preg_replace('/(\/)([A-Za-z0-9\-\.\s\_]+\.[A-Za-z0-9]{2,4})/', '$1vp$1$2', $rutaimagen);
        if (is_file($rutaimagen)) {
            @unlink($rutaimagen);
        }
        if (is_file($rutavpimagen)) {
            @unlink($rutavpimagen);
        }
    }
    $folder = '../clasificados/'.obtener_carpeta($rutaimagen);
    $foldervp=$folder.'vp';
    if (is_dir($folder)) {
        @rmdir($foldervp);
        if (!@rmdir($folder)) {
            return false;
        }
    }
    return true;
}
