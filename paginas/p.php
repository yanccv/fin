<?php

include ("../includes/classdb.php");
include ("../includes/funcion.php");
$bd= new dbMysql();
$bd->dbConectar();
/*
$cedcliente=4628204;

$cadena="../clasificados/2014071901072653caacd64f7b0/1.png|../clasificados/2014071901072653caacd64f7b0/2.png|../clasificados/2014071901072653caacd64f7b0/3.png|../clasificados/2014071901072653caacd64f7b0/4.png|../clasificados/2014071901072653caacd64f7b0/5.png||||";


function obtener_carpeta($original){
    if (is_null($original)){
        return (date("Ymdhis"))."/";
    }
    $pos=stripos($original,"|");
    if (empty($pos)){
        $pos=strlen($original);
    }
    $pos1=strrpos(substr($original,0,$pos),"/");
    $pos2=strrpos(substr($original,0,$pos1),"/");
    $folder=substr($original,$pos2+1,($pos1-$pos2));    
    return $folder;    
}

echo obtener_carpeta($cadena);
*/
//echo $folder;
//vp_img($original,"../clasificados/2014070410393953b76c73d29ec","1.png","png");








/*
$ConAsociados=$bd->dbConsultar("select cedula,asociador,estado from clientes where asociador=?",array($cedcliente));
if ($bd->Error){
  echo $bd->MsgError;
  exit();
}else{
  if ($ConAsociados->num_rows>0){
    while ($FAsociado=$ConAsociados->fetch_array()){
      echo $FAsociado['cedula'].",".$FAsociado['estado']."<br>";
    }
  }
}
*/


/* Funcion de Recorrido de los Asociados */
/*
    function PorcentajeParticipacion($bd,$Monto){
               $ConBaremo=$bd->dbConsultar("(select * from baremos where monto>=? limit 1) UNION (select * from baremos where monto<=? order by monto desc limit 1)",array($Monto,$Monto));
               if (!$bd->Error){
                    if ($ConBaremo->num_rows>0){
                        $n=0;
                        while ($FilaBaremo=$ConBaremo->fetch_array()){
                            $n++;
                            $Vls[$n]=$FilaBaremo['monto'];
                            $Pls[$n]=$FilaBaremo['porcentaje'];
                        }
                        if ($n==1)
                            $x=$Pls[1];
                        else
                            $x=((($Monto-$Vls[2])*($Pls[1]-$Pls[2]))/($Vls[1]-$Vls[2]))+$Pls[2];
                    }
                    return $x;
                    //$Afiliador=$ConAfiliador->fetch_array();
               }else{
                echo $bd->MsgError;
               }
    }
*/    


/*
function RecorrerAsociados($bd,$asociador,&$todos,&$activos,&$directos,&$nivel,&$porc,&$monto){
    //$ConAsociados=$bd->dbConsultar("select cedula,estado from clientes where asociador=?",array($asociador));
    $ConAsociados=$bd->dbConsultar("select c.cedula,c.estado,ROUND((f.monto/12),2) as monto from clientes as c left join franquiciados as f on f.cliente=c.cedula where c.asociador=?",array($asociador));
    if ($bd->Error){
      echo $bd->MsgError;
      exit();
    }else{
      if ($ConAsociados->num_rows>0){
        if ($nivel==0)  $directos=$ConAsociados->num_rows;
        while ($FAsociado=$ConAsociados->fetch_array()){
          if ($FAsociado['estado']=='A'){
            $activos++;
            $monto+=round(($FAsociado['monto']*(($porc/2)/100)),2);
            echo $FAsociado['cedula']."-".$monto."-".($porc/2)."<br />";
          }    

          $todos++;
          RecorrerAsociados($bd,$FAsociado['cedula'],$todos,$activos,$directos,$nivel,round(($porc/2),2),$monto);
          //echo $FAsociado['cedula'].",".$FAsociado['estado']."<br>";
        }
      }
    }
}

$todos=0;
$activos=0;
$directos=0;
$nivel=0;
$porc=100;
$monto=0;

RecorrerAsociados($bd,$cedcliente,$todos,$activos,$directos,$nivel,$porc,$monto);

echo "Todos: ".$todos."<br>";
echo "Activos: ".$activos."<br>";
echo "Directos: ".$directos."<br>";
echo "Monto: ".$monto."<br>";

echo "Fin Con Cedula Definida<br /><br />";
*/


/*
function RecorrerArbol($bd,$cedula=null){
   if (is_null($cedula)){
      $ConAsociado=$bd->dbConsultar("select c.cedula,f.monto,m.cambio from clientes as c inner join franquiciados as f on f.cliente=c.cedula inner join paises as p on p.id=c.pais inner join monedas as m on m.id=p.monedaoficial where c.estado='A' and c.asociador is null");
   }else{
      $ConAsociado=$bd->dbConsultar("select c.cedula,f.monto,m.cambio from clientes as c inner join franquiciados as f on f.cliente=c.cedula inner join paises as p on p.id=c.pais inner join monedas as m on m.id=p.monedaoficial where c.estado='A' and c.asociador=?",array($cedula));   
   }
   
   if (!$bd->Error){
      if ($ConAsociado->num_rows>0){
         
         while ($Asociado=$ConAsociado->fetch_array()){
            $todos=0;
            $activos=0;
            $directos=0;
            $nivel=0;
            $porc=100;
            $monto=0;

            RecorrerAsociados($bd,$Asociado['cedula'],$todos,$activos,$directos,$nivel,$porc,$monto);
            RecorrerArbol($bd,$Asociado['cedula']);
            $Porcentaje=round(PorcentajeParticipacion($bd,$Asociado['monto']),2);
            $Monto=round($monto*($Porcentaje/100),2);
            $bd->dbInsertar("insert into movimientos (id,cliente,franquicia,movimiento,fecha,monto_oficial,monto_base,estado) values(lastid('movimientos'),?,'FCG','Liquidez',curdate(),?,?,'E')",array($Asociado['cedula'],$Monto*$Asociado['cambio'],$Monto));
            if ($bd->Error)
               $bd->RollBack();
            //echo "Asociador ".$cedula.", Cedula: ".$Asociado['cedula'].", % ".$Porcentaje.", Monto: ".round($monto*($Porcentaje/100),2)."<br />";
         }
      }
   }else{
      echo $bd->MsgError;
   }      
}

*/
/*
$bd->AutoCommit(false);
RecorrerArbol($bd);
$bd->Commit();
*/
/*
$ConClientes=$bd->dbConsultar("select cedula from clientes where estado='A' and asociador is null limit 1");
if (!$bd->Error){
   $Clientes=$ConClientes->fetch_array();
   //echo "Cedula: ".$Clientes['cedula'];
   RecorrerArbol($bd,$Clientes['cedula']);
}
else{
   echo $bd->MsgError;
}
*/
/*
function generaralfa($longitud){
    $Chars=array(array(0,1,2,3,4,5,6,7,8,9),array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'));
    for ($i=0;$i<$longitud;$i++){
        $tipo=rand(0,1);
        switch($tipo){
            case 0: $max=9; break;
            case 1: $max=25;    break;    
        }
        $id=rand(0,$max);
        $cadena.=$Chars[$tipo][$id];
    }
    return $cadena;
}

echo generaralfa(8);



    $Acentos=array('á','é','í','ó','ú');
    $Letras =array('a','e','i','o','u');
    $Areax=str_replace($Acentos,$Letras,"Conexión");
    echo $Areax;
*/
echo RecorrerArbol($bd);
echo "Termino";

?>