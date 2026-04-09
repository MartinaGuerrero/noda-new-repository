<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../../modelo/UsuariosModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? null;

    $model = new UsuariosModel($conn);
    $resultado = $model->eliminarUsuario($email);

    echo json_encode($resultado);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metodo de solicitud no valido.']);
}

$conn->close();
?>
