<?php
require_once __DIR__ . '/../Controlador/conexion.php';
require_once __DIR__ . '/UsuariosModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nombre' => $_POST['nombre'] ?? '',
        'apellido' => $_POST['apellido'] ?? '',
        'cargo' => $_POST['cargo'] ?? '',
        'email' => $_POST['email'] ?? '',
        'emailOriginal' => $_POST['emailOriginal'] ?? ''
    ];

    $model = new UsuariosModel($conn);
    $resultado = $model->editarUsuarioAdmin($data);

    echo json_encode($resultado);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no válido']);
}

$conn->close();
