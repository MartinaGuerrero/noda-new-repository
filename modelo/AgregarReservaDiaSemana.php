<?php
require_once __DIR__ . '/../Controlador/conexion.php';
require_once __DIR__ . '/ReservasModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Método de solicitud no permitido"]);
    exit();
}

$data = [
    'fecha' => $_POST['fecha'] ?? null,
    'horaI' => $_POST['horaI'] ?? null,
    'horaF' => $_POST['horaF'] ?? null,
    'cantidad' => $_POST['cantidad'] ?? null,
    'email' => $_POST['email'] ?? null,
    'sala' => $_POST['sala'] ?? null,
];

$model = new ReservasModel($conn);
$resultado = $model->crearReservaDiaSemana($data);

echo json_encode($resultado);

$conn->close();
?>
