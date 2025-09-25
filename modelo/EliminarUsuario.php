<?php

require_once '../Controlador/conexion.php'; // Asegúrate de que esta ruta sea correcta para tu archivo de conexión

// Verifica si se ha enviado una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene el email del usuario a eliminar
    $email = isset($_POST['email']) ? $_POST['email'] : null;

    if ($email) {
        // Primero, verifica si el usuario tiene reservas
        $sqlCheckReservations = "SELECT COUNT(*) FROM reserva WHERE fk_email = ?"; // Cambia 'email_usuario' al nombre real de la columna
        $stmtCheck = $conn->prepare($sqlCheckReservations);
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($count > 0) {
            echo json_encode(['status' => 'error', 'message' => 'El usuario tiene reservas asociadas y no puede ser eliminado.']);
            exit;
        }

        // Preparar la consulta SQL para eliminar el usuario
        $sqlDelete = "DELETE FROM usuario WHERE email = ?";

        // Usar prepared statements para evitar inyecciones SQL
        if ($stmt = $conn->prepare($sqlDelete)) {
            // Vincular el parámetro a la consulta
            $stmt->bind_param("s", $email);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Verifica si se eliminó alguna fila
                if ($stmt->affected_rows > 0) {
                    echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado correctamente.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No se encontró ningún usuario con ese correo electrónico.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al ejecutar la consulta: ' . $stmt->error]);
            }

            // Cerrar el statement
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al preparar la consulta.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Se requiere un correo electrónico para eliminar el usuario.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no válido.']);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
