<?php
require_once __DIR__ . '/../Controlador/conexion.php';
require_once __DIR__ . '/EspaciosModel.php';

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

$nombre = $_POST['nombre'] ?? '';
$capacidad = $_POST['capacidad'] ?? 0;
$recursos = isset($_POST['recursos']) ? explode(',', $_POST['recursos']) : [];

$imagen = $_FILES['imagen']['name'] ?? '';
$rutaImagen = 'vista/img/' . basename($imagen);

if (!move_uploaded_file($_FILES['imagen']['tmp_name'], '../' . $rutaImagen)) {
    echo json_encode(["status" => "error", "message" => "Error al mover la imagen"]);
    exit();
}

$data = [
    'nombre' => $nombre,
    'capacidad' => $capacidad,
    'recursos' => $recursos,
    'rutaImagen' => $rutaImagen
];

$model = new EspaciosModel($conn);
$resultado = $model->crearSala($data, $centro);

echo json_encode($resultado);

$conn->close();
?>
