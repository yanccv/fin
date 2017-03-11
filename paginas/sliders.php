<?php
    $IdArea=$_GET['op'];  
    $ConArea=$bd->dbConsultar("select * from areas where area=?",array($_GET['op']));
    if (!$bd->Error){
        $Area=$ConArea->fetch_array();
        $banners=explode(":",$Area['banners']);
    }
?>
<div id="slider">
    <ul id="slider1">
    <?php
        for ($i=0;$i<count($banners);$i++){
            if ((!empty($banners[$i])) && is_file($banners[$i]))
                echo "<li><img src='".$banners[$i]."' alt='".$IdArea."'></li>\n";
        }
    ?>
    </ul> 
</div>