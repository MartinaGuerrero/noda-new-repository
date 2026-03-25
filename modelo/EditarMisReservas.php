<?php
session_start();

require_once __DIR__ . '/../Controlador/conexion.php';
require_once __DIR__ . '/ReservasModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(["status" => "error", "message" => "Usuario no logueado"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Metodo no permitido"]);
    exit();
}

$centro = $_POST['centro'] ?? 'secundaria';

if (!in_array($centro, ['primaria', 'secundaria'], true)) {
    echo json_encode(["status" => "error", "message" => "Centro invalido"]);
    exit();
}

$data = [
    'id_reserva' => $_POST['id_reserva'] ?? null,
    'fecha' => $_POST['fecha'] ?? null,
    'hora_inicio' => $_POST['hora_inicio'] ?? null,
    'hora_fin' => $_POST['hora_fin'] ?? null,
    'insumo' => $_POST['insumo'] ?? null,
    'sala' => $_POST['sala'] ?? null,
    'observacion' => $_POST['observacion'] ?? ($_POST['observaciones'] ?? null),
    'limpieza' => $_POST['limpieza'] ?? null,
];

$model = new ReservasModel($conn);
$resultado = $model->editarReserva($data, $centro);

echo json_encode($resultado);

$conn->close();
?>
