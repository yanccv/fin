<?php
   session_start();
    $_SESSION['cliente']=null;
    unset($_SESSION['cliente']);
    header("location: index.php");
?>