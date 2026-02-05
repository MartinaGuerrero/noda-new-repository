<?php
require_once '../Controlador/conexion.php';
require_once 'ReservasModel.php';

$model = new ReservasModel($conn);
$reservas = $model->obtenerReservas('secundaria');

header('Content-Type: application/json');
echo json_encode($reservas);

$conn->close();
?>