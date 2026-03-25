<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../../modelo/ReservasModel.php';

header('Content-Type: application/json');

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
    'fecha' => $_POST['fecha'] ?? null,
    'horaI' => $_POST['horaI'] ?? null,
    'horaF' => $_POST['horaF'] ?? null,
    'insumos' => $_POST['insumos'] ?? null,
    'email' => $_POST['email'] ?? null,
    'sala' => $_POST['sala'] ?? null,
    'observaciones' => $_POST['observaciones'] ?? null,
    'limpieza' => $_POST['limpieza'] ?? null,
];

$model = new ReservasModel($conn);
$resultado = $model->crearReserva($data, $centro);

echo json_encode($resultado);

$conn->close();
?>
