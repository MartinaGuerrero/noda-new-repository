<?php
require_once '../Controlador/conexion.php';

// Asegúrate de que no haya salida antes de este punto
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $cargo = $_POST['cargo'] ?? '';
    $email = $_POST['email'] ?? '';
    $emailOriginal = $_POST['emailOriginal'] ?? '';

    $contraseña = 'c764507e78bd93111da214cd3bc08d905c93079f4d47c44314b2088d08765e5b77d890f5f9e114ff6476de5faf39a0f838da010e1ace49242136352f060b4f45';

    // Verificar que todos los campos necesarios estén presentes
    if (empty($nombre) || empty($apellido) || empty($cargo) || empty($email) || empty($emailOriginal)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    // Escapar los valores para prevenir inyección SQL
    $nombre = mysqli_real_escape_string($conn, $nombre);
    $apellido = mysqli_real_escape_string($conn, $apellido);
    $cargo = mysqli_real_escape_string($conn, $cargo);
    $email = mysqli_real_escape_string($conn, $email);
    $emailOriginal = mysqli_real_escape_string($conn, $emailOriginal);

    // Verificar si el nuevo email ya existe (si se está cambiando)
    if ($email !== $emailOriginal) {
        $checkEmail = "SELECT email FROM usuario WHERE email = '$email'";
        $result = mysqli_query($conn, $checkEmail);
        if (mysqli_num_rows($result) > 0) {
            echo json_encode(['status' => 'error', 'message' => 'El email ya está en uso']);
            exit;
        }
    }

    $sql = "UPDATE usuario SET nombre = '$nombre', apellido = '$apellido', cargo = '$cargo', email = '$email', password = '$contraseña' WHERE email = '$emailOriginal'";
    $resultado = mysqli_query($conn, $sql);

    if ($resultado) {
        echo json_encode(['status' => 'success', 'message' => 'Usuario actualizado correctamente']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el usuario: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no válido']);
}

// Asegúrate de que no haya salida después de este punto
exit;
?>