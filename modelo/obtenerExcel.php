<?php
include_once '../Controlador/conexion.php';
// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'novosis_NODA');
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Ejecutar la consulta
$query = "SELECT reserva.id_auto, reserva.hora_i, reserva.hora_f, 
          reserva.observacion, reserva.insumo, espacio.nom_sala, usuario.email 
          FROM reserva 
          LEFT JOIN espacio ON reserva.fk_id_e = espacio.id_espacio 
          LEFT JOIN usuario ON reserva.fk_email = usuario.email;";

$result = $conexion->query($query);

// Comprobar si hay resultados
$datos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $datos[] = $row;
    }
}

// Devolver datos en formato JSON
header('Content-Type: application/json');
echo json_encode($datos);

$conexion->close();
