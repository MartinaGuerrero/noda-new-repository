document.getElementById('buscar').addEventListener('keyup', function() {
    // Obtiene el valor del campo de búsqueda y lo convierte a minúsculas
    let query = this.value.toLowerCase();
    
    // Selecciona todos los elementos con la clase 'reserva'
    let reservas = document.querySelectorAll('.reserva');

    // Itera sobre cada elemento de reserva
    reservas.forEach(function(reserva) {
        // Obtiene el texto del elemento de reserva y lo convierte a minúsculas
        let textoReserva = reserva.innerText.toLowerCase();
        
        // Verifica si el texto de la reserva incluye el texto de búsqueda
        if (textoReserva.includes(query)) {
            reserva.style.display = 'flex';  // Muestra la reserva si coincide con la búsqueda
        } else {
            reserva.style.display = 'none';  // Oculta la reserva si no coincide
        }
    });
});

