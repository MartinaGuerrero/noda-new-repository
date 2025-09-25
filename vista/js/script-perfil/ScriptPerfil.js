document.querySelector('.user-icon').addEventListener('click', function(event) {
    event.stopPropagation();
    const Perfil = document.querySelector('.Perfil');
    Perfil.style.display = Perfil.style.display === 'none' || Perfil.style.display === '' ? 'block' : 'none';
});

document.querySelector('.cruz').addEventListener('click', function(event) {
    event.stopPropagation();
    const Perfil = document.querySelector('.Perfil');
    Perfil.style.display = 'none';
});

document.querySelector('.Perfil').addEventListener('click', function(event) {
    event.stopPropagation();
});

document.addEventListener('click', function(event) {
    const Perfil = document.querySelector('.Perfil');
    const userIcon = document.querySelector('.user-icon');

    if (!Perfil.contains(event.target) && !userIcon.contains(event.target)) {
        Perfil.style.display = 'none';
    }
});

$('#formularioEditar').hide();

$('#editarPerfil').on('click', function(event) {
    $('#formularioEditar').fadeIn();
});

$('#btnCerrarFormularioEditar').on('click', function(event) {
    event.stopPropagation();
    $('#formularioEditar').fadeOut();
});

$('#btnGuardarPerfil').on('click', function(event) {
    event.preventDefault();
    
    let nombre = $('#nombreEditar').val();
    let apellido = $('#apellidoEditar').val();
    let password = $('#passwordEditar').val();
    let confirmarpass = $('#confirmarpassEditar').val();

    if (password !== confirmarpass) {
        const msg = `
                <div class="alert show">
                    <span class="fa-solid fa-exclamation-triangle"></span>
                    <span class="msg">Las contraseñas no coinciden</span>
                    <span class="close-btn">
                        <span class="fas fa-times"></span>
                    </span>
                </div>
                `;


                $('body').append(msg);

                // Mostrar y ocultar la alerta de éxito
                $('.alert').removeClass("hide");
                $('.alert').addClass("show");
                $('.alert').addClass("showAlert");
                setTimeout(function () {
                    $('.alert').removeClass("show");
                    $('.alert').addClass("hide");
                }, 5000);

                $('.close-btn').click(function () {
                    $('.alert').removeClass("show");
                    $('.alert').addClass("hide");
                });
                // Cierra el formulario
                $('#formularioEditar').fadeOut();
                return;
    }

    $.ajax({
        url: 'modelo/EditarPerfil.php',
        type: 'POST',
        data: {
            nombre: nombre,
            apellido: apellido,
            password: password
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) { // Cambiado de 'status' a 'success' para coincidir con el PHP
                const msg = `
                    <div class="alert show">
                        <span class="fa-solid fa-check"></span>
                        <span class="msg">${response.message}</span>
                        <span class="close-btn">
                            <span class="fas fa-times"></span>
                        </span>
                    </div>
                `;

                $('body').append(msg);

                // Cierra el formulario
                $('#formularioEditar').fadeOut();

                // Actualizar la información mostrada en el perfil
                $('.username').text(nombre + ' ' + apellido);
                $('.icon-iniciales').text(nombre.charAt(0).toUpperCase() + apellido.charAt(0).toUpperCase());
                $('.inicialesheader').text(nombre.charAt(0).toUpperCase() + apellido.charAt(0).toUpperCase());

                // Mostrar y ocultar la alerta de éxito
                $('.alert').removeClass("hide").addClass("show showAlert");
                setTimeout(function () {
                    $('.alert').removeClass("show").addClass("hide");
                }, 5000);

                $('.close-btn').click(function () {
                    $('.alert').removeClass("show").addClass("hide");
                });
            } else {
                const msg = `
                    <div class="alert show">
                        <span class="fa-solid fa-exclamation-triangle"></span>
                        <span class="msg">${response.message}</span>
                        <span class="close-btn">
                            <span class="fas fa-times"></span>
                        </span>
                    </div>
                `;

                $('body').append(msg);

                // Mostrar y ocultar la alerta de error
                $('.alert').removeClass("hide").addClass("show showAlert");
                setTimeout(function () {
                    $('.alert').removeClass("show").addClass("hide");
                }, 5000);

                $('.close-btn').click(function () {
                    $('.alert').removeClass("show").addClass("hide");
                });
            }
        },
        error: function() {
            alert('Error al procesar la solicitud');
        }
    });
});

// Cargar los datos actuales del usuario al abrir el formulario de edición
$('#editarPerfil').on('click', function() {
    $('#nombreEditar').val($('.username').text().split(' ')[0]);
    $('#apellidoEditar').val($('.username').text().split(' ')[1]);
    $('#passwordEditar').val('');
    $('#confirmarpassEditar').val('');
});