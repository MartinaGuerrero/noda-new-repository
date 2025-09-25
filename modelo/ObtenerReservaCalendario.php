<?php
include_once '../Controlador/conexion.php';


$fecha = isset($_GET['date']) ? $_GET['date'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

$sql = "
SELECT 
    r.fecha, 
    r.hora_i, 
    r.hora_f, 
    r.fk_id_e,
    u.nombre AS usuario_nombre, 
    u.apellido AS usuario_apellido
FROM 
    reserva r
JOIN 
    usuario u ON r.fk_email = u.email
WHERE 
    r.fecha = '$fecha' AND r.fk_id_e = '$id'";  

$result = $conn->query($sql);

$reservas = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $reservas[] = [
            'fecha' => $row["fecha"],
            'hora' => $row["hora_i"] . " - " . $row["hora_f"],
            'nombre' => $row["usuario_nombre"] . " " . $row["usuario_apellido"],
        ];
    }
}

// Configurar el tipo de contenido a JSON
header('Content-Type: application/json');
echo json_encode($reservas);

$conn->close();
?>
