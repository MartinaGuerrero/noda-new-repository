$(document).ready(function() {
    // Función para obtener las reservas y agregarlas al contenedor
    function cargarReservas() {
        $.ajax({
            url: 'modelo/ObtenerReservas.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('.Container').empty(); // Limpiar el contenedor antes de agregar nuevas reservas
                
                if (data.length === 0) {
                    // Si no hay reservas, mostramos un mensaje
                    mostrarMensajeNoReservas();
                } else {
                    // Ordenar los datos según el filtro seleccionado
                    let opcionOrdenar = $('#ordenar').val();
                    data.sort(function(a, b) {
                        if (opcionOrdenar === 'az') {
                            return a.nombre.localeCompare(b.nombre);
                        } else if (opcionOrdenar === 'za') {
                            return b.nombre.localeCompare(a.nombre);
                        }
                        return 0;
                    });
                    
                    // Agregar las reservas al contenedor
                    data.forEach(function(sala) {
                        Reserva(sala.id, sala.nombre, sala.capacidad, sala.fecha, sala.hora, sala.imagen, sala.insumo, sala.observacion);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", status, error);
                console.error("Respuesta del servidor: ", xhr.responseText);
            }
        });
    }

    // Cargar reservas al cargar la página
    cargarReservas();

    // Actualizar las reservas cuando cambia la opción de ordenamiento
    $('#ordenar').change(function() {
        cargarReservas();
    });

    // Eliminar reservas
    $('#EliminarReserva').hide();

    // Asocia el evento de clic al botón "Eliminar" de cada sala
    $(document).on('click', '.btnEliminar', function() {
        let id = $(this).data('id');  // Obtén el ID de la sala desde el botón presionado
        $('#btnEliminarReserva').data('id', id);
        $('#EliminarReserva').fadeIn();
    });

    $('#btnCerrarEliminar').on('click', function() {
        $('#EliminarReserva').fadeOut();
    });

    $('#btnEliminarReserva').on('click', function() {
        let id = $(this).data('id');
        $.ajax({
            url: 'modelo/EliminarReserva.php',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                $('#EliminarReserva').fadeOut();
                const msg = `
                <div class="alert show">
                    <span class="fa-solid fa-check"></span>
                    <span class="msg">Reserva eliminada exitosamente</span>
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
                cargarReservas();
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", status, error);
                console.error("Respuesta del servidor: ", xhr.responseText);
            }
        });
    });
});

// editar reservas
$(document).ready(function() {
    $('#formularioEditarReserva').hide();  // Oculta el formulario al cargar la página

    // Al hacer clic en el botón de editar
    $(document).on('click', '.btnEditar', function() {
        let idReserva = $(this).data('id');  // Obtiene el ID de la reserva desde el atributo data-id
        $('#id_reserva').val(idReserva);  // Establece el valor del campo oculto con el ID de la reserva

        // Hacer una petición AJAX para obtener las salas
        $.ajax({
            url: 'modelo/ObtenerSalas.php',  // Cambia esta ruta según la ubicación del archivo PHP
            method: 'GET',
            dataType: 'json',
            success: function(salas) {
                let selectSala = $('#sala');  // Selecciona el <select> del formulario
                selectSala.empty();  // Limpia las opciones actuales
                selectSala.append('<option value="">Selecciona una sala</option>');  // Opción predeterminada

                // Itera sobre cada sala y agrega las opciones al <select>
                salas.forEach(function(sala) {
                    selectSala.append('<option value="' + sala.id + '">' + sala.nombre + ' (Capacidad: ' + sala.capacidad + ')</option>');
                });

                // Muestra el formulario de edición con el ID de la reserva
                $('#formularioEditarReserva').fadeIn();
            },
            error: function() {
                alert('Hubo un problema al obtener las salas.');
            }
        });
    });

    // Cerrar el formulario al hacer clic en el botón "Cancelar"
    $('#btnCerrarFormulario').on('click', function() {
        $('#formularioEditarReserva').fadeOut();
    });

    $('#formEditarReserva').submit(function(e) {
        e.preventDefault();  // Evita el comportamiento predeterminado del formulario
    
        // Captura los datos del formulario, incluido el ID de la reserva
        let formData = {
            id_reserva: $('#id_reserva').val(),  // ID de la reserva obtenido del clic
            sala: $('#sala').val(),
            fecha: $('#fecha').val(),
            hora_inicio: $('#hora_inicio').val(),
            hora_fin: $('#hora_fin').val(),
            observacion: $('#observacion').val(),
            insumo: $('#insumo').val(),
            limpieza: $('#toggleSwitch').prop('checked')
        };

        console.log(formData);
    
        // Enviar los datos mediante AJAX a EditarReserva.php
        $.ajax({
            url: 'modelo/EditarReserva.php',  // Ajusta la ruta al archivo PHP
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.status === 'success') {
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
                   $('#formularioEditarReserva').fadeOut();
                } else {
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
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Hubo un problema al procesar la solicitud: ' + error);
            }
        });
    });
});


function Reserva(id, nombre, capacidad, fecha, hora, imagen, insumo, observacion) {
    insumo = insumo || "No hay insumos";
    observacion = observacion || "No hay observaciones";
    let reservaHTML = `
        <div class="Reserva" data-id="${id}">
            <img src="${imagen}" alt="Imagen de la Reserva" class="SalaImg">
            <div class="ReservaInfo">
                <p class="texto">Reservada por: <span>${nombre}</span></p>
                <p class="texto">Espacio: <span>${capacidad}</span></p>
                <p class="texto">Fecha: <span>${fecha}</span></p>
                <p class="texto">Hora: <span>${hora}</span></p>
                <p class="texto">Insumo: <span>${insumo}</span></p>
                <p class="texto">Observaciones: <span>${observacion}</span></p>
            </div>
            <div class="Tapar"></div>
            <div class="btnEliminar Eliminar" data-id="${id}">
                <img src="vista/img/eliminar.png" alt="eliminar" id="Eliminar">
            </div>
            <div class="btnEditar Editar" data-id="${id}">
                <img src="vista/img/lapiz.png" alt="editar" id="Editar">
            </div>
        </div>`;

    $('.Container').append(reservaHTML);
}

function mostrarMensajeNoReservas() {
    let mensajeHTML = `
    <div class="No-reservas">
        <p class="texto-no-reservas">No hay reservas en este momento.</p>
    </div>`;

    $('.Container').append(mensajeHTML);
}
