const misReservasCentro = window.RESERVAS_CENTRO || 'secundaria';
let misReservasPorId = {};

document.addEventListener('DOMContentLoaded', function () {
    fetchReservas();
});

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

$('#EliminarMisReserva').hide();

$(document).on('click', '.btnEliminar', function () {
    let id = $(this).data('id');
    $('#btnEliminarMisReserva').data('id', id);
    $('#EliminarMisReserva').fadeIn();
});

$('#btnCerrarEliminar').on('click', function () {
    $('#EliminarMisReserva').fadeOut();
});

$('#btnEliminarMisReserva').on('click', function () {
    let id = $(this).data('id');
    $.ajax({
        url: 'modelo/EliminarMisReservas.php',
        type: 'POST',
        dataType: 'json',
        data: { id_reserva: id },
        success: function (response) {
            $('#EliminarMisReserva').fadeOut();
            mostrarAlerta(response.message || 'Reserva eliminada exitosamente');
            fetchReservas();
        },
        error: function (xhr, status, error) {
            console.error('Error en la solicitud AJAX: ', status, error);
            console.error('Respuesta del servidor: ', xhr.responseText);
        }
    });
});

$(document).ready(function () {
    $('#formularioEditarMisReserva').hide();

    $(document).on('click', '.btnEditar', function () {
        let idReserva = $(this).data('id');
        let reserva = misReservasPorId[idReserva];
        $('#id_reserva').val(idReserva);

        $.ajax({
            url: 'modelo/ObtenerSalas.php',
            method: 'GET',
            data: { centro: misReservasCentro },
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

                if (reserva) {
                    $('#fecha').val(reserva.fecha || '');
                    $('#hora_inicio').val((reserva.hora_i || '').toString().substring(0, 5));
                    $('#hora_fin').val((reserva.hora_f || '').toString().substring(0, 5));
                    $('#insumo').val(reserva.insumos || '');
                    $('#observaciones').val(reserva.observacion || '');
                    $('#toggleSwitch').prop('checked', Number(reserva.limpieza) === 1);
                    selectSala.val(String(reserva.sala_id));
                }

                $('#formularioEditarMisReserva').fadeIn();
            },
            error: function () {
                alert('Hubo un problema al obtener las salas.');
            }
        });
    });

    $('#btnCerrarFormulario').on('click', function () {
        $('#formularioEditarMisReserva').fadeOut();
    });

    $('#formEditarMisReserva').submit(function (e) {
        e.preventDefault();

        let horaInicio = $('#hora_inicio').val();
        let horaFin = $('#hora_fin').val();

        if (!horaInicio || !horaFin || horaFin <= horaInicio) {
            mostrarAlerta('La hora de fin debe ser posterior a la hora de inicio');
            return;
        }

        let formData = {
            id_reserva: $('#id_reserva').val(),
            sala: $('#sala').val(),
            fecha: $('#fecha').val(),
            hora_inicio: horaInicio,
            hora_fin: horaFin,
            observacion: $('#observaciones').val(),
            insumo: $('#insumo').val(),
            limpieza: $('#toggleSwitch').prop('checked'),
            centro: misReservasCentro
        };

        $.ajax({
            url: 'modelo/EditarMisReservas.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                mostrarAlerta(response.message);
                if (response.status === 'success') {
                    $('#formularioEditarMisReserva').fadeOut();
                    fetchReservas();
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert('Hubo un problema al procesar la solicitud: ' + error);
            }
        });
    });
});

function fetchReservas() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'modelo/ObtenerMisReservas.php?centro=' + encodeURIComponent(misReservasCentro), true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                var data = JSON.parse(xhr.responseText);
                var reservasContainer = document.getElementById('reservas-container');

                if (data.error) {
                    console.error('Error:', data.error);
                    reservasContainer.innerHTML = `<p>Error al cargar las reservas: ${data.error}</p>`;
                } else if (data.length > 0) {
                    reservasContainer.innerHTML = '';
                    misReservasPorId = {};
                    data.forEach(function (reserva) {
                        misReservasPorId[reserva.id] = reserva;
                        var reservaDiv = document.createElement('div');
                        reservaDiv.classList.add('reserva');

                        reservaDiv.innerHTML = `
                            <img src="${reserva.imagen}" alt="${reserva.nom_sala}" class="SalaImg">
                            <div class="reservainfo">
                                <p class="texto">Sala reservada: ${reserva.nom_sala}</p>
                                <p class="texto">Fecha: ${reserva.fecha}</p>
                                <p class="texto">Hora de inicio: ${reserva.hora_i}</p>
                                <p class="texto">Hora de finalizacion: ${reserva.hora_f}</p>
                                <p class="texto">Insumos: ${reserva.insumos || 'No se solicitaron insumos'}</p>
                                <p class="texto">Limpieza: ${Number(reserva.limpieza) === 1 ? 'Sí' : 'No'}</p>
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

    xhr.onerror = function () {
        console.error('Error de red al intentar cargar las reservas.');
        document.getElementById('reservas-container').innerHTML = '<p>Error de red al cargar las reservas.</p>';
    };

    xhr.send();
}

