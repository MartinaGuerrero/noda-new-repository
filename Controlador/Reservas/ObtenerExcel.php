<?php
require_once __DIR__ . '/../conexion.php';

header('Content-Type: application/json');

$centro = $_GET['centro'] ?? 'secundaria';

if (!in_array($centro, ['primaria', 'secundaria'], true)) {
    echo json_encode(["status" => "error", "message" => "Centro invalido"]);
    exit();
}

$query = "SELECT 
            reserva.id_auto,
            reserva.hora_i,
            reserva.hora_f,
            reserva.observacion,
            reserva.insumo,
            espacio.nom_sala,
            usuario.email
          FROM reserva
          LEFT JOIN espacio ON reserva.fk_id_e = espacio.id_espacio
          LEFT JOIN usuario ON reserva.fk_email = usuario.email
          WHERE espacio.centro = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $centro);
$stmt->execute();
$result = $stmt->get_result();

$datos = [];
while ($row = $result->fetch_assoc()) {
    $datos[] = $row;
}

echo json_encode($datos);

$stmt->close();
$conn->close();
?>
