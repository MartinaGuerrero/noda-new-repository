<?php
require_once '../Controlador/conexion.php';
require_once 'EspaciosModel.php';

$model = new EspaciosModel($conn);
$salas = $model->obtenerSalas('secundaria');

header('Content-Type: application/json');
echo json_encode($salas);

$conn->close();
?>