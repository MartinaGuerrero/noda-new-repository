<?php
require_once '../Controlador/conexion.php';
header('Content-Type: application/json'); 

if (isset($_GET['res'])) {
    $response = array(
        'usuarios' => array(),
        'cargos' => array()
    );

    if (isset($_GET['busqueda']) && !empty($_GET['busqueda'])) {
        $busqueda = $conn->real_escape_string($_GET['busqueda']); 
        $query = "SELECT * FROM usuario WHERE nombre LIKE '%$busqueda%' OR apellido LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR cargo LIKE '%$busqueda%'";
    } else {
        $query = "SELECT * FROM usuario";
    }

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $nombre = $row['nombre'];
            $apellido = $row['apellido'];
            $iniciales = strtoupper(substr($nombre, 0, 1) . substr($apellido, 0, 1)); 

            $response['usuarios'][] = array(
                'email' => $row['email'],
                'nombre' => $row['nombre'],
                'apellido' => $row['apellido'],
                'cargo' => $row['cargo'],
                'iniciales' => $iniciales 
            );

            // Agregar el cargo al array de cargos si no existe
            if (!in_array($row['cargo'], $response['cargos'])) {
                $response['cargos'][] = $row['cargo'];
            }
        }
    }

    echo json_encode($response);
} else {
    echo json_encode(array('usuarios' => [], 'cargos' => []));
}
?>