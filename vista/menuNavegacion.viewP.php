
<?php

// Verifica si la sesión del usuario no está iniciada
if (!isset($_SESSION['usuario'])) {
    // Redirige al usuario a la página de inicio de sesión
    header('Location: ../index.php');
}

// Obtiene el cargo del usuario desde la sesión
$cargo = $_SESSION['cargo'];

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;700&display=swap">
        <link rel="stylesheet" href="vista/css/style-menu-navegacion/styleMenuNavegacion.css">
    </head>
        <body>
        <button id="menuToggle">☰</button>

        <div class="containerMenu">
    <div id="opciones">
        <div class="div Principal" onclick="location.href='menuprincipal.php'">
            <img src="vista/img/hogar.png" alt="" id="menuPrincipal">
            <div class="nombreMenuPrincipal">
                <p>Menú principal</p>
            </div>
        </div>

        <div class="div agenda" onclick="location.href='agendaP.php'">
            <img src="vista/img/verSalas.png" alt="" id="menuSalas">
            <div class="Salas">
                <p>Salas</p>
            </div>
        </div>

        <?php if($cargo === 'operativo' || $cargo === 'lider'): ?>
            <div class="div reservas" onclick="location.href='reservaP.php'">
                <img src="vista/img/reservas.png" alt="" id="menuReservas">
                <div class="Reservas">
                    <p>Reservas</p>
                </div>
            </div>
        <?php endif; ?>

        <div class="div misreservas" onclick="location.href='misreservasP.php'">
            <img src="vista/img/misReservas.png" alt="" id="menuMisReservas">
            <div class="MisReservas">
                <p>Mis reservas</p>
            </div>
        </div>

        <?php if($cargo === 'operativo'): ?>
            <div class="div" id="menuUsuarios" onclick="location.href='perfilP.php'">
                <img src="vista/img/verUsuario.png" alt="" id="menuPerfiles">
                <div class="Usuarios">
                    <p>Perfiles</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="vista/js/script-menu-navegacion/scriptNavegacion.js"></script>
</body>
</html>


