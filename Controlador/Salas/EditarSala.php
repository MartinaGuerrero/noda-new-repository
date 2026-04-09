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

$recursos = isset($_POST['recursos']) ? explode(',', $_POST['recursos']) : [];

$imagen = $_FILES['imagen']['name'] ?? null;
$rutaImagen = $imagen ? 'vista/img/' . basename($imagen) : null;

if ($imagen && !move_uploaded_file($_FILES['imagen']['tmp_name'], '../../' . $rutaImagen)) {
    echo json_encode(["status" => "error", "message" => "Error al mover la imagen"]);
    exit();
}

$data = [
    'id' => $_POST['id'] ?? null,
    'nombre' => $_POST['nombre'] ?? '',
    'capacidad' => $_POST['capacidad'] ?? 0,
    'estado' => $_POST['estado'] ?? 'disponible',
    'imagen' => $rutaImagen,
    'recursos' => $recursos
];

$model = new EspaciosModel($conn);
$resultado = $model->editarSala($data, $centro);

echo json_encode($resultado);

$conn->close();
?>
