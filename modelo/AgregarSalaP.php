<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once '../Controlador/conexion.php';

    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $capacidad = $_POST['capacidad'];
    $recursos = explode(',', $_POST['recursos']);
    
    // Establecer el valor de centro como 'primaria'
    $centro = 'primaria';

    // Depuración: Verificar el valor de centro
    //var_dump($centro);

    $imagen = $_FILES['imagen']['name'];
    $rutaImagen = 'vista/img/' . basename($imagen);

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], '../' . $rutaImagen)) {
        $conn->begin_transaction();

        try {
            // Depuración: Verificar el valor de centro antes de la consulta
            //echo "Valor de centro antes de la consulta: " . $centro;

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
                    'recursos' => implode(', ', $recursos),
                    'imagen' => $rutaImagen,
                    'centro' => $centro
                ]);
            } else {
                throw new Exception("Error al insertar la sala: " . $stmt->error);
            }
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        $stmt->close();
        if (isset($stmt_recurso)) {
            $stmt_recurso->close();
        }
    } else {
        echo json_encode(['success' => false, 'error' => "Error al mover la imagen a la carpeta."]);
    }

    $conn->close();
}
?>