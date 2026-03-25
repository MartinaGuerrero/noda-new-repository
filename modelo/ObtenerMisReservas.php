<?php
session_start();

require_once __DIR__ . '/../Controlador/conexion.php';
require_once __DIR__ . '/ReservasModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'Usuario no logueado']);
    exit();
}

$centro = $_GET['centro'] ?? 'secundaria';

if (!in_array($centro, ['primaria', 'secundaria'], true)) {
    echo json_encode(['error' => 'Centro invalido']);
    exit();
}

$email = $_SESSION['usuario'];

$model = new ReservasModel($conn);
$reservas = $model->obtenerMisReservas($email, $centro);

echo json_encode($reservas);

$conn->close();
?>
