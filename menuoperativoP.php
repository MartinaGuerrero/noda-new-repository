<?php session_start();

if (isset($_SESSION['usuario'])) {
    require 'vista/menuSecOperativo.viewP.php';
} else {
    header('Location: modelo/login_registrar.php');
}


?>