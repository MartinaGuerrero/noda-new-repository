<?php

// Verifica si la sesión del usuario no está iniciada
if (!isset($_SESSION['usuario'])) {
    // Redirige al usuario a la página de inicio de sesión
    header('Location: ../index.php');
}

// Incluye el archivo de la cabecera
require_once 'header.view.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Menu secundaria</title>
    <link rel="stylesheet" href="vista/css/style-menu/styleMenuPriDoc.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;700&display=swap">
</head>
<body>


<!-- Contenedor de opciones -->
<div class="opciones">
    <!-- Opción para Escuela -->
    <div id="escuela">
        <h1>Primaria</h1>
    </div>
    <!-- Opción para Liceo -->
    <div id="liceo" onclick="moverEscuela()">
        <h1>Secundaria</h1>
    </div>
</div>

<!-- Contenedor para la opción de ver salas -->
<div id="blanco1">
    <div id="verSalas" onclick="location.href='agendaP.php'">
        <!-- Imagen y texto para la opción de ver salas -->
        <img src="vista/img/verSalas.png" alt="">
        <h1>Ver Salas</h1>
    </div>
</div>

<!-- Contenedor para la opción de mis reservas -->
<div id="blanco2">
    <div id="misReservas" onclick="location.href='misreservasP.php'">
        <img src="vista/img/misReservas.png" alt="">
        <h1>Mis Reservas</h1>
    </div>
</div>

<!-- Incluir el archivo de scripts -->
<script src="vista/js/script-menus/script-doc.js"></script>

</body>
</html>