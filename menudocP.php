<?php session_start();

if (isset($_SESSION['usuario'])) {
    require 'vista/menuSecDoc.viewP.php';
} else {
    header('Location: modelo/login_registrar.php');
}


?>