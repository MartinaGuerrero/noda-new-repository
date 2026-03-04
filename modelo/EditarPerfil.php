<?php
session_start();
require_once __DIR__ . '/../Controlador/conexion.php';
require_once __DIR__ . '/UsuariosModel.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_SESSION['usuario'];

    $data = [
        'nombre' => $_POST['nombre'] ?? '',
        'apellido' => $_POST['apellido'] ?? '',
        'password' => $_POST['password'] ?? ''
    ];

    $model = new UsuariosModel($conn);
    $resultado = $model->editarPerfilPropio($email, $data);

    // Mantener respuesta del original (success/false)
    if ($resultado['success']) {
        $_SESSION['nombre'] = $data['nombre'];
        $_SESSION['apellido'] = $data['apellido'];
    }

    echo json_encode($resultado);
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido']);
}

$conn->close();

