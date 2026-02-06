<?php
require_once '../Controlador/conexion.php';
require_once 'ReservasModel.php';

session_start();
if (!isset($_SESSION['usuario']) || ($_SESSION['cargo'] ?? '') !== 'operativo') {
    echo json_encode(["status" => "error", "message" => "No autorizado"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit();
}

$id = $_POST['id_reserva'] ?? null;

$model = new ReservasModel($conn);
$resultado = $model->eliminarReserva($id);

header('Content-Type: application/json');
echo json_encode($resultado);

$conn->close();


?>