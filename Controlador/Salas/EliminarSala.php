<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../../modelo/EspaciosModel.php';

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

$id = $_POST['id'] ?? null;

$model = new EspaciosModel($conn);
$resultado = $model->eliminarSala($id, $centro);

echo json_encode($resultado);

$conn->close();
?>
