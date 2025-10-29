<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Secundaria</title>

    <!-- CSS principal -->
    <link rel="stylesheet" href="../vista/css/style-menu/styleMenuSecOperativo.css">

</head>
<body>

<?php
require_once '../vista/layouts/header.view.php';
?>

<div class="todo">
    <div class="containeropciones">
        <!-- Contenedor de opciones -->
        <div class="opciones">
            <div id="escuela">
                <h1>Primaria</h1>
            </div>
            <div id="liceo">
                <h1>Secundaria</h1>
            </div>
        </div>

        <!-- Primera fila de opciones -->
        <div id="fila1">
            <?php include '../vista/components/verSalas.component.php'; ?>
            <?php include '../vista/components/misReservas.component.php'; ?>
        </div>

        <!-- Segunda fila de opciones -->
        <div id="fila2">
            <?php include '../vista/components/reservas.component.php'; ?>
            <?php include '../vista/components/perfiles.component.php'; ?>
        </div>
    </div>
</div>

<?php
require_once '../vista/layouts/footer.view.php';
?>

<script src="../vista/js/script-menus/script-operativo.js" defer></script>
</body>
</html>
