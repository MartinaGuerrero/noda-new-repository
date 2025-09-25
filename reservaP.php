<?php session_start();

if (isset($_SESSION['usuario'])) {
    require 'vista/menuReservas.viewP.php';
} else {
    header('Location: modelo/login_registrar.php');
}


?>