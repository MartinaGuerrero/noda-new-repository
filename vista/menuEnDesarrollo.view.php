<?php

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login_registrar.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="vista/css/style-menu/styleMenuEnDesarrollo.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primaria</title>
</head>
<body>
    <div class="container">
        <div class="grua">
            <img src="vista/img/grua.png" alt="">
        </div>
        <div class="logo">
            <img src="vista/img/logoError.png" alt="">
        </div>
        <div class="mensaje">
            <img src="vista/img/MensajeDeDesarrollo.png" alt="">
        </div>
        <div>
            <button class="boton" onclick="location.href='menuprincipal.php'"><h2>Volver al menu</h2></button>
        </div>

    </div>
</body>
</html>