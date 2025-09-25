<?php
session_start();

// Incluye el archivo de conexión
require_once '../Controlador/conexion.php';

// Verifica si la conexión se estableció correctamente
if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_SESSION['usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $password = $_POST['password'];

    // Verificar si la contraseña ha cambiado y obtener el cargo actual
    $sql = "SELECT password, cargo FROM usuario WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta']);
        exit;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario['password'] !== hash('sha512', $password)) {
        // La contraseña ha cambiado, hay que actualizarla
        $password_hash = hash('sha512', $password);
    } else {
        // La contraseña no ha cambiado, usamos la existente
        $password_hash = $usuario['password'];
    }

    // Mantener el cargo actual
    $cargo = $usuario['cargo'];

    // Actualizar los datos del usuario
    $sql = "UPDATE usuario SET nombre = ?, apellido = ?, password = ?, cargo = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta de actualización']);
        exit;
    }
    $stmt->bind_param("sssss", $nombre, $apellido, $password_hash, $cargo, $email);

    if ($stmt->execute()) {
        // Actualizar los datos de la sesión
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellido'] = $apellido;
        $_SESSION['cargo'] = $cargo;

        echo json_encode(['success' => true, 'message' => 'Perfil actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el perfil: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido']);
}

// Cierra la conexión al final del script
$conn->close();