<?php

// Verifica si la sesión del usuario no está iniciada
if (!isset($_SESSION['usuario'])) {
    // Redirige al usuario a la página de inicio de sesión
    header('Location: ../index.php');
}

// Incluye el archivo de la cabecera
require_once 'header.view.php';
// Incluye el archivo del menú de navegación
require_once 'menuNavegacion.view.php';

// Obtiene el cargo del usuario desde la sesión
$cargo = $_SESSION['cargo'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Reservas</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;700&display=swap">
    <link rel="stylesheet" href="vista/css/style-menu-reservas/StyleReservas.css">
    <script src="https://kit.fontawesome.com/1aaabd23d9.js" crossorigin="anonymous"></script>
    <script 
        src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js" 
        integrity="sha512-UnrKxsCMN9hFk7M56t4I4ckB4N/2HHi0w/7+B/1JsXIX3DmyBcsGpT3/BsuZMZf+6mAr0vP81syWtfynHJ69JA==" 
        crossorigin="anonymous" 
        referrerpolicy="no-referrer"></script>
    <script 
        src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js" 
        integrity="sha512-Qlv6VSKh1gDKGoJbnyA5RMXYcvnpIqhO++MhIM2fStMcGT9i2T//tSwYFlcyoRRDcDZ+TYHpH8azBBCyhpSeqw==" 
        crossorigin="anonymous" 
        referrerpolicy="no-referrer"></script>
</head>
<body>

 <!-- Verifica si el usuario tiene el cargo 'operativo' -->
 <?php if($cargo === 'operativo'): ?>
    <button id="exportar">   </button>
    <a href="modelo/tablapdf.php"><button id="pdf"></button></a>
    <?php endif; ?>

    <!-- Contenedor del buscador y filtro -->
    <div class="BuscadorFiltro">
        <!-- Contenedor del buscador -->
        <div class="Buscador">
            <!-- Icono de búsqueda -->
            <img src="vista/img/check 1.png" alt="Icono de búsqueda" class="IconoBuscador">
            <!-- Campo de texto para buscar reservas -->
            <input type="text" id="buscar" placeholder="Buscar reservas...">
        </div>

        <!-- Contenedor del filtro -->
        <div class="Filtro">
            <!-- Selector para ordenar las reservas -->
            <select id="ordenar">
                <option value="az">Nombre A-Z</option>
                <option value="za">Nombre Z-A</option>
            </select>
        </div>
    </div>

    <div class="Container">
        <!-- Aquí se generarán dinámicamente las Reservas existentes y nuevas -->
    </div>

    <div id="EliminarReserva" class="containerEliminar">
        <div class="EliminarReserva">

        <p>¿Estas seguro que deseas eliminar esta reserva?</p>

        <button type="button" id="btnEliminarReserva">Eliminar</button>
        <button type="button" id="btnCerrarEliminar">Cerrar</button>
        </div>
    </div>

    <div id="formularioEditarReserva" class="containerEditar">
    <div class="formulario-editar-container">
        <h2 id="titulo">Editar reserva</h2>
        <form id="formEditarReserva" enctype="multipart/form-data">
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
            
            <button type="submit" id="btnEditarReserva">Guardar cambios</button>
            <button type="button" id="btnCerrarFormulario">Cancelar</button>
        </form>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="vista/js/script-reservas/buscador.js"></script>
    <script src="vista/js/script-reservas/scriptResevas.js"></script>
    <script src="vista/js/script-reservas/excel.js"></script>
</body>
</html>