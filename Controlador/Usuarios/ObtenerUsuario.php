<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../../modelo/UsuariosModel.php';

header('Content-Type: application/json');

if (isset($_GET['res'])) {
    $busqueda = isset($_GET['busqueda']) && $_GET['busqueda'] !== '' ? $_GET['busqueda'] : null;

    $model = new UsuariosModel($conn);
    $response = $model->obtenerUsuariosConCargos($busqueda);

    echo json_encode($response);
} else {
    echo json_encode(['usuarios' => [], 'cargos' => []]);
}

$conn->close();
?>
