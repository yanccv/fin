<?php

$Banners=":../files/1/Banner/1.jpg:../files/1/Banner/2.jpg:../files/1/Banner/3.jpg:../files/1/Banner/4.jpg:../files/1/Banner/5.jpg";
$imagen="../files/1/Banner/4.jpg";

$newBanner=str_ireplace(":".$imagen,"",$Banners);
echo $Banners."<br />";
echo $imagen."<br />";
echo $newBanner."<br />";

//echo strripos($imagen,"/");

echo substr($imagen,strripos($imagen,"/")+1);


?>