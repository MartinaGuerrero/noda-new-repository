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
    <link rel="stylesheet" href="vista/css/style-menu/styleMenuPriOperativo.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;700&display=swap">
</head>
<body>
    <div class="todo">
        <div class="containeropciones">
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

            <!-- Primera fila de opciones -->
            <div id="fila1">
                <div id="blancoVS">
                    <!-- Opción para Ver Salas -->
                    <div id="verSalas" onclick="location.href='agendaP.php'">
                        <img src="vista/img/verSalas.png" alt="">
                        <h1>Ver salas</h1>
                    </div>
                </div>

                <div id="blancoMR">
                    <!-- Opción para Mis Reservas -->
                    <div id="misReservas" onclick="location.href='misreservasP.php'">
                        <img src="vista/img/misReservas.png" alt="">
                        <h1>Mis reservas</h1>
                    </div>
                </div>
            </div>

            <!-- Segunda fila de opciones -->
            <div id="fila2">
                <div id="blancoR">
                    <!-- Opción para Reservas -->
                    <div id="reservas" onclick="location.href='reservaP.php'">
                        <img src="vista/img/reservas.png" alt="">
                        <h1>Reservas</h1>
                    </div>
                </div>

                <div id="blancoP">
                    <!-- Opción para Perfiles -->
                    <div id="perfiles" onclick="location.href='perfilP.php'">
                        <img src="vista/img/verUsuario.png" alt="">
                        <h1>Perfiles</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vista/js/script-menus/script-operativo.js"></script>
</body>
</html>