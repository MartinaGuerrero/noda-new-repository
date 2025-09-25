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

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Perfiles</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;700&display=swap">
    <link rel="stylesheet" href="vista/css/style-menu-usuarios/usuarios.css">
    <script src="https://kit.fontawesome.com/1aaabd23d9.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Botón para agregar un nuevo usuario -->
    <button id="agregar">
        <img src="vista/img/add 2.png" alt="mas" id="imagenMas">
    </button>

     <!-- Formulario para agregar un nuevo usuario -->
    <form method="post" id="formu">
        <input type="email" name="email" id="Email">
    </form>

    <!-- Contenedor del buscador -->
    <div class="buscador">
        <img src="vista/img/check 1.png" alt="Icono de búsqueda" class="IconoBuscador">
        <input type="text" id="buscar" placeholder="Buscar usuarios...">
    </div>

    <button id="NoRegistrados" class="NoRegistrados">Usuarios registrados</button>

    <!-- Contenedor para cargar dinámicamente las tarjetas de usuarios -->
    <div id="data" class="card-container">
        <!-- Aquí se cargan las tarjetas -->
    </div>

    <div id="data2" class="card-container2">
        <!-- Aquí se cargan las tarjetas de los usuarios no registrados -->
    </div>

    <div id="EliminarUsuario" class="containerEliminar">
        <div class="EliminarUsuario">

        <p>¿Estas seguro que deseas eliminar este usuario?</p>

        <button type="button" id="btnEliminarUsuario">Eliminar</button>
        <button type="button" id="btnCerrarEliminar">Cerrar</button>
        </div>
    </div>

    <div id="formularioEditarUsuario" class="containerEditar">
    <div class="formulario-editar-container">
        <h2 id="titulo">Editar usuario</h2>
        <form id="formEditarUsuario" enctype="multipart/form-data">
        <input type="hidden" id="id_usuario" name="id_usuario" value="">

            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" name="apellido" required>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>  
            <input type="hidden" id="emailOriginal" name="emailOriginal">
            
            <label for="cargo">Cargo</label>
            <select id="cargo" name="cargo" required>
                <!-- Las opciones se cargarán dinámicamente con JavaScript -->
            </select>
            
            <button type="submit" id="btnEditarUsuario">Guardar cambios</button>
            <button type="button" id="btnCerrarFormulario">Cerrar</button>
        </form>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script type="text/javascript" src="vista/js/script-menu-usuarios/functions.js"></script>
</body>
</html>