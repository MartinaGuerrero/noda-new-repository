<?php
require_once __DIR__ . '/../Controlador/conexion.php';
require_once __DIR__ . '/UsuariosModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit();
}

$data = [
    'nombre' => $_POST['nombre'] ?? '',
    'apellido' => $_POST['apellido'] ?? '',
    'email' => $_POST['email'] ?? '',
    'cargo' => $_POST['cargo'] ?? ''
];

$model = new UsuariosModel($conn);
$resultado = $model->crearUsuario($data);

header('Content-Type: application/json');
echo json_encode($resultado);

$conn->close();
