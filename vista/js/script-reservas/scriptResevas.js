const reservasCentro = window.RESERVAS_CENTRO || 'secundaria';
let reservasCargo = '';

$(document).ready(function () {
    let reservasPorId = {};

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
            url: 'Controlador/Reservas/ObtenerReservas.php',
            type: 'GET',
            data: { centro: reservasCentro },
            cache: false,
            dataType: 'json',
            success: function (data) {
                $('.Container').empty();
                reservasPorId = {};

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
                    reservasPorId[reserva.id] = reserva;
                    Reserva(
                        reserva.id,
                        reserva.nombre,
                        reserva.capacidad,
                        reserva.fecha,
                        reserva.hora,
                        reserva.imagen,
                        reserva.insumo,
                        reserva.observacion,
                        reserva.sala_id,
                        reserva.hora_inicio,
                        reserva.hora_fin
                    );
                });
            },
            error: function (xhr, status, error) {
                console.error('Error en la solicitud AJAX: ', status, error);
                console.error('Respuesta del servidor: ', xhr.responseText);
            }
        });
    }

    function normalizarHora(hora) {
        return hora ? hora.toString().substring(0, 5) : '';
    }

    function cargarSalasEnFormulario(salaSeleccionada) {
        $.ajax({
            url: 'Controlador/Salas/ObtenerSalas.php',
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

                if (salaSeleccionada) {
                    selectSala.val(String(salaSeleccionada));
                }

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
            url: 'Controlador/Reservas/EliminarReserva.php',
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
        let reserva = reservasPorId[idReserva];

        if (!reserva) {
            alert('No se pudieron cargar los datos de la reserva.');
            return;
        }

        $('#id_reserva').val(idReserva);
        $('#fecha').val(reserva.fecha || '');
        $('#hora_inicio').val(normalizarHora(reserva.hora_inicio));
        $('#hora_fin').val(normalizarHora(reserva.hora_fin));
        $('#formEditarReserva').find('[name="insumo"]').val(reserva.insumo || '');
        $('#formEditarReserva').find('[name="observacion_editar"]').val(reserva.observacion || '');
        $('#toggleSwitch').prop('checked', false);
        cargarSalasEnFormulario(reserva.sala_id);
    });

    $('#btnCerrarFormulario').on('click', function () {
        $('#formularioEditarReserva').fadeOut();
    });

    $('#formEditarReserva').submit(function (e) {
        e.preventDefault();

        let form = $(this);
        let formData = {
            id_reserva: form.find('[name="id_reserva"]').val(),
            sala: form.find('[name="sala"]').val(),
            fecha: form.find('[name="fecha"]').val(),
            hora_inicio: form.find('[name="hora_inicio"]').val(),
            hora_fin: form.find('[name="hora_fin"]').val(),
            insumo: form.find('[name="insumo"]').val(),
            observacion_editar: form.find('[name="observacion_editar"]').val(),
            limpieza: form.find('#toggleSwitch').prop('checked') ? 1 : 0,
            centro: reservasCentro
        };

        $.ajax({
            url: 'Controlador/Reservas/EditarReserva.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                mostrarAlerta(response.message);
                if (response.status === 'success') {
                    let idReserva = formData.id_reserva;
                    let tarjeta = $(`.Reserva[data-id="${idReserva}"]`);
                    let textoSala = form.find('[name="sala"] option:selected').text();
                    let observacionTexto = formData.observacion_editar || 'No hay observaciones';
                    let insumoTexto = formData.insumo || 'No hay insumos';
                    let horaTexto = `${formData.hora_inicio} - ${formData.hora_fin}`;

                    if (reservasPorId[idReserva]) {
                        reservasPorId[idReserva].fecha = formData.fecha;
                        reservasPorId[idReserva].hora_inicio = formData.hora_inicio;
                        reservasPorId[idReserva].hora_fin = formData.hora_fin;
                        reservasPorId[idReserva].hora = horaTexto;
                        reservasPorId[idReserva].insumo = formData.insumo;
                        reservasPorId[idReserva].observacion = formData.observacion_editar;
                        reservasPorId[idReserva].sala_id = formData.sala;
                        reservasPorId[idReserva].capacidad = textoSala || reservasPorId[idReserva].capacidad;
                    }

                    tarjeta.find('.reserva-sala span').text(textoSala);
                    tarjeta.find('.reserva-fecha span').text(formData.fecha);
                    tarjeta.find('.reserva-hora span').text(horaTexto);
                    tarjeta.find('.reserva-insumo span').text(insumoTexto);
                    tarjeta.find('.reserva-observacion span').text(observacionTexto);

                    $('#formularioEditarReserva').fadeOut();
                    form[0].reset();
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert('Hubo un problema al procesar la solicitud: ' + error);
            }
        });
    });
});

function Reserva(id, nombre, capacidad, fecha, hora, imagen, insumo, observacion, salaId, horaInicio, horaFin) {
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
                <p class="texto reserva-sala">Espacio: <span>${capacidad}</span></p>
                <p class="texto reserva-fecha">Fecha: <span>${fecha}</span></p>
                <p class="texto reserva-hora">Hora: <span>${hora}</span></p>
                <p class="texto reserva-insumo">Insumo: <span>${insumo}</span></p>
                <p class="texto reserva-observacion">Observaciones: <span>${observacion}</span></p>
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
