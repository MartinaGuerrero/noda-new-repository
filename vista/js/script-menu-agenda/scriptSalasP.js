
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

        // Mostrar y ocultar la alerta de éxito
        $('.alert').removeClass("hide").addClass("show showAlert");
        setTimeout(function () {
            $('.alert').removeClass("show").addClass("hide");
        }, 5000);

        $('.close-btn').click(function () {
            $('.alert').removeClass("show").addClass("hide");
        });
    }

    let userCargo = '';
    let usuario = '';

    // Obtener la carga y el email del usuario a través de la sesión
    $.ajax({
        url: 'Controlador/session.php',
        type: 'GET',
        success: function (response) {
            if (typeof response === 'string') {
                response = JSON.parse(response);
            }
            userCargo = response.cargo;
            usuario = response.usuario;
            console.log(userCargo);
        },
        error: function (xhr, status, error) {
            console.error('Error en la solicitud:', error);
        }
    });

    // Obtener las salas disponibles
    $.ajax({
        url: 'modelo/ObtenerSalasP.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log('Respuesta del servidor:', data);

            if (data.length === 0) {
                $('.ContenedorSalas').append('<p class="mensaje-salas">No hay salas disponibles en este momento.</p>');
            } else {
                data.forEach(function (sala) {
                    let recursos = sala.recursos.length > 0 ? sala.recursos.join(', ') : 'Sin recursos';
                    agregarSala(sala.id, sala.nombre, sala.capacidad, recursos, sala.imagen);
                });
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al obtener las salas:', error);
        }
    });

    // Lógica del buscador
    document.getElementById('buscar').addEventListener('keyup', function () {
        let query = this.value.toLowerCase();
        let reservas = document.querySelectorAll('.Sala');
        reservas.forEach(function (reserva) {
            let textoReserva = reserva.innerText.toLowerCase();
            reserva.style.display = textoReserva.includes(query) ? 'flex' : 'none';
        });
    });

    // Manejar formulario de agregar sala
    $('#formularioAgregarSala').hide();
    $('#btnAgregarSala').on('click', function () {
        $('#formularioAgregarSala').fadeIn();
    });

    $('#btnCerrarFormulario').on('click', function () {
        $('#formularioAgregarSala').fadeOut();
    });

    $('#btnGuardarSala').on('click', function () {
        let formData = new FormData($('#formAgregarSala')[0]);

        $.ajax({
            url: 'modelo/AgregarSalaP.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                let sala = JSON.parse(response);
                agregarSala(sala.id, sala.nombre, sala.capacidad, sala.recursos, sala.imagen);

                $('.mensaje-salas').remove();

                $('#formularioAgregarSala').fadeOut();
                $('#formAgregarSala')[0].reset();
            }
        });
    });

    // Eliminar sala
    $('#EliminarSala').hide();

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
            data: { id: id },
            success: function (response) {
                console.log("Respuesta del servidor:", response); // Log para depuración
                if (response.includes("No se puede eliminar la sala")) {
                    mostrarAlerta('No se puede eliminar la sala porque tiene reservas asociadas.');
                } else if (response.includes("Error al eliminar la sala")) {
                    mostrarAlerta('Error al eliminar la sala.');
                } else if (response.includes("Sala y recursos asociados eliminados correctamente.")) {
                    $(`#sala-${id}`).remove();
                    mostrarAlerta('Sala eliminada exitosamente');
    
                    // Verificar si no hay más salas
                    if ($('.Sala').length === 0) {
                        $('.ContenedorSalas').append('<p class="mensaje-salas">No hay salas disponibles en este momento.</p>');
                    }
                } else {
                    mostrarAlerta('Respuesta inesperada del servidor.');
                }
                $('#EliminarSala').fadeOut();
            },
            error: function (xhr, status, error) {
                console.error('Error al eliminar la sala:', error);
                mostrarAlerta('Error al eliminar la sala.');
            }
        });
    });

    // Editar sala
    $('#formularioEditarSala').hide();
    $(document).on('click', '.btnEditar', function () {
        let salaId = $(this).data('id');
        $('#btnEditarSala').data('id', salaId);

        let nombreSala = $(`#sala-${salaId} .nombreSala`).text();
        let capacidadSala = $(`#sala-${salaId} .capacidadSala`).text();
        let recursosSala = $(`#sala-${salaId} .recursosSala`).text();

        $('#nombreSalaEditar').val(nombreSala);
        $('#capacidadSalaEditar').val(capacidadSala);
        $('#recursosSalaEditar').val(recursosSala);

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
            alert("Por favor, completa todos los campos.");
            return;
        }

        let formData = new FormData();
        if (imagenInput.files.length > 0) {
            formData.append('imagenEditar', imagenInput.files[0]);
        }

        formData.append('salaId', salaId);
        formData.append('nombreEditar', nombre);
        formData.append('capacidadEditar', capacidad);
        formData.append('recursosEditar', recursos);

        $.ajax({
            url: 'modelo/EditarSalaP.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log("Respuesta del servidor:", response);
                try {
                    var data = typeof response === "object" ? response : JSON.parse(response);
                    if (data.error) {
                        alert("Error al editar la sala: " + data.error);
                    } else {
                        mostrarAlerta("Sala actualizada exitosamente");
                        $('#formularioEditarSala').fadeOut();
                    }
                } catch (error) {
                    console.error("Error al parsear la respuesta JSON: ", error);
                    alert("Hubo un problema al procesar la respuesta del servidor.");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error en la petición AJAX: ", textStatus, errorThrown);
            }
        });
    });

    //Editar-----------------------------------------------------------------


    // Función para agregar una sala al DOM
    function agregarSala(id, nombre, capacidad, recursos, imagen) {
        let salaHTML = `
    <div class="Sala" id="sala-${id}">  
        <div class="SalaInfo">
            <img src="${imagen}" alt="ImagenSala" class="SalaImagen">
            <div>
                <p>Nombre de la sala: <span>${nombre}</span></p>
                <p>Capacidad de personas: <span>${capacidad}</span></p>
                <p>Recursos de sala: <span>${recursos}</span></p>
            </div>
        </div>
        <button class="Agendar" data-id="${id}">Agendar</button>
        <div class="Tapar"></div>`;
        // Mostrar botones de eliminar y editar solo si el usuario es 'operativo'
        if (userCargo === 'operativo') {
            salaHTML += `
        <div class="btnEliminar Eliminar" data-id="${id}">
            <img src="vista/img/eliminar.png" alt="eliminar" id="Eliminar">
        </div>
        <div class="btnEditar Editar" data-id="${id}">
            <img src="vista/img/lapiz.png" alt="editar" id="Editar">
        </div>`;
        }

        salaHTML += `</div>`;  // Cierra el div de Sala

        $('.ContenedorSalas').append(salaHTML);
    }

    $(document).off('click', '.Agendar').on('click', '.Agendar', function () {
        let salaId = $(this).data('id');
        let salaImagen = $(this).parent().find('.SalaImagen').attr('src');
        console.log("Agendar sala con ID: " + salaId);

        // Si ya existe un formulario visible, solo lo mostramos, no creamos uno nuevo
        if ($('.todo').length === 0) {
            $('.ContenedorSalas').append(`
                <div class="todo">
                    <div class="cerrar"><h1>x</h1></div>
                    <div class="container">
                        <!-- FORMULARIO -->
                        <div class="formularioDiv">
                            <!-- Imagen de salones -->
                            <div class="img">
                                <img src="${salaImagen}" alt="" class="salon">
                            </div>
                            <div>
                        
                                <!-- Creación de formulario -->
                                <form method="post" enctype="multipart/form-data">
                                    <!-- Input de fecha -->
                                    <label for="fechaYHora" id="labelFechaYHora" class="label">Fecha y hora</label> <br>
                                    <input type="date" name="fecha" class="inputForm" id="fecha">
                                    <!-- Input de hora -->
                                    <input type="time" name="horaI" class="inputForm" id="horaI">
                                       <div class="divI" id="divInfoHoraI">
                                             <p class="i" id="infoDeHoraInicio">i</p> 
                                        </div>
                                       <input type="time" name="horaF" class="inputForm" id="horaF">
                                        <div class="divI" id="divInfoHoraF">
                                            <p class="i" id="infoDeHoraFin">i</p> 
                                        </div>
                                    <!-- Input de insumos -->
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
                                    <!-- Input de limpieza -->
                                    <label for="serLimpieza" class="label" id="servLimpieza">Servicio de limpieza</label>
                                     <div class="divI" id="divInfoLimpieza">
                                   <p class="i" id="infoDeLimpieza">i</p> 
                                    </div>
                                    <label class="switch">
                                        <input type="checkbox" id="toggleSwitch">
                                        <span class="slider"></span>
                                    </label>
                                    <!-- Botón de agendar -->
                                    <button type="button" class="agendar" id="agendar" name="Agendar">Agendar</button>
                                </form>
                            </div>
                        </div>
                        <!-- CALENDARIO -->
                        <div class="calendarioDiv">
                            <!-- Vistas del calendario -->
                            <div class="vistas">
                                <button class="vista" id="diario"><h1>Día</h1></button>
                                <button class="vista" id="semanal"><h1>Semana</h1></button>
                                <button class="vista" id="mensual"><h1>Mes</h1></button>
                            </div>
                            
                            <!-- Control de días del calendario -->
                            <div class="row align-items-start" id="dias">
                                <div class="col">
                                    <div class="retroceder">
                                        <img src="vista/img/flecha-calendario-izquierda.png" alt="" class="flechaRetroceder">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="info">
                                        <!-- Día -->
                                        <h1 class="numDia-mes">24</h1>  
                                        <!-- Mes -->
                                        <h3 class="mes-anio">enero</h3>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="avanzar">
                                        <img src="vista/img/flecha-calendario-derecha.png" alt="" class="flechaAvanzar">
                                    </div>
                                </div>
                            </div>
    
                            <!-- Creación del calendario -->
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

            // Manejo del clic para agendar
            $('#agendar').click(function () {
                let fecha = $('#fecha').val();
                let horaI = $('#horaI').val();
                let horaF = $('#horaF').val();
                let insumos = $('#insumos').val();
                let observaciones = $('#observaciones').val();
                let limpieza = $('#toggleSwitch').prop('checked');
                console.log(limpieza);
                let email = usuario;
                let sala = salaId;

                $.ajax({
                    url: 'modelo/AgregarReserva.php',
                    type: 'POST',
                    data: {
                        fecha: fecha,
                        horaI: horaI,
                        horaF: horaF,
                        insumos: insumos,
                        observaciones: observaciones,
                        limpieza: limpieza,
                        email: email,
                        sala: sala
                    },
                    success: function (data) {
                        // Reserva realizada con éxito
                        const msg = `
                <div class="alert show">
                    <span class="fa-solid fa-check"></span>
                    <span class="msg">Sala reservada exitosamente</span>
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

                        // Limpiar los valores del formulario después de agendar
                        $('#fecha').val('');
                        $('#horaI').val('');
                        $('#horaF').val('');
                        $('#insumos').val('');
                        $('#observaciones').val('');
                        $('#toggleSwitch').prop('checked', false);

                        // Ocultar el formulario
                        $('.todo').hide();
                    }
                });
            });

            // Cargar el calendario para la sala seleccionada
            calendario(salaId);
        } else {
            // Limpiar los valores del formulario antes de mostrarlo nuevamente
            $('#fecha').val('');
            $('#horaI').val('');
            $('#horaF').val('');
            $('#insumos').val('');
            $('#toggleSwitch').prop('checked', false);

            // Mostrar el formulario si ya existe
            $('.todo').show();
        }
    });
    if (window.matchMedia("(max-width: 993px)").matches) {
        // Delegación de eventos para mostrar info de campos
        $(document).on('click', '#divInfoHoraI', function () {
            $('.container').append(`
                <div class="alertInfo" id="alertInfoHoraI">
                    <p>Ingrese la hora de inicio de su reserva.</p>
                </div>    
            `)
        });

        $(document).on('click', '#divInfoHoraF', function () {
            $('.container').append(`
                <div class="alertInfo" id="alertInfoHoraF">
                    <p>Ingrese la hora de fin de su reserva.</p>
                </div>    
            `)

        });

        $(document).on('click', '#divInfoInsumos', function () {
            $('.container').append(`
                <div class="alertInfo" id="alertInfoInsumos">
                    <p>Ingrese los insumos que necesita para su reserva.</p>
                    <p>Ej: 10 botellas de agua, merienda, etc.</p>
                </div>    
            `)
        });

        $(document).on('click', '#divInfoObservaciones', function () {
            $('.container').append(`
                <div class="alertInfo" id="alertInfoObservaciones">
                    <p>Ingrese las observaciones que considere necesarias para su reserva.</p>
                    <p>Ej: decoración, requerimientos especiales, etc.</p>
                </div> 
            `)
        });

        $(document).on('click', '#divInfoLimpieza', function () {
            $('.container').append(`
                <div class="alertInfo" id="alertInfoLimpieza">
                    <p>Seleccione si desea o no servicio de limpieza luego de su reserva.</p>
                    <p>Ten en cuenta la suciedad del espacio luego de su uso.</p>
                </div> 
            `)
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
                <p>Ej: decoración, requerimientos especiales, etc.</p>
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

        // Ocultar información al quitar el mouse
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





