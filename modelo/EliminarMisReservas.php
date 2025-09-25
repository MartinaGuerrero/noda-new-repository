<?php
// Inicia la sesión si es necesario
session_start();

// Verifica que el ID de la Reserva se haya recibido correctamente
if (isset($_POST['id'])) {
    // Obtener el ID de la Reserva desde la solicitud POST
    $id_reserva = $_POST['id'];

    // Incluir la conexión a la base de datos
    require_once '../Controlador/conexion.php';  // Asegúrate de que la ruta es correcta

    // Luego, eliminar la Reserva de la tabla 'reserva'
    $sql_reserva = "DELETE FROM reserva WHERE id_auto = ?";
    if ($stmt_reserva = $conn->prepare($sql_reserva)) {
        $stmt_reserva->bind_param("i", $id_reserva);
        if ($stmt_reserva->execute()) {
            echo "Reserva eliminada correctamente.";
        } else {
            echo "Error al eliminar la Reserva.";
        }
        $stmt_reserva->close();
    } else {
        echo "Error en la preparación de la consulta.";
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    echo "No se recibió el ID de la Reserva.";
}

?>