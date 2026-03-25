const reservasCentro = window.RESERVAS_CENTRO || 'secundaria';
let reservasCargo = '';

$(document).ready(function () {
    function mostrarAlerta(mensaje) {
        const msg = `
        <div class="alert show">
            <span class="fa-solid fa-check"></span>
            <span class="msg">${mensaje}</span>
            <span class="close-btn">
                <span class="fas fa-times"></span>
            </span>
        </div>
        `;

        $('body').append(msg);
        $('.alert').removeClass('hide').addClass('show showAlert');

        setTimeout(function () {
            $('.alert').removeClass('show').addClass('hide');
        }, 5000);

        $('.close-btn').click(function () {
            $('.alert').removeClass('show').addClass('hide');
        });
    }

    function cargarReservas() {
        $.ajax({
            url: 'modelo/ObtenerReservas.php',
            type: 'GET',
            data: { centro: reservasCentro },
            dataType: 'json',
            success: function (data) {
                $('.Container').empty();

                if (!Array.isArray(data) || data.length === 0) {
                    mostrarMensajeNoReservas();
                    return;
                }

                let opcionOrdenar = $('#ordenar').val();
                data.sort(function (a, b) {
                    if (opcionOrdenar === 'az') {
                        return a.nombre.localeCompare(b.nombre);
                    }
                    if (opcionOrdenar === 'za') {
                        return b.nombre.localeCompare(a.nombre);
                    }
                    return 0;
                });

                data.forEach(function (reserva) {
                    Reserva(
                        reserva.id,
                        reserva.nombre,
                        reserva.capacidad,
                        reserva.fecha,
                        reserva.hora,
                        reserva.imagen,
                        reserva.insumo,
                        reserva.observacion
                    );
                });
            },
            error: function (xhr, status, error) {
                console.error('Error en la solicitud AJAX: ', status, error);
                console.error('Respuesta del servidor: ', xhr.responseText);
            }
        });
    }

    function cargarSalasEnFormulario() {
        $.ajax({
            url: 'modelo/ObtenerSalas.php',
            method: 'GET',
            data: { centro: reservasCentro },
            dataType: 'json',
            success: function (salas) {
                let selectSala = $('#sala');
                selectSala.empty();
                selectSala.append('<option value="">Selecciona una sala</option>');

                salas.forEach(function (sala) {
                    selectSala.append(
                        '<option value="' + sala.id + '">' + sala.nombre + ' (Capacidad: ' + sala.capacidad + ')</option>'
                    );
                });

                $('#formularioEditarReserva').fadeIn();
            },
            error: function () {
                alert('Hubo un problema al obtener las salas.');
            }
        });
    }

    $.ajax({
        url: 'Controlador/session.php',
        type: 'GET',
        success: function (response) {
            if (typeof response === 'string') {
                response = JSON.parse(response);
            }

            reservasCargo = response.cargo || '';
            cargarReservas();
        },
        error: function () {
            cargarReservas();
        }
    });

    $('#ordenar').change(function () {
        cargarReservas();
    });

    $('#EliminarReserva').hide();
    $('#formularioEditarReserva').hide();

    $(document).on('click', '.btnEliminar', function () {
        let id = $(this).data('id');
        $('#btnEliminarReserva').data('id', id);
        $('#EliminarReserva').fadeIn();
    });

    $('#btnCerrarEliminar').on('click', function () {
        $('#EliminarReserva').fadeOut();
    });

    $('#btnEliminarReserva').on('click', function () {
        let id = $(this).data('id');

        $.ajax({
            url: 'modelo/EliminarReserva.php',
            type: 'POST',
            dataType: 'json',
            data: { id_reserva: id },
            success: function (response) {
                $('#EliminarReserva').fadeOut();
                mostrarAlerta(response.message || 'Reserva eliminada exitosamente');
                if (response.status === 'success') {
                    cargarReservas();
                }
            },
            error: function (xhr, status, error) {
                console.error('Error en la solicitud AJAX: ', status, error);
                console.error('Respuesta del servidor: ', xhr.responseText);
            }
        });
    });

    $(document).on('click', '.btnEditar', function () {
        let idReserva = $(this).data('id');
        $('#id_reserva').val(idReserva);
        cargarSalasEnFormulario();
    });

    $('#btnCerrarFormulario').on('click', function () {
        $('#formularioEditarReserva').fadeOut();
    });

    $('#formEditarReserva').submit(function (e) {
        e.preventDefault();

        let formData = {
            id_reserva: $('#id_reserva').val(),
            sala: $('#sala').val(),
            fecha: $('#fecha').val(),
            hora_inicio: $('#hora_inicio').val(),
            hora_fin: $('#hora_fin').val(),
            observacion: $('#observaciones').val(),
            insumo: $('#insumo').val(),
            limpieza: $('#toggleSwitch').prop('checked'),
            centro: reservasCentro
        };

        $.ajax({
            url: 'modelo/EditarReserva.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                mostrarAlerta(response.message);
                if (response.status === 'success') {
                    $('#formularioEditarReserva').fadeOut();
                    cargarReservas();
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert('Hubo un problema al procesar la solicitud: ' + error);
            }
        });
    });
});

function Reserva(id, nombre, capacidad, fecha, hora, imagen, insumo, observacion) {
    insumo = insumo || 'No hay insumos';
    observacion = observacion || 'No hay observaciones';

    let accionesHTML = '';
    if (reservasCargo === 'operativo') {
        accionesHTML = `
            <div class="btnEliminar Eliminar" data-id="${id}">
                <img src="vista/img/eliminar.png" alt="eliminar" id="Eliminar">
            </div>
            <div class="btnEditar Editar" data-id="${id}">
                <img src="vista/img/lapiz.png" alt="editar" id="Editar">
            </div>`;
    }

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
            ${accionesHTML}
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
