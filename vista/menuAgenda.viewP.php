<?php

// Verifica si la sesión del usuario no está iniciada
if (!isset($_SESSION['usuario'])) {
    // Redirige al usuario a la página de inicio de sesión
    header('Location: ../index.php');
}
// Incluye el archivo de la cabecera
require_once 'header.view.php';
// Incluye el archivo del menú de navegación
require_once 'menuNavegacion.viewP.php';

// Obtiene el cargo del usuario desde la sesión
$cargo = $_SESSION['cargo'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu agenda</title>
    <link rel="stylesheet" href="vista/css/style-agendar-salas/styleCalendario.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://kit.fontawesome.com/1aaabd23d9.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;700&display=swap">
    <link rel="stylesheet" href="vista/css/style-agendar-salas/styleAgenda.css">
</head>
<body>
    <div class="Buscador">
        <!-- Icono de búsqueda -->
        <img src="vista/img/check 1.png" alt="Icono de búsqueda" class="IconoBuscador"> 
        <input type="text" id="buscar" placeholder="Buscar salas..."> 
    </div>

    <!-- Verifica si el usuario tiene el cargo 'operativo' -->
    <?php if($cargo === 'operativo'): ?>
        <div class="AgregarSalaContainer">
            <!-- Botón para agregar una nueva sala -->
            <button id="btnAgregarSala">Agregar Sala</button>
        </div>
    <?php endif; ?>

    <div class="ContenedorSalas <?php echo $cargo !== 'operativo' ? 'full-height' : 'default-height'; ?>">
        <!-- Aquí se generarán dinámicamente las salas existentes y nuevas -->
    </div>

    <!-- Formulario para agregar una nueva sala -->
    <div id="formularioAgregarSala" class="formulario-overlay2">
        <div class="formulario-container">
            <h2 id="titulo">Agregar Nueva Sala</h2>
            <form id="formAgregarSala" enctype="multipart/form-data">
                <label for="imagenSala" id="imagen">Imagen</label>
                <input type="file" id="imagenSala" name="imagen" accept="image/*" required><br>
                
                <label for="nombreSala" id="nom_sala">Nombre de Sala</label>
                <input type="text" id="nombreSala" name="nombre" required><br>

                <label for="capacidadSala" id="capa_persona">Capacidad de Personas</label>
                <input type="number" id="capacidadSala" name="capacidad" required><br>

                <label for="recursosSala" id="recurso_sala">Recursos de Sala</label>
                <input type="text" id="recursosSala" name="recursos" required><br>

                <button type="button" id="btnGuardarSala">Agregar</button>
                <button type="button" id="btnCerrarFormulario">Cerrar</button>
            </form>
        </div>
    </div>

    <div id="EliminarSala" class="containerEliminar">
        <div class="EliminarSala">

        <p>¿Estas seguro que deseas eliminar esta sala?</p>

        <button type="button" id="btnEliminarSala">Eliminar</button>
        <button type="button" id="btnCerrarEliminar">Cerrar</button>
        </div>
    </div>

    <div id="formularioEditarSala" class="containerEditar">
    <div class="formulario-editar-container">
        <h2 id="titulo">Editar sala</h2>
        <form id="formEditarSala" enctype="multipart/form-data">
            <label for="imagenSala" id="imagenn">Imagen</label>
            <input type="file" id="imagenEditar" name="imagenEditar" accept="image/*" required><br>
            
            <label for="nombreSala" id="nom_sala">Nombre de Sala</label>
            <input type="text" id="nombreSalaEditar" name="nombreEditar" required><br>

            <label for="capacidadSala" id="capa_persona">Capacidad de Personas</label>
            <input type="number" id="capacidadSalaEditar" name="capacidadEditar" required><br>

            <label for="recursosSala" id="recurso_sala">Recursos de Sala</label>
            <input type="text" id="recursosSalaEditar" name="recursosEditar" required><br>

            <button type="button" id="btnEditarSala">Guardar cambios</button>
            <button type="button" id="btnCerrarEditar">Cerrar</button>
        </form>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="vista/js/script-menu-agenda/scriptSalasP.js"></script>
    <script src="vista/js/script-menu-agenda/scriptCalendario.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>