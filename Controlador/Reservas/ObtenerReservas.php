<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../../modelo/ReservasModel.php';

header('Content-Type: application/json');

$centro = $_GET['centro'] ?? 'secundaria';

if (!in_array($centro, ['primaria', 'secundaria'], true)) {
    echo json_encode(["status" => "error", "message" => "Centro invalido"]);
    exit();
}

$model = new ReservasModel($conn);
$reservas = $model->obtenerReservas($centro);

echo json_encode($reservas);

$conn->close();
?>
