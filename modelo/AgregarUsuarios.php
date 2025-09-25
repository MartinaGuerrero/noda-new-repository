<?php
include_once '../Controlador/conexion.php';

header('Content-Type: application/json');

function sendJsonResponse($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['email'])) {
        sendJsonResponse('error', 'Solicitud inválida');
    }

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendJsonResponse('error', 'Email inválido');
    }

    global $conn;
    
    // Verificar si el email ya existe
    $stmt = $conn->prepare("SELECT email FROM usuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        sendJsonResponse('error', 'El email ya existe en la base de datos');
    }
    
    // Insertar el nuevo email
    $stmt = $conn->prepare("INSERT INTO usuario (email) VALUES (?)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        sendJsonResponse('success', 'Usuario agregado correctamente');
    } else {
        sendJsonResponse('error', 'No se pudo agregar el usuario');
    }

} catch (Exception $e) {
    sendJsonResponse('error', 'Error inesperado: ' . $e->getMessage());
}
?>