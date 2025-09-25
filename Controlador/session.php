<?php
session_start();
header('Content-Type: application/json');

// Verificar si las variables de sesión están configuradas
$cargo = isset($_SESSION['cargo']) ? $_SESSION['cargo'] : null;
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

// Crear el array de respuesta
$response = array('cargo' => $cargo, 'usuario' => $usuario);

// Convertir a JSON y enviarlo
echo json_encode($response);
?>
