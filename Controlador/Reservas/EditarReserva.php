<?php
session_start();

require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../../modelo/ReservasModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario']) || ($_SESSION['cargo'] ?? '') !== 'operativo') {
    echo json_encode(["status" => "error", "message" => "No autorizado"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Metodo no permitido"]);
    exit();
}

$centro = $_POST['centro'] ?? 'secundaria';

if (!in_array($centro, ['primaria', 'secundaria'], true)) {
    echo json_encode(["status" => "error", "message" => "Centro invalido"]);
    exit();
}

$data = [
    'id_reserva' => $_POST['id_reserva'] ?? null,
    'fecha' => $_POST['fecha'] ?? null,
    'hora_inicio' => $_POST['hora_inicio'] ?? null,
    'hora_fin' => $_POST['hora_fin'] ?? null,
    'insumo' => $_POST['insumo'] ?? null,
    'sala' => $_POST['sala'] ?? null,
    'observacion' => $_POST['observacion_editar'] ?? ($_POST['observacion'] ?? ($_POST['observaciones'] ?? null)),
    'limpieza' => $_POST['limpieza'] ?? null,
];

file_put_contents(
    __DIR__ . '/../../tmp_editar_reserva.log',
    json_encode(
        [
            'post' => $_POST,
            'data' => $data
        ],
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    )
);

$model = new ReservasModel($conn);
$resultado = $model->editarReserva($data, $centro);

echo json_encode($resultado);

$conn->close();
?>
