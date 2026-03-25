const agendaCentro = window.AGENDA_CENTRO || 'secundaria';

$(document).ready(function () {
    let userCargo = '';
    let usuario = '';

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

    function cargarSalas() {
        $.ajax({
            url: 'modelo/ObtenerSalas.php',
            type: 'GET',
            data: { centro: agendaCentro },
            dataType: 'json',
            success: function (data) {
                $('.ContenedorSalas').empty();

                if (!Array.isArray(data) || data.length === 0) {
                    $('.ContenedorSalas').append('<p class="mensaje-salas">No hay salas disponibles en este momento.</p>');
                    return;
                }

                data.forEach(function (sala) {
                    let recursos = sala.recursos.length > 0 ? sala.recursos.join(', ') : 'Sin recursos';
                    agregarSala(sala.id, sala.nombre, sala.capacidad, recursos, sala.imagen);
                });
            },
            error: function (xhr, status, error) {
                console.error('Error al obtener las salas:', error);
            }
        });
    }

    function actualizarVisibilidadFormulario() {
        $('#formularioAgregarSala').hide();
        $('#EliminarSala').hide();
        $('#formularioEditarSala').hide();
    }

    function obtenerRecursosComoArray(recursos) {
        return recursos
            .split(',')
            .map(function (recurso) {
                return recurso.trim();
            })
            .filter(Boolean);
    }

    $.ajax({
        url: 'Controlador/session.php',
        type: 'GET',
        success: function (response) {
            if (typeof response === 'string') {
                response = JSON.parse(response);
            }

            userCargo = response.cargo;
            usuario = response.usuario;
            cargarSalas();
        },
        error: function (xhr, status, error) {
            console.error('Error en la solicitud:', error);
            cargarSalas();
        }
    });

    document.getElementById('buscar').addEventListener('keyup', function () {
        let query = this.value.toLowerCase();
        let salas = document.querySelectorAll('.Sala');

        salas.forEach(function (sala) {
            let textoSala = sala.innerText.toLowerCase();
            sala.style.display = textoSala.includes(query) ? 'flex' : 'none';
        });
    });

    actualizarVisibilidadFormulario();

    $('#btnAgregarSala').on('click', function () {
        $('#formularioAgregarSala').fadeIn();
    });

    $('#btnCerrarFormulario').on('click', function () {
        $('#formularioAgregarSala').fadeOut();
    });

    $('#btnGuardarSala').on('click', function () {
        let formData = new FormData($('#formAgregarSala')[0]);
        formData.append('centro', agendaCentro);

        $.ajax({
            url: 'modelo/AgregarSala.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                mostrarAlerta(response.message || 'Sala agregada correctamente');
                if (response.status === 'success') {
                    $('.mensaje-salas').remove();
                    $('#formularioAgregarSala').fadeOut();
                    $('#formAgregarSala')[0].reset();
                    cargarSalas();
                }
            }
        });
    });

    $(document).on('click', '.btnEliminar', function () {
        let id = $(this).data('id');
        $('#btnEliminarSala').data('id', id);
        $('#EliminarSala').fadeIn();
    });

    $('#btnCerrarEliminar').on('click', function () {
        $('#EliminarSala').fadeOut();
    });

    $('#btnEliminarSala').on('click', function () {
        let id = $(this).data('id');

        $.ajax({
            url: 'modelo/EliminarSala.php',
            type: 'POST',
            dataType: 'json',
            data: { id: id, centro: agendaCentro },
            success: function (response) {
                mostrarAlerta(response.message || 'Operacion completada');
                $('#EliminarSala').fadeOut();
                if (response.status === 'success') {
                    cargarSalas();
                }
            },
            error: function (xhr, status, error) {
                console.error('Error al eliminar la sala:', error);
                mostrarAlerta('Error al eliminar la sala.');
            }
        });
    });

    $(document).on('click', '.btnEditar', function () {
        let salaId = $(this).data('id');
        let card = $('#sala-' + salaId);

        $('#btnEditarSala').data('id', salaId);
        $('#nombreSalaEditar').val(card.find('.nombreSala').text().trim());
        $('#capacidadSalaEditar').val(card.find('.capacidadSala').text().trim());
        $('#recursosSalaEditar').val(card.find('.recursosSala').text().trim());
        $('#formularioEditarSala').fadeIn();
    });

    $('#btnCerrarEditar').on('click', function () {
        $('#formularioEditarSala').fadeOut();
    });

    $('#btnEditarSala').click(function () {
        let salaId = $(this).data('id');
        let nombre = $('#nombreSalaEditar').val().trim();
        let capacidad = $('#capacidadSalaEditar').val().trim();
        let recursos = $('#recursosSalaEditar').val().trim();
        let imagenInput = $('#imagenEditar')[0];

        if (!nombre || !capacidad || !recursos) {
            alert('Por favor, completa todos los campos.');
            return;
        }

        let formData = new FormData();
        if (imagenInput.files.length > 0) {
            formData.append('imagen', imagenInput.files[0]);
        }

        formData.append('id', salaId);
        formData.append('nombre', nombre);
        formData.append('capacidad', capacidad);
        formData.append('recursos', recursos);
        formData.append('centro', agendaCentro);

        $.ajax({
            url: 'modelo/EditarSala.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                mostrarAlerta(response.message || 'Sala actualizada exitosamente');
                if (response.status === 'success') {
                    $('#formularioEditarSala').fadeOut();
                    cargarSalas();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error en la peticion AJAX: ', textStatus, errorThrown);
            }
        });
    });

    function agregarSala(id, nombre, capacidad, recursos, imagen) {
        let salaHTML = `
        <div class="Sala" id="sala-${id}">
            <div class="SalaInfo">
                <img src="${imagen}" alt="ImagenSala" class="SalaImagen">
                <div>
                    <p>Nombre de la sala: <span class="nombreSala">${nombre}</span></p>
                    <p>Capacidad de personas: <span class="capacidadSala">${capacidad}</span></p>
                    <p>Recursos de sala: <span class="recursosSala">${recursos}</span></p>
                </div>
            </div>
            <button class="Agendar" data-id="${id}">Agendar</button>
            <div class="Tapar"></div>`;

        if (userCargo === 'operativo') {
            salaHTML += `
            <div class="btnEliminar Eliminar" data-id="${id}">
                <img src="vista/img/eliminar.png" alt="eliminar" id="Eliminar">
            </div>
            <div class="btnEditar Editar" data-id="${id}">
                <img src="vista/img/lapiz.png" alt="editar" id="Editar">
            </div>`;
        }

        salaHTML += `</div>`;
        $('.ContenedorSalas').append(salaHTML);
    }

    $(document).off('click', '.Agendar').on('click', '.Agendar', function () {
        let salaId = $(this).data('id');
        let salaImagen = $(this).parent().find('.SalaImagen').attr('src');

        if ($('.todo').length === 0) {
            $('.ContenedorSalas').append(`
                <div class="todo">
                    <div class="cerrar"><h1>x</h1></div>
                    <div class="container">
                        <div class="formularioDiv">
                            <div class="img">
                                <img src="${salaImagen}" alt="" class="salon">
                            </div>
                            <div>
                                <form method="post" enctype="multipart/form-data">
                                    <label for="fechaYHora" id="labelFechaYHora" class="label">Fecha y hora</label> <br>
                                    <input type="date" name="fecha" class="inputForm" id="fecha">
                                    <input type="time" name="horaI" class="inputForm" id="horaI">
                                    <div class="divI" id="divInfoHoraI">
                                        <p class="i" id="infoDeHoraInicio">i</p>
                                    </div>
                                    <input type="time" name="horaF" class="inputForm" id="horaF">
                                    <div class="divI" id="divInfoHoraF">
                                        <p class="i" id="infoDeHoraFin">i</p>
                                    </div>
                                    <label for="insumos" class="label">Insumos</label>
                                    <div class="divI" id="divInfoInsumos">
                                        <p class="i" id="infoDeInsumos">i</p>
                                    </div>
                                    <label for="observaciones" class="label" id="lObs">Observaciones</label>
                                    <div class="divI" id="divInfoObservaciones">
                                        <p class="i" id="infoDeObservaciones">i</p>
                                    </div>
                                    <textarea name="insumos" class="inputForm" id="insumos"></textarea>
                                    <textarea name="observaciones" class="inputForm" id="observaciones"></textarea>
                                    <label for="serLimpieza" class="label" id="servLimpieza">Servicio de limpieza</label>
                                    <div class="divI" id="divInfoLimpieza">
                                        <p class="i" id="infoDeLimpieza">i</p>
                                    </div>
                                    <label class="switch">
                                        <input type="checkbox" id="toggleSwitch">
                                        <span class="slider"></span>
                                    </label>
                                    <button type="button" class="agendar" id="agendar" name="Agendar">Agendar</button>
                                </form>
                            </div>
                        </div>
                        <div class="calendarioDiv">
                            <div class="vistas">
                                <button class="vista" id="diario"><h1>Dia</h1></button>
                                <button class="vista" id="semanal"><h1>Semana</h1></button>
                                <button class="vista" id="mensual"><h1>Mes</h1></button>
                            </div>
                            <div class="row align-items-start" id="dias">
                                <div class="col">
                                    <div class="retroceder">
                                        <img src="vista/img/flecha-calendario-izquierda.png" alt="" class="flechaRetroceder">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="info">
                                        <h1 class="numDia-mes">24</h1>
                                        <h3 class="mes-anio">enero</h3>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="avanzar">
                                        <img src="vista/img/flecha-calendario-derecha.png" alt="" class="flechaAvanzar">
                                    </div>
                                </div>
                            </div>
                            <div class="calendario">
                                <div class="diasSemana">
                                    <div class="diaSemana" id="do"><h1>D</h1></div>
                                    <div class="diaSemana" id="lu"><h1>L</h1></div>
                                    <div class="diaSemana" id="ma"><h1>M</h1></div>
                                    <div class="diaSemana" id="mi"><h1>M</h1></div>
                                    <div class="diaSemana" id="ju"><h1>J</h1></div>
                                    <div class="diaSemana" id="vi"><h1>V</h1></div>
                                    <div class="diaSemana" id="sa"><h1>S</h1></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);

            $('#agendar').click(function () {
                let fecha = $('#fecha').val();
                let horaI = $('#horaI').val();
                let horaF = $('#horaF').val();
                let insumos = $('#insumos').val();
                let observaciones = $('#observaciones').val();
                let limpieza = $('#toggleSwitch').prop('checked');

                $.ajax({
                    url: 'modelo/AgregarReserva.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        fecha: fecha,
                        horaI: horaI,
                        horaF: horaF,
                        insumos: insumos,
                        observaciones: observaciones,
                        limpieza: limpieza,
                        email: usuario,
                        sala: salaId,
                        centro: agendaCentro
                    },
                    success: function (response) {
                        mostrarAlerta(response.message || 'Sala reservada exitosamente');

                        if (response.status === 'success') {
                            $('#fecha').val('');
                            $('#horaI').val('');
                            $('#horaF').val('');
                            $('#insumos').val('');
                            $('#observaciones').val('');
                            $('#toggleSwitch').prop('checked', false);
                            $('.todo').hide();
                        }
                    }
                });
            });

            calendario(salaId);
        } else {
            $('#fecha').val('');
            $('#horaI').val('');
            $('#horaF').val('');
            $('#insumos').val('');
            $('#toggleSwitch').prop('checked', false);
            $('.todo').show();
        }
    });

    if (window.matchMedia('(max-width: 993px)').matches) {
        $(document).on('click', '#divInfoHoraI', function () {
            $('.container').append(`
                <div class="alertInfo" id="alertInfoHoraI">
                    <p>Ingrese la hora de inicio de su reserva.</p>
                </div>
            `);
        });

        $(document).on('click', '#divInfoHoraF', function () {
            $('.container').append(`
                <div class="alertInfo" id="alertInfoHoraF">
                    <p>Ingrese la hora de fin de su reserva.</p>
                </div>
            `);
        });

        $(document).on('click', '#divInfoInsumos', function () {
            $('.container').append(`
                <div class="alertInfo" id="alertInfoInsumos">
                    <p>Ingrese los insumos que necesita para su reserva.</p>
                    <p>Ej: 10 botellas de agua, merienda, etc.</p>
                </div>
            `);
        });

        $(document).on('click', '#divInfoObservaciones', function () {
            $('.container').append(`
                <div class="alertInfo" id="alertInfoObservaciones">
                    <p>Ingrese las observaciones que considere necesarias para su reserva.</p>
                    <p>Ej: decoracion, requerimientos especiales, etc.</p>
                </div>
            `);
        });

        $(document).on('click', '#divInfoLimpieza', function () {
            $('.container').append(`
                <div class="alertInfo" id="alertInfoLimpieza">
                    <p>Seleccione si desea o no servicio de limpieza luego de su reserva.</p>
                    <p>Ten en cuenta la suciedad del espacio luego de su uso.</p>
                </div>
            `);
        });

        $(document).on('click', function (event) {
            if (!$(event.target).closest('.alertInfo, .divI').length) {
                $('.alertInfo').remove();
            }
        });
    } else {
        $(document).on('mouseenter', '#divInfoHoraI', function () {
            $('.container').append(`
                <div class="alertInfo" id="alertInfoHoraI">
                    <p>Ingrese la hora de inicio de su reserva.</p>
                </div>
            `);
        });

        $(document).on('mouseenter', '#divInfoHoraF', function () {
            $('.container').append(`
                <div class="alertInfo" id="alertInfoHoraF">
                    <p>Ingrese la hora de fin de su reserva.</p>
                </div>
            `);
        });

        $(document).on('mouseenter', '#divInfoInsumos', function () {
            $('.container').append(`
                <div class="alertInfo" id="alertInfoInsumos">
                    <p>Ingrese los insumos que necesita para su reserva.</p>
                    <p>Ej: 10 botellas de agua, merienda, etc.</p>
                </div>
            `);
        });

        $(document).on('mouseenter', '#divInfoObservaciones', function () {
            $('.container').append(`
                <div class="alertInfo" id="alertInfoObservaciones">
                    <p>Ingrese las observaciones que considere necesarias para su reserva.</p>
                    <p>Ej: decoracion, requerimientos especiales, etc.</p>
                </div>
            `);
        });

        $(document).on('mouseenter', '#divInfoLimpieza', function () {
            $('.container').append(`
                <div class="alertInfo" id="alertInfoLimpieza">
                    <p>Seleccione si desea o no servicio de limpieza luego de su reserva.</p>
                    <p>Ten en cuenta la suciedad del espacio luego de su uso.</p>
                </div>
            `);
        });

        $(document).on('mouseleave', '#divInfoHoraI', function () {
            $('#alertInfoHoraI').remove();
        });

        $(document).on('mouseleave', '#divInfoHoraF', function () {
            $('#alertInfoHoraF').remove();
        });

        $(document).on('mouseleave', '#divInfoInsumos', function () {
            $('#alertInfoInsumos').remove();
        });

        $(document).on('mouseleave', '#divInfoObservaciones', function () {
            $('#alertInfoObservaciones').remove();
        });

        $(document).on('mouseleave', '#divInfoLimpieza', function () {
            $('#alertInfoLimpieza').remove();
        });
    }
});
