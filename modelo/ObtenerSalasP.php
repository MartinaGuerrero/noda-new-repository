<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    include_once '../Controlador/conexion.php';

    if (!$conn) {
        throw new Exception("Error de conexión con la base de datos.");
    }

    $sql = "
    SELECT 
    e.id_espacio AS id,
    e.nom_sala AS nombre,
    e.capacidad,
    e.imagen,
    GROUP_CONCAT(r.recurso SEPARATOR ', ') AS recursos
    FROM 
    espacio e
    LEFT JOIN
    recursos r ON e.id_espacio = r.id_espacio
    WHERE
    e.centro = 'primaria' 
    GROUP BY
    e.id_espacio, e.nom_sala, e.capacidad, e.imagen;
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Error en la consulta: " . $conn->error);
    }

    $salas = array();

    while($row = $result->fetch_assoc()) {
        $salas[] = [
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'capacidad' => $row['capacidad'],
            'imagen' => $row['imagen'],
            'recursos' => $row['recursos'] ? explode(', ', $row['recursos']) : []
        ];
    }

    echo json_encode($salas, JSON_THROW_ON_ERROR);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()], JSON_THROW_ON_ERROR);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}