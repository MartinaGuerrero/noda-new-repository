<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../../modelo/ReservasModel.php';

header('Content-Type: application/json');

$fecha = $_GET['date'] ?? '';
$idSala = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$model = new ReservasModel($conn);
$reservas = $model->obtenerReservasPorFechaSala($fecha, $idSala);

echo json_encode($reservas);

$conn->close();
?>
