<?php
// controlador/MenuOperativoController.php
session_start();

// Verifica si la sesión del usuario está iniciada
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php');
    exit;
}

// Podés cargar información del usuario desde un modelo si es necesario
$usuario = $_SESSION['usuario'];

// Cargar la vista
require_once '../vista/menuSecOperativo.view.php';
