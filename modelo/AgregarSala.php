<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once '../Controlador/conexion.php';

    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $capacidad = $_POST['capacidad'];
    $recursos = explode(',', $_POST['recursos']); // Convertir la cadena de recursos en un array

    // Manejo del archivo de imagen
    $imagen = $_FILES['imagen']['name'];
    $rutaImagen = 'vista/img/' . basename($imagen);

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], '../' . $rutaImagen)) {
        // Iniciar transacción
        $conn->begin_transaction();

        try {
            // Insertar sala en la tabla "espacio"
            $sql_sala = "INSERT INTO espacio (nom_sala, capacidad, imagen, centro) VALUES (?, ?, ?, 'secundaria')";
            
            $stmt = $conn->prepare($sql_sala);
            $stmt->bind_param("sis", $nombre, $capacidad, $rutaImagen);
            
            if ($stmt->execute()) {
                $last_sala_id = $conn->insert_id;

                // Insertar recursos en la tabla "recurso"
                $sql_recurso = "INSERT INTO recursos (recurso, id_espacio) VALUES (?, ?)";
                $stmt_recurso = $conn->prepare($sql_recurso);

                foreach ($recursos as $recurso) {
                    $stmt_recurso->bind_param("si", $recurso, $last_sala_id);
                    $stmt_recurso->execute();
                }

                // Confirmar transacción
                $conn->commit();

                // Devolver la información de la sala insertada
                echo json_encode([
                    'id' => $last_sala_id, 
                    'nombre' => $nombre, 
                    'capacidad' => $capacidad, 
                    'recursos' => implode(', ', $recursos), 
                    'imagen' => $rutaImagen
                ]);
            } else {
                throw new Exception("Error al insertar la sala: " . $stmt->error);
            }
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }

        $stmt->close();
        if (isset($stmt_recurso)) {
            $stmt_recurso->close();
        }
    } else {
        echo "Error al mover la imagen a la carpeta.";
    }

    // Cerrar la conexión
    $conn->close();
}
?>