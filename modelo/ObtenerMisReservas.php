<?php 
// Desactivar la salida de errores PHP
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Establecer el tipo de contenido a JSON
header('Content-Type: application/json');

// Iniciar la sesión
session_start();

// Verificar si el usuario está logueado
if(!isset($_SESSION['usuario'])){
    echo json_encode(['error' => 'Usuario no logueado']);
    exit();
}

try {
    // Intentar conectar a la base de datos
    $conexion = new PDO('mysql:host=localhost;dbname=novosis_noda', 'root', '');
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el email del usuario desde la sesión
    $email = $_SESSION['usuario'];

    // Preparar la consulta SQL para obtener las reservas del usuario
    $statement = $conexion->prepare('
        SELECT reserva.id_auto AS id, reserva.fecha, reserva.hora_i, reserva.hora_f, reserva.insumo AS insumos, reserva.observacion, espacio.nom_sala, espacio.imagen
        FROM reserva
        INNER JOIN espacio ON reserva.fk_id_e = espacio.id_espacio
        WHERE reserva.fk_email = :email
    ');

    // Ejecutar la consulta con el email del usuario
    $statement->execute(array(':email' => $email));

    // Obtener todas las reservas en un array asociativo
    $reservas = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Devolver las reservas en formato JSON
    echo json_encode($reservas);

} catch (PDOException $e) {
    // Manejar errores de base de datos
    echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    // Manejar otros errores
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
?>