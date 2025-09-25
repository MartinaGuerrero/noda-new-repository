document.getElementById('buscar').addEventListener('keyup', function() {
    // Obtiene el valor del campo de búsqueda y lo convierte a minúsculas
    let query = this.value.toLowerCase();
    
    // Selecciona todos los elementos con la clase 'Reserva'
    let reservas = document.querySelectorAll('.Reserva');

    // Inicializa un contador para las reservas visibles
    let reservasVisibles = 0;

    // Itera sobre cada elemento de reserva
    reservas.forEach(function(reserva) {
        // Obtiene el texto del elemento de reserva y lo convierte a minúsculas
        let textoReserva = reserva.innerText.toLowerCase();
        
        // Verifica si el texto de la reserva incluye el texto de búsqueda
        if (textoReserva.includes(query)) {
            reserva.style.display = 'flex';  // Muestra la reserva si coincide con la búsqueda
            reservasVisibles++;
        } else {
            reserva.style.display = 'none';  // Oculta la reserva si no coincide
        }
    });

    // Verifica si no hay reservas visibles
    if (reservasVisibles === 0) {
        mostrarMensajeNoResultados();
    } else {
        ocultarMensajeNoResultados();
    }
});

function mostrarMensajeNoResultados() {
    // Verifica si el mensaje ya está en el DOM
    let mensaje = document.querySelector('.No-reservas');
    if (!mensaje) {
        let mensajeHTML = `
        <div class="No-reservas">
            <p class="texto-no-reservas">No se encontraron resultados.</p>
        </div>`;
        document.querySelector('.Container').insertAdjacentHTML('beforeend', mensajeHTML);
    }
}

function ocultarMensajeNoResultados() {
    // Elimina el mensaje si existe
    let mensaje = document.querySelector('.No-reservas');
    if (mensaje) {
        mensaje.remove();  // Elimina el mensaje si hay reservas visibles
    }
}
