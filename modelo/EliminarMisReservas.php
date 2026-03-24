<?php
session_start();

require_once __DIR__ . '/../Controlador/conexion.php';
require_once __DIR__ . '/ReservasModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit();
}

if (!isset($_SESSION['usuario'])) {
    echo json_encode(["status" => "error", "message" => "Usuario no logueado"]);
    exit();
}

$id = $_POST['id'] ?? $_POST['id_reserva'] ?? null;

$model = new ReservasModel($conn);
$resultado = $model->eliminarReserva($id);

echo json_encode($resultado);

$conn->close();
?>
