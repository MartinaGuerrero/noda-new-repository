<?php
require_once __DIR__ . '/../Controlador/conexion.php';
require_once __DIR__ . '/EspaciosModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit();
}

$id = $_POST['id'] ?? null;

$model = new EspaciosModel($conn);
$resultado = $model->eliminarSala($id, 'primaria');

header('Content-Type: application/json');
echo json_encode($resultado);

$conn->close();
