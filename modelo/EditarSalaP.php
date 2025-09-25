<?php

include_once '../Controlador/conexion.php';

if (!$conn) {
    die("Error de conexión con la base de datos.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(print_r($_POST, true));
    error_log(print_r($_FILES, true));

    // Recuperar el ID del espacio
    $id_espacio = isset($_POST['salaId']) ? $_POST['salaId'] : null;

    // Verificar que el ID del espacio esté definido
    if (!$id_espacio) {
        echo json_encode(['error' => 'ID de espacio no proporcionado']);
        exit;
    }

    $nom_sala = $_POST['nombreEditar'] ?? '';
    $capacidad = $_POST['capacidadEditar'] ?? '';
    $recursos = isset($_POST['recursosEditar']) ? explode(',', $_POST['recursosEditar']) : [];

    // Verificar que todos los campos estén definidos
    if (empty($nom_sala) || empty($capacidad)) {
        echo json_encode(['error' => 'Datos incompletos']);
        exit;
    }

    // Procesar la imagen si está presente
    $imagen = null;
    if (isset($_FILES['imagenEditar']) && $_FILES['imagenEditar']['error'] === UPLOAD_ERR_OK) {
        $imagen = 'vista/img/' . basename($_FILES['imagenEditar']['name']);
        if (!move_uploaded_file($_FILES['imagenEditar']['tmp_name'], '../' . $imagen)) {
            echo json_encode(['error' => 'Error al mover la imagen']);
            exit;
        }
    }

    // Iniciar una transacción para asegurarnos de que todo se ejecute correctamente
    $conn->begin_transaction();

    try {
        // Actualizar espacio
        if ($imagen) {
            $stmt = $conn->prepare("UPDATE espacio SET nom_sala = ?, capacidad = ?, imagen = ?, centro = 'primaria' WHERE id_espacio = ?");
            $stmt->bind_param("sisi", $nom_sala, $capacidad, $imagen, $id_espacio);
        } else {
            $stmt = $conn->prepare("UPDATE espacio SET nom_sala = ?, capacidad = ?, centro = 'primaria' WHERE id_espacio = ?");
            $stmt->bind_param("sii", $nom_sala, $capacidad, $id_espacio);
        }

        // Ejecutar la consulta
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar el espacio: " . $stmt->error);
        }

        // Eliminar recursos existentes
        $stmt = $conn->prepare("DELETE FROM recursos WHERE id_espacio = ?");
        $stmt->bind_param("i", $id_espacio);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar recursos existentes: " . $stmt->error);
        }

        // Insertar nuevos recursos
        if (!empty($recursos)) {
            $stmt = $conn->prepare("INSERT INTO recursos (recurso, id_espacio) VALUES (?, ?)");
            foreach ($recursos as $recurso) {
                $stmt->bind_param("si", $recurso, $id_espacio);
                if (!$stmt->execute()) {
                    throw new Exception("Error al insertar recurso: " . $stmt->error);
                }
            }
        }

        // Confirmar la transacción
        $conn->commit();

        // Respuesta exitosa
        $response = [
            'id' => $id_espacio,
            'nombreEditar' => $nom_sala,
            'capacidadEditar' => $capacidad,
            'imagenEditar' => $imagen,
            'recursosEditar' => $recursos
        ];
        echo json_encode($response);
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo json_encode(['error' => $e->getMessage()]);
    }

    // Cerrar las declaraciones preparadas
    if (isset($stmt)) {
        $stmt->close();
    }
}

$conn->close();