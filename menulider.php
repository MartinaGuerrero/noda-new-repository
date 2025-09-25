<?php session_start();

if (isset($_SESSION['usuario'])) {
    require 'vista/menuSecLider.view.php';
} else {
    header('Location: modelo/login_registrar.php');
}


?>