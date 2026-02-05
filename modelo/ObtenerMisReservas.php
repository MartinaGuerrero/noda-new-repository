<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'Usuario no logueado']);
    exit();
}

require_once '../Controlador/conexion.php';
require_once 'ReservasModel.php';

$email = $_SESSION['usuario'];

$model = new ReservasModel($conn);
$reservas = $model->obtenerMisReservas($email, 'secundaria');

header('Content-Type: application/json');
echo json_encode($reservas);

$conn->close();
?>

