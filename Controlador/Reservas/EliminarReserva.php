<?php
session_start();

require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../../modelo/ReservasModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario']) || ($_SESSION['cargo'] ?? '') !== 'operativo') {
    echo json_encode(["status" => "error", "message" => "No autorizado"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Metodo no permitido"]);
    exit();
}

$id = $_POST['id_reserva'] ?? $_POST['id'] ?? null;

$model = new ReservasModel($conn);
$resultado = $model->eliminarReserva($id);

echo json_encode($resultado);

$conn->close();
?>
