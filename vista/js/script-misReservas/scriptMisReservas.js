document.addEventListener("DOMContentLoaded", function() {
    fetchReservas();
});

$('#EliminarMisReserva').hide();

    // Asocia el evento de clic al botón "Eliminar" de cada sala
    $(document).on('click', '.btnEliminar', function() {
        let id = $(this).data('id');  // Obtén el ID de la sala desde el botón presionado
        $('#btnEliminarMisReserva').data('id', id);
        $('#EliminarMisReserva').fadeIn();
    });

    $('#btnCerrarEliminar').on('click', function() {
        $('#EliminarMisReserva').fadeOut();
    });

    $('#btnEliminarMisReserva').on('click', function() {
        let id = $(this).data('id');
        $.ajax({
            url: 'modelo/EliminarMisReservas.php',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                $('#EliminarMisReserva').fadeOut();
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
                fetchReservas();
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", status, error);
                console.error("Respuesta del servidor: ", xhr.responseText);
            }
        });
    });

    // editar reservas
$(document).ready(function() {
    $('#formularioEditarMisReserva').hide();  // Oculta el formulario al cargar la página

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
                $('#formularioEditarMisReserva').fadeIn();
            },
            error: function() {
                alert('Hubo un problema al obtener las salas.');
            }
        });
    });

    // Cerrar el formulario al hacer clic en el botón "Cancelar"
    $('#btnCerrarFormulario').on('click', function() {
        $('#formularioEditarMisReserva').fadeOut();
    });

    $('#formEditarMisReserva').submit(function(e) {
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
            url: 'modelo/EditarMisReservas.php',  // Ajusta la ruta al archivo PHP
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
                   $('#formularioEditarMisReserva').fadeOut();
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

function fetchReservas() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'modelo/ObtenerMisReservas.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var data = JSON.parse(xhr.responseText);
                var reservasContainer = document.getElementById('reservas-container');

                if (data.error) {
                    console.error('Error:', data.error);
                    reservasContainer.innerHTML = `<p>Error al cargar las reservas: ${data.error}</p>`;
                } else if (data.length > 0) {
                    reservasContainer.innerHTML = ''; // Limpiar el contenedor
                    data.forEach(function(reserva) {
                        var reservaDiv = document.createElement('div');
                        reservaDiv.classList.add('reserva');
                
                        reservaDiv.innerHTML = `
                            <img src="${reserva.imagen}" alt="${reserva.nom_sala}" class="SalaImg">
                            <div class="reservainfo">
                                <p class="texto">Sala reservada: ${reserva.nom_sala}</p>
                                <p class="texto">Fecha: ${reserva.fecha}</p>
                                <p class="texto">Hora de inicio: ${reserva.hora_i}</p>
                                <p class="texto">Hora de finalización: ${reserva.hora_f}</p>
                                <p class="texto">Insumos: ${reserva.insumos || 'No se solicitaron insumos'}</p>
                                <p class="texto">Observaciones: ${reserva.observacion || 'Sin observaciones'}</p>
                            </div>
                            <div class="Tapar"></div>
                            <div class="btnEliminar Eliminar" data-id="${reserva.id}">
                                <img src="vista/img/eliminar.png" alt="eliminar" id="Eliminar">
                            </div>
                            <div class="btnEditar Editar" data-id="${reserva.id}">
                                <img src="vista/img/lapiz.png" alt="editar" id="Editar">
                            </div>
                        `;
                
                        reservasContainer.appendChild(reservaDiv);
                    });
                } else {
                    reservasContainer.innerHTML = `
                    <div class="sin-reservas">
                        <p class="texto-sin-reservas">No tienes reservas.</p>
                    </div>`;
                }
            } catch (e) {
                console.error('Error al parsear JSON:', e);
                reservasContainer.innerHTML = '<p>Error al procesar los datos de las reservas.</p>';
            }
        } else {
            console.error('Error al cargar las reservas:', xhr.statusText);
            document.getElementById('reservas-container').innerHTML = '<p>Error al cargar las reservas.</p>';
        }
    };

    xhr.onerror = function() {
        console.error('Error de red al intentar cargar las reservas.');
        document.getElementById('reservas-container').innerHTML = '<p>Error de red al cargar las reservas.</p>';
    };

    xhr.send();
}