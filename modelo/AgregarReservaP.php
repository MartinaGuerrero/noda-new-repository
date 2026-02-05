<?php
require_once '../Controlador/conexion.php';
require_once 'ReservasModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
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
$resultado = $model->crearReserva($data, 'primaria');

header('Content-Type: application/json');
echo json_encode($resultado);

$conn->close();
?>

