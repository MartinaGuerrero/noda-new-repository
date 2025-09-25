// Función para cerrar las reservas al hacer clic en el botón de cerrar
function cerrarReservas() {
    $(".cerrarReservas").off().click(function () {
        $(".reservasDia").remove(); 
    });
}



// Función para inicializar y manejar el calendario
function calendario(id) {
    let usuario = ''; // Variable para almacenar el email del usuario
    // Obtener la carga y el email del usuario a través de la sesión
    $.ajax({
        url: 'Controlador/session.php',
        type: 'GET',
        success: function (response) {
            console.log(response); // Verificar qué se recibe

            // Asegúrate de que la respuesta sea un objeto y no un string
            if (typeof response === 'string') {
                response = JSON.parse(response);
            }

            userCargo = response.cargo; // Almacenar la carga del usuario
            usuario = response.usuario; // Almacenar el correo electrónico del usuario
            console.log(userCargo);
        },
        error: function (xhr, status, error) {
            console.error('Error en la solicitud:', error);
        }
    });
    console.log(id);
    const salaId = id;
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    let currentWeekStart = new Date();
    // Maneja el cambio del botón de alternar
    $("#toggleSwitch").change(function () {
        console.log(this.checked ? "El botón está en ON" : "El botón está en OFF");
    });

    // Funciones para manejar el clic en las vistas diario, semanal y mensual
    $("#diario").click(function () {
        $("#diario").css("background-color", "#3392FF");
        $("#semanal, #mensual").css("background-color", "#002C6F");
        renderDay(new Date());
    });

    $("#semanal").click(function () {
        $("#semanal").css("background-color", "#3392FF");
        $("#diario, #mensual").css("background-color", "#002C6F");
        renderWeek(currentWeekStart);
    });

    $("#mensual").click(function () {
        $("#mensual").css("background-color", "#3392FF");
        $("#diario, #semanal").css("background-color", "#002C6F");
        renderCalendar(currentMonth, currentYear);
        reservarDiaDeSemana();
    });

    // Funciones para manejar la navegación entre meses y semanas
    $(".retroceder").click(function () {
        if ($("#mensual").css("background-color") === "rgb(51, 146, 255)") {
            currentMonth = currentMonth === 0 ? 11 : currentMonth - 1;
            if (currentMonth === 11) currentYear--;
            renderCalendar(currentMonth, currentYear);
        } else {
            adjustWeekOrDay(-1);
        }
    });

    $(".avanzar").click(function () {
        if ($("#mensual").css("background-color") === "rgb(51, 146, 255)") {
            currentMonth = currentMonth === 11 ? 0 : currentMonth + 1;
            if (currentMonth === 0) currentYear++;
            renderCalendar(currentMonth, currentYear);
        } else {
            adjustWeekOrDay(1);
        }
    });

    // Función para ajustar la visualización de la semana o el día
    function adjustWeekOrDay(delta) {
        currentWeekStart.setDate(currentWeekStart.getDate() + delta * (delta === 1 ? 7 : 1));
        if ($("#semanal").css("background-color") === "rgb(51, 146, 255)") {
            renderWeek(currentWeekStart);
        } else {
            renderDay(currentWeekStart);
        }
    }

    // Función para renderizar el calendario mensual
    function renderCalendar(month, year) {
        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        let firstDay = new Date(year, month, 1).getDay();
        let daysInMonth = new Date(year, month + 1, 0).getDate();
        let daysHTML = "";

        $(".numDia-mes").text(monthNames[month]);
        $(".mes-anio").text(year);
        $(".calendario").empty();

        // Agrega los días de la semana al calendario
        $(".calendario").append(`
            <div class="diasSemana">
                <div class="diaSemana"><h1>D</h1></div>
                <div class="diaSemana"><h1>L</h1></div>
                <div class="diaSemana"><h1>M</h1></div>
                <div class="diaSemana"><h1>M</h1></div>
                <div class="diaSemana"><h1>J</h1></div>
                <div class="diaSemana"><h1>V</h1></div>
                <div class="diaSemana"><h1>S</h1></div>
            </div>
        `);

        // Renderiza los días del mes
        for (let i = 0; i < firstDay; i++) {
            daysHTML += `<div class="divDiaVacia"></div>`;
        }

        for (let i = 1; i <= daysInMonth; i++) {
            daysHTML += `<div class="divDia"><p>${i}</p></div>`;
        }

        $(".calendario").append(daysHTML);

        // Maneja el clic en un día específico
        $(".divDia").off().click(function () {
            $(".divDia").removeClass("selected");
            $(this).addClass("selected");
            let selectedDay = $(this).text();
            let fullDate = `${year}-${(month + 1).toString().padStart(2, '0')}-${selectedDay.padStart(2, '0')}`;
            console.log(fullDate);

            $("#fecha").val(fullDate);
            $(".reservas").empty(); // Limpiar reservas existentes

            // Crea el contenedor de reservas
            const reservasDiaHtml = `
                <div class="reservasDia">
                    <div class="cerrarReservas"><p class="cerrarX">X</p></div>
                    <p class="tituloReserva">Reservas del día ${selectedDay} de ${monthNames[month]} de ${year}</p>
                    <div class="reservas">
                        <!-- las reservas se añadirán aquí con AJAX -->    
                    </div>
                </div>
            `;

            $(".container").append(reservasDiaHtml);
            $(`#${id}`).on('click', function () {
                let salaId = $(this).data('id');
                console.log(salaId);
            });

            // Obtener reservas de la base de datos
            $.ajax({
                url: 'modelo/ObtenerReservaCalendario.php',
                type: 'GET',
                data: {
                    date: fullDate, id: salaId
                },
                success: function (data) {
                    console.log(data); // Imprimir la data en la consola

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(function (reserva) {
                            $(".reservas:last").append(`
                                <div class="reserva">
                                    <p>${reserva.nombre}</p>
                                    <p>${reserva.hora}</p>
                                </div>`);
                        });
                    } else {
                        $(".reservas:last").append(`<p>No hay reservas para este día.</p>`);
                    }
                },
            });

            cerrarReservas(); // Asegúrate de que esta función esté definida
            setTimeout(function () {
                $(".reservasDia").addClass("show");
            }, 10);
        });
    }

    // Función para renderizar la vista semanal
    function renderWeek(weekStart) {
        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        let daysHTML = "";
        let currentDay = new Date(weekStart);
        let firstDay = currentDay.getDay();

        $(".numDia-mes").text(currentDay.getDate());
        $(".mes-anio").text(`${monthNames[currentDay.getMonth()]} ${currentDay.getFullYear()}`);
        $(".calendario").empty();
        $(".calendario").css("padding-top", "15px");

        for (let i = 0; i < firstDay; i++) {
            daysHTML += `<div class="divDiaVaciaSem"></div>`;
        }

        for (let i = 0; i < 7; i++) {
            daysHTML += `<div class="divDiaSem">${currentDay.getDate()}</div>`;
            currentDay.setDate(currentDay.getDate() + 1);
        }

        $(".calendario").append(daysHTML);

        // Maneja el clic en un día específico de la semana
        $(".divDiaSem").off().click(function () {
            $(".divDiaSem").removeClass("selected");
            $(this).addClass("selected");
            let selectedDay = $(this).text();
            let selectedDate = new Date(weekStart);
            selectedDate.setDate(weekStart.getDate() + $(this).index() - firstDay);

            let fullDate = `${selectedDate.getFullYear()}-${(selectedDate.getMonth() + 1).toString().padStart(2, '0')}-${selectedDay.padStart(2, '0')}`;
            $("#fecha").val(fullDate);
        });
    }

    // Función para renderizar la vista de un solo día
    function renderDay(day) {
        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        let selectedDate = new Date(day);
        $(".numDia-mes").text(selectedDate.getDate());
        $(".mes-anio").text(`${monthNames[selectedDate.getMonth()]} ${selectedDate.getFullYear()}`);
        $(".calendario").empty();
        $(".calendario").append(`<div class="divDia">${selectedDate.getDate()}</div>`);

        // Maneja el clic en el día seleccionado
        $(".divDia").off().click(function () {
            $(this).addClass("selected");
            let fullDate = `${selectedDate.getFullYear()}-${(selectedDate.getMonth() + 1).toString().padStart(2, '0')}-${selectedDate.getDate().toString().padStart(2, '0')}`;
            $("#fecha").val(fullDate);
        });
    }

    // Llama a la función para renderizar el calendario inicial
    renderCalendar(currentMonth, currentYear);
    $('.cerrar').click(function () {
        $('.todo').remove();
    });

    // Función para manejar la reserva de días en la semana
    function reservarDiaDeSemana() {
        $('.diaSemana').click(function () {
            const reservaDiaSemana = `
            <div class="reservasDiaSemana">
                <div class="cerrarXChico"><p>X</p></div>
                <form action="agenda.php" method="POST">
                    <label for="fecha" class="ReservarDiaPorSemanaFecha">Fecha</label>
                    <input type="date" name="fecha" id="fecha" value="" required>
                    <label for="hora" class="ReservarDiaPorSemanaHora">Hora</label>
                    <input type="time" name="horai" id="horai" class="horaD" required>
                    <input type="time" name="horaf" id="horaf" class="horaD" required>
                    <label for="cantDias" class="ReservarDiaPorSemanaCantDias">Cantidad de días</label>
                    <input type="number" name="cantDias" id="cantDias" required>
                    <button type="button" class="agendar" id="resrevarD" name="Agendar">Agendar</button>
                </form>
            </div>
        `;

            $(".reservas").empty();
            $(".calendario").append(reservaDiaSemana);
            clickreserva(); // Llama a la función para manejar el clic en reservar

            $('.cerrarXChico').click(function () {
                $('.reservasDiaSemana').remove();
            });
        });
    }

    // Función para manejar el clic en el botón de reserva
    function clickreserva() {
        const boton = document.getElementById('resrevarD');
        boton.addEventListener('click', function () {
            console.log('click');
            let fecha = $('#fecha').val();
            let horaI = $('#horai').val();
            let horaF = $('#horaf').val();
            let cantidad = $('#cantDias').val();
            let email = usuario;
            let sala = salaId;

            // Realiza la solicitud AJAX para agregar la reserva
            $.ajax({
                url: 'modelo/AgregarReservaDiaSemana.php',
                type: 'POST',
                data: {
                    fecha: fecha,
                    horaI: horaI,
                    horaF: horaF,
                    cantidad: cantidad,
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

                    // Maneja el clic en el botón de cerrar la alerta
                    $('.close-btn').click(function () {
                        $('.alert').removeClass("show");
                        $('.alert').addClass("hide");
                    });

                    // Limpiar los valores del formulario después de agendar
                    $('#fecha').val('');
                    $('#horaI').val('');
                    $('#horaF').val('');
                    $('#insumos').val('');
                    $('#toggleSwitch').prop('checked', false);

                    // Ocultar el formulario
                    $('.todo').hide();
                }
            });
        });
    };
}
