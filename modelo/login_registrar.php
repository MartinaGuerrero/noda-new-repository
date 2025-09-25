<?php 
// Iniciar la sesión
session_start();

// Verificar si el usuario ya está logueado
if(isset($_SESSION['usuario'])){
    // Redirigir al usuario a la página principal si ya está logueado
    header('Location: ../index.php');
    exit();
}

// Verificar si la solicitud es de tipo POST
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener la acción del formulario (login o signup)
    $action = $_POST['action'];

    // Llamar a la función correspondiente según la acción
    if ($action == 'login') {
        login();
    } else if ($action == 'signup') {
        registro();
    }
}

// Función para manejar el inicio de sesión
function login(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitizar y obtener los datos del formulario
        $email = filter_var(strtolower($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        // Encriptar la contraseña
        $password = hash('sha512', $password);

        // Intentar conectar a la base de datos
        try {
            $conexion = new PDO('mysql:host=localhost;dbname=novosis_noda', 'root', '');
        } catch (PDOException $e) {
            // Manejar error de conexión
            $_SESSION['error'] = 'Error de conexión con la base de datos';
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
        
        // Preparar y ejecutar la consulta SQL para verificar las credenciales
        $statement = $conexion->prepare('SELECT * FROM usuario WHERE email = :email AND password = :password');
        $statement->execute(array(
            ':email' => $email, 
            ':password' => $password
        ));

        // Obtener el resultado de la consulta
        $resultado = $statement->fetch();
        if ($resultado != false) {
            // Si las credenciales son correctas, iniciar sesión y redirigir al usuario
            $_SESSION['usuario'] = $email;
            $_SESSION['cargo'] = $resultado['cargo'];
            $_SESSION['nombre'] = $resultado['nombre'];
            $_SESSION['apellido'] = $resultado['apellido'];
            $_SESSION['mensaje'] = 'Sesión iniciada correctamente';
            header('Location: ../index.php');
            exit();
        } else {
            // Mostrar mensaje de error si las credenciales son incorrectas
            ?>
            <div class="alert show">
                <span class="fas fa-exclamation-circle"></span>
                <span class="msg"><?php echo "El E-mail o contraseña son incorrectos"; ?></span>
                <span class="close-btn">
                    <span class="fas fa-times"></span>
                </span>
            </div>
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <script>
                // Mostrar y ocultar la alerta de error
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
        }
    }
}

// Función para manejar el registro de nuevos usuarios
function registro() {
    // Verificar si la solicitud es de tipo POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitizar y obtener los datos del formulario
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_SPECIAL_CHARS);
        $apellido = filter_var($_POST['apellido'], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var(strtolower($_POST['email']), FILTER_SANITIZE_EMAIL);
        $cargo = $_POST['cargo'];
        $password = $_POST['password'];

        try {
            $conexion = new PDO('mysql:host=localhost;dbname=novosis_noda', 'root', '');
        } catch (PDOException $e) {
            // Manejar error de conexión
            $_SESSION['error'] = 'Error de conexión con la base de datos';
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }

        // Preparar y ejecutar la consulta SQL para verificar si el email ya existe en la tabla
        $statement = $conexion->prepare('SELECT * FROM usuario WHERE email = :email LIMIT 1');
        $statement->execute(array(':email' => $email));
        $resultado = $statement->fetch(PDO::FETCH_ASSOC);

        // Verificar si el resultado existe
        if ($resultado) {
            // Comprobar si los campos están llenos
            if (!empty($resultado['nombre']) && !empty($resultado['apellido']) && !empty($resultado['password']) && !empty($resultado['cargo'])) {
                // Si ya tiene nombre, apellido, contraseña y cargo, mostrar mensaje de error
                ?>
                <div class="alert show">
                    <span class="fas fa-exclamation-circle"></span>
                    <span class="msg"><?php echo "Este E-mail ya está en uso"; ?></span>
                    <span class="close-btn">
                        <span class="fas fa-times"></span>
                    </span>
                </div>
                <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
                <script>
                    // Mostrar y ocultar la alerta de error
                    $('.alert').removeClass("hide");
                    $('.alert').addClass("show");
                    $('.alert').addClass("showAlert");
                    setTimeout(function() {
                        $('.alert').removeClass("show");
                        $('.alert').addClass("hide");
                    }, 5000);

                    $('.close-btn').click(function() {
                        $('.alert').removeClass("show");
                        $('.alert').addClass("hide");
                    });
                </script>
                <?php
                return;
            }
        } else {
            // Si el correo no existe, mostrar mensaje de error
            ?>
            <div class="alert show">
                <span class="fas fa-exclamation-circle"></span>
                <span class="msg"><?php echo "Este E-mail no existe"; ?></span>
                <span class="close-btn">
                    <span class="fas fa-times"></span>
                </span>
            </div>
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <script>
                // Mostrar y ocultar la alerta de error
                $('.alert').removeClass("hide");
                $('.alert').addClass("show");
                $('.alert').addClass("showAlert");
                setTimeout(function() {
                    $('.alert').removeClass("show");
                    $('.alert').addClass("hide");
                }, 5000);

                $('.close-btn').click(function() {
                    $('.alert').removeClass("show");
                    $('.alert').addClass("hide");
                });
            </script>
            <?php
            return; // Finaliza la función aquí si el correo no existe
        }

        // Encriptar la contraseña
        $password = hash('sha512', $password);

        // Preparar y ejecutar la consulta SQL para actualizar el nuevo usuario
        $statement = $conexion->prepare('UPDATE usuario SET nombre = :nombre, apellido = :apellido, cargo = :cargo, password = :password WHERE email = :email');
        $statement->execute(array(
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':email' => $email,
            ':cargo' => $cargo,
            ':password' => $password
        ));
        ?>
        <div class="alert show">
            <span class="fa-solid fa-check"></span>
            <span class="msg"><?php echo "Usuario registrado correctamente"; ?></span>
            <span class="close-btn">
                <span class="fas fa-times"></span>
            </span>
        </div>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script>
            // Mostrar y ocultar la alerta de éxito
            $('.alert').removeClass("hide");
            $('.alert').addClass("show");
            $('.alert').addClass("showAlert");
            setTimeout(function() {
                $('.alert').removeClass("show");
                $('.alert').addClass("hide");
            }, 5000);

            $('.close-btn').click(function() {
                $('.alert').removeClass("show");
                $('.alert').addClass("hide");
            });
        </script>
        <?php
    }
}

// Incluir la vista del formulario de login
require '../vista/login.view.php';
?>