<?php
require_once '../Controlador/conexion.php';
require_once 'ReservasModel.php';

session_start();
if (!isset($_SESSION['usuario']) || ($_SESSION['cargo'] ?? '') !== 'operativo') {
    echo json_encode(["status" => "error", "message" => "No autorizado"]);
    exit();
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit();
}

$data = [
    'id_reserva' => $_POST['id_reserva'] ?? null,
    'fecha' => $_POST['fecha'] ?? null,
    'hora_inicio' => $_POST['hora_inicio'] ?? null,
    'hora_fin' => $_POST['hora_fin'] ?? null,
    'insumo' => $_POST['insumo'] ?? null,
    'sala' => $_POST['sala'] ?? null,
    'observacion' => $_POST['observacion'] ?? null,
    'limpieza' => $_POST['limpieza'] ?? null,
];

$model = new ReservasModel($conn);
$resultado = $model->editarReserva($data, 'primaria');

header('Content-Type: application/json');
echo json_encode($resultado);

$conn->close();
?>
