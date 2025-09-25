<?php 
    session_start();
    session_destroy();
    $_SESSION = array();

    header('Location: modelo/login_registrar.php');
die();
?>