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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Secundaria</title>
    <link rel="stylesheet" href="vista/css/style-menu/styleMenuPriLider.css">
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
        <div id="liceo">
            <h1>Secundaria</h1>
        </div>
    </div>

    <div class="container">
        <div class="menu" id="menu1">
            <!-- Sección izquierda del menú -->
            <div class="left">
                <div class="leftin" onclick="location.href='AgendaP.php'">
                    <img src="vista/img/verSalas.png" alt="" class="imagenMenu">
                    <h1>Ver salas</h1>
                </div>
            </div>

            <!-- Sección inferior del menú -->
            <div class="bottom">
                <div class="bottomin" onclick="location.href='reservaP.php'">
                    <img src="vista/img/reservas.png" alt="" class="imagenMenu">
                    <h1>Reservas</h1>
                </div>
            </div>

            <!-- Sección derecha del menú -->
            <div class="right">
                <div class="rightin" onclick="location.href='misreservasP.php'">
                    <img src="vista/img/misReservas.png" alt="" class="imagenMenu">
                    <h1>Mis reservas</h1>
                </div>
            </div>
        </div>
    </div>

    <script src="vista/js/script-menus/script-lider.js"></script>
    
</body>
</html>