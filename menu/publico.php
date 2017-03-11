<?php
    //include("../includes/classdb.php");
    $db = new dbMysql();
    $db->dbConectar();
    $ConAreas=$db->dbConsultar("select id,area,msubmenu submenu from areas");
    if ($bd->Error){
        echo $bd->MsgError;
        exit();
    }    
    $Acentos=array('á','é','í','ó','ú');
    //$Letras =array('a','e','i','o','u');
    $Letras =array('á','é','í','ó','ú');
    echo "<script>";
    echo 'stm_bm(["menu408e",980,"","",0,"","",0,0,250,0,1000,1,0,0,"","730",0,0,1,2,"default","hand","",1,25],this);'.          
         'stm_bp("p0",[0,4,0,0,2,0,0,0,100,"",-2,"",-2,50,0,0,"#999999","transparent","",3,0,0,"#000000"]);';
          
    $i=0;
    while($Area=$ConAreas->fetch_array()){
        $Areax=str_replace($Acentos,$Letras,$Area['area']);
        if ($i==0)
            echo 'stm_ai("p0i0",[0,"'.$Area['area'].'","","",-1,-1,0,"index.php?op='.$Areax.'","_self","","","","",0,0,0,"","",0,0,0,1,1,"#FFFFFF",0,"#0098DB",0,"","",3,3,1,1,"#0098DB","#0098DB","#0098DB","#FFFFFF","bold 8pt Verdana","bold 8pt Verdana",0,0,"","","","",0,0,0],102,26);';
                  //stm_ai("p0i0",[0,"'.$Area['area'].'","","",-1,-1,0,"index.php?op='.$Area['area'].'","_self","","","","",0,0,0,"","",0,0,0,1,1,"#FFFFFF",0,"#0098DB",0,"","",3,3,1,1,"#0098DB","#0098DB","#0098DB","#FFFFFF","bold 8pt Verdana","bold 8pt Verdana",0,0,"","","","",0,0,0],102,26);
        else
            echo 'stm_aix("p0i1","p0i0",[0,"'.$Area['area'].'","","",-1,-1,0,"index.php?op='.$Areax.'"],102,26);';
        if ($Area['submenu']=='S'){
            $ConSubMenu=$db->dbConsultar("select id,tmenu from articulos where area=? order by orden asc",array($Area['id']));
            if ($bd->Error){
                echo $bd->MsgError;
                exit();
            }
            else{
                if ($ConSubMenu->num_rows>0){
                    echo 'stm_bpx("p1","p0",[1,4,-2,0,2,2]);';
                    while ($SubMenu=$ConSubMenu->fetch_array()){
                        echo 'stm_aix("p1i0","p0i0",[0,"'.$SubMenu['tmenu'].'","","",-1,-1,0,"index.php?op='.$Areax.'#'.$SubMenu['tmenu'].'","_self","","","","",0,0,0,"","",0,0,0,0],102,26);';
                    }
                    echo 'stm_ep();';                    
                }
            }    
 
        }

        $i++;
    }
    echo 'stm_ep();';
    echo 'stm_em();';
    echo "</script>";
    //print_r($ConAreas);
?>