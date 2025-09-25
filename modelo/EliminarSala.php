<?php
// Inicia la sesión si es necesario (dependiendo de tu configuración)
session_start();

// Verifica que el ID de la sala se haya recibido correctamente
if (isset($_POST['id'])) {
    // Obtener el ID de la sala desde la solicitud POST
    $id_sala = $_POST['id'];

    // Incluir la conexión a la base de datos
    require_once '../Controlador/conexion.php';  // Asegúrate de que la ruta es correcta

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Verificar si hay reservas asociadas a la sala
        $sql_reservas = "SELECT COUNT(*) as total FROM reserva WHERE fk_id_e = ?";
        $stmt_reservas = $conn->prepare($sql_reservas);
        $stmt_reservas->bind_param("i", $id_sala);
        $stmt_reservas->execute();
        $result_reservas = $stmt_reservas->get_result();
        $row_reservas = $result_reservas->fetch_assoc();
        $stmt_reservas->close();

        if ($row_reservas['total'] > 0) {
            // Si hay reservas, lanzar una alerta y detener el proceso
            echo "No se puede eliminar la sala porque tiene reservas asociadas.";
            $conn->rollback();
            exit;
        }

        // Primero, eliminar los recursos asociados a la sala
        $sql_recursos = "DELETE FROM recursos WHERE id_espacio = ?";
        $stmt_recursos = $conn->prepare($sql_recursos);
        $stmt_recursos->bind_param("i", $id_sala);
        $stmt_recursos->execute();
        $stmt_recursos->close();

        // Luego, eliminar la sala
        $sql_sala = "DELETE FROM espacio WHERE id_espacio = ?";
        $stmt_sala = $conn->prepare($sql_sala);
        $stmt_sala->bind_param("i", $id_sala);
        $stmt_sala->execute();
        $stmt_sala->close();

        // Si todo salió bien, confirmar la transacción
        $conn->commit();
        echo "Sala y recursos asociados eliminados correctamente.";
    } catch (Exception $e) {
        // Si algo salió mal, revertir la transacción
        $conn->rollback();
        echo "Error al eliminar la sala: " . $e->getMessage();
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    echo "No se recibió el ID de la sala.";
}
?>