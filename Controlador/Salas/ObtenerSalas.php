<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../../modelo/EspaciosModel.php';

header('Content-Type: application/json');

$centro = $_GET['centro'] ?? 'secundaria';

if (!in_array($centro, ['primaria', 'secundaria'], true)) {
    echo json_encode(["status" => "error", "message" => "Centro invalido"]);
    exit();
}

$model = new EspaciosModel($conn);
$salas = $model->obtenerSalas($centro);

echo json_encode($salas);

$conn->close();
?>
