<?php
$email_usuario = $_SESSION['email'] ?? null;

// Verifica si la sesión del usuario no está iniciada
if (!isset($_SESSION['usuario'])) {
    // Redirige al usuario a la página de inicio de sesión
    header('Location: ../index.php');
}

// Incluye el archivo de la cabecera
require_once 'header.view.php';
// Incluye el archivo del menú de navegación
require_once 'menuNavegacion.view.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas</title>
    <link rel="stylesheet" href="vista/css/style-misReservas/styleMisReservas.css">
    <script src="https://kit.fontawesome.com/1aaabd23d9.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="Container">
        <!-- Contenedor del buscador -->
        <div class="Buscador">
            <!-- Icono de búsqueda -->
            <img src="vista/img/check 1.png" alt="Icono de búsqueda" class="IconoBuscador"> 
            <input type="text" id="buscar" placeholder="Buscar reservas..."> 
        </div>

        <!-- Contenedor donde se mostrarán las reservas del usuario -->
        <div id="reservas-container">
            <!-- Aquí se mostrarán las reservas del usuario -->
        </div>
    </div>

    <div id="EliminarMisReserva" class="containerEliminar">
        <div class="EliminarMisReserva">

        <p>¿Estas seguro que deseas eliminar esta reserva?</p>

        <button type="button" id="btnEliminarMisReserva">Eliminar</button>
        <button type="button" id="btnCerrarEliminar">Cerrar</button>
        </div>
    </div>

    <div id="formularioEditarMisReserva" class="containerEditar">
    <div class="formulario-editar-container">
        <h2 id="titulo">Editar reserva</h2>
        <form id="formEditarMisReserva" enctype="multipart/form-data">
        <input type="hidden" id="id_reserva" name="id_reserva" value="">
            <label for="sala">Sala</label>
            <select id="sala" name="sala" required>
                <!-- salas -->
            </select>

            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" required>
            
            <label for="hora_inicio">Hora de inicio</label>
            <input type="time" id="hora_inicio" name="hora_inicio" required>
            
            <label for="hora_fin">Hora de fin</label>
            <input type="time" id="hora_fin" name="hora_fin" required>  
            
            <label for="insumo">Insumo</label>
            <input type="text" id="insumo" name="insumo" required>

            <label for="observaciones">Observaciones</label>
            <input type="text" id="observaciones" name="observacion" required>

            <label for="serLimpieza" class="label" id="servLimpieza">Servicio de limpieza</label>
                <label class="switch">
                    <input type="checkbox" id="toggleSwitch">
                    <span class="slider"></span>
                </label>
            
            <button type="submit" id="btnEditarMisReserva">Guardar cambios</button>
            <button type="button" id="btnCerrarFormulario">Cancelar</button>
        </form>
    </div>
</div>

    <script src="vista/js/script-misReservas/scriptMisReservas.js"></script>
    <script src="vista/js/script-misReservas/buscador.js"></script>
</body>
</html>