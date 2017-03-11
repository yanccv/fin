<?php
    session_start();
	include("check.php");
	checkar($_SERVER['SCRIPT_NAME'],$_SESSION['usuario']['nivel'],$_SERVER['HTTP_REFERER']);    
    
    
    include("../class/listados.php");

            $lis=new LISTA();
            $tipolis=$_POST['fid'];
            $maxpage= (int) $_POST['nro'];
            $noletter=array("'","#",";");
            $nwletter=array("","","");
            include("../listado/php/parametros.php");    
			//echo substr($_POST['valor'],3,2).substr($_POST['valor'],0,2).substr($_POST['valor'],6,4);  
			if (@checkdate(substr($_POST['valor'],3,2),substr($_POST['valor'],0,2),substr($_POST['valor'],6,4)) )
			{
				if (!empty($lis->LFiltro)) $lis->LFiltro.=" and ";
				if ((substr($_POST['valor'],2,1)=="/" || substr($_POST['valor'],2,1)=="-") && (substr($_POST['valor'],5,1)=="/" || substr($_POST['valor'],2,1)=="-")){
					$_POST['valor']=FData($_POST['valor']);
				    $lis->LFiltro.=" ".DecodeCombo($_POST['campotb'])." = '".str_replace($noletter,$nwletter,$_POST['valor'])."'";				
				}else{
	                $lis->LFiltro.=" ".DecodeCombo($_POST['campotb'])." like '%".str_replace($noletter,$nwletter,$_POST['valor'])."%'";
				}
			}else if (!empty($_POST['valor']) && (empty($lis->LFiltro)))
			{
                $lis->LFiltro.=" ".DecodeCombo($_POST['campotb'])." like '%".str_replace($noletter,$nwletter,$_POST['valor'])."%'";
            }elseif (!empty($_POST['valor']) && (!empty($lis->LFiltro)))
            {
                $lis->LFiltro.=" and ".DecodeCombo($_POST['campotb'])." like '%".str_replace($noletter,$nwletter,$_POST['valor'])."%'";
            }
			//echo $lis->LFiltro;
            if (empty($maxpage))    $maxpage=30;
            if (!empty($_POST['pages'])) $limit=array('0'=>(int) $_POST['pages'],'1'=>$maxpage);      
            if (!empty($_POST['ordenar']))  $orden=$_POST['ordenar'];            
            
	        $lis->Listados($campos,$titulos,$tabla,$filtro,$orden,$limit);		
			//echo $lis->getSql();	
	        $lis->paginacion($tabla,$limit);
?>