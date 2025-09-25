<?php session_start();

if (isset($_SESSION['usuario'])) {
    require 'vista/misReservas.viewP.php';
} else {
    header('Location: modelo/login_registrar.php');
}


?>