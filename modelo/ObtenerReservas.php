<?php
require_once __DIR__ . '/../Controlador/conexion.php';
require_once __DIR__ . '/ReservasModel.php';

header('Content-Type: application/json');

$centro = $_GET['centro'] ?? 'secundaria';

if (!in_array($centro, ['primaria', 'secundaria'], true)) {
    echo json_encode(["status" => "error", "message" => "Centro inválido"]);
    exit();
}

$model = new ReservasModel($conn);
$reservas = $model->obtenerReservas($centro);

echo json_encode($reservas);

$conn->close();
?>
