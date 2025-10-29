<?php
include_once '../Controlador/conexion.php';

$sql = "SELECT e.id_espacio, e.nom_sala, e.capacidad, e.imagen, GROUP_CONCAT(r.recurso SEPARATOR ', ') as recursos
        FROM espacio e
        LEFT JOIN recursos r ON e.id_espacio = r.id_espacio
        WHERE e.centro = 'primaria'
        GROUP BY e.id_espacio, e.nom_sala, e.capacidad, e.imagen";

$result = $conn->query($sql);

$salas = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $salas[] = [
            'id' => $row['id_espacio'],
            'nombre' => $row['nom_sala'],
            'capacidad' => $row['capacidad'],
            'recursos' => $row['recursos'] !== null ? $row['recursos'] : '',
            'imagen' => $row['imagen']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($salas);

$conn->close();
?>