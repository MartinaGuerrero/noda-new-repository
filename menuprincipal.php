<?php session_start();

if (isset($_SESSION['usuario'])) {
    require 'vista/menuPrincipal.view.php';
    if (isset($_SESSION['mensaje'])) {
        echo '<div class="alert show">
                <span class="fa-solid fa-check"></span>
                <span class="msg">' . $_SESSION['mensaje'] . '</span>
                <span class="close-btn">
                    <span class="fas fa-times"></span>
                </span>
              </div>';
        unset($_SESSION['mensaje']); // Elimina el mensaje de la sesión
    }
    ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $('.alert').removeClass("hide");
        $('.alert').addClass("show");
        $('.alert').addClass("showAlert");
        setTimeout(function(){
            $('.alert').removeClass("show");
            $('.alert').addClass("hide");
        }, 5000);
    
        $('.close-btn').click(function(){
            $('.alert').removeClass("show");
            $('.alert').addClass("hide");
        });
    </script>
    <?php
} else {
    header('Location: modelo/login_registrar.php');
}

?>