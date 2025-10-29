<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once '../Controlador/conexion.php';

    // Obtener datos del formulario
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $capacidad = isset($_POST['capacidad']) ? $_POST['capacidad'] : null;
    $recursos = isset($_POST['recursos']) ? explode(',', $_POST['recursos']) : [];
    
    // Establecer el valor de centro como 'primaria'
    $centro = 'primaria';

    $imagen = isset($_FILES['imagen']['name']) && $_FILES['imagen']['name'] !== '' ? $_FILES['imagen']['name'] : null;
    $rutaImagen = $imagen ? 'vista/img/' . basename($imagen) : null;

    if ($imagen) {
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], '../' . $rutaImagen)) {
            echo json_encode(['success' => false, 'error' => "Error al mover la imagen a la carpeta."]);
            $conn->close();
            exit;
        }
    }

    // Insertar la sala aunque no haya imagen
    $sql_sala = "INSERT INTO espacio (nom_sala, capacidad, imagen, centro) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_sala);
    $stmt->bind_param("siss", $nombre, $capacidad, $rutaImagen, $centro);

    if ($stmt->execute()) {
        $last_sala_id = $conn->insert_id;

        $sql_recurso = "INSERT INTO recursos (recurso, id_espacio) VALUES (?, ?)";
        $stmt_recurso = $conn->prepare($sql_recurso);

        foreach ($recursos as $recurso) {
            $stmt_recurso->bind_param("si", $recurso, $last_sala_id);
            $stmt_recurso->execute();
        }

        $conn->commit();

        echo json_encode([
            'success' => true,
            'id' => $last_sala_id,
            'nombre' => $nombre,
            'capacidad' => $capacidad,
            'recursos' => implode(", ", $recursos),
            'imagen' => $rutaImagen
        ]);
    } else {
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => "Error al insertar la sala: " . $stmt->error]);
    }

    $stmt->close();
    if (isset($stmt_recurso)) {
        $stmt_recurso->close();
    }

    $conn->close();
    exit;
}
?>