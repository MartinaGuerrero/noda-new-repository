document.addEventListener('DOMContentLoaded', function() {
    function addHoverEffect(elementSelector, textSelector) {
        const element = document.querySelector(elementSelector);
        const text = document.querySelector(textSelector);
        if (element && text) {
            element.addEventListener('mouseover', function() {
                text.style.display = 'block';
            });
            element.addEventListener('mouseout', function() {
                text.style.display = 'none';
            });
        }
    }

    // Menú Principal
    addHoverEffect('.Principal', '.nombreMenuPrincipal');

    // Agenda (Salas)
    addHoverEffect('.agenda', '.Salas');

    // Reservas (solo para operativo o líder)
    addHoverEffect('.reservas', '.Reservas');

    // Mis Reservas
    addHoverEffect('.misreservas', '.MisReservas');

    // Usuarios (Perfiles) (solo para operativo)
    addHoverEffect('#menuUsuarios', '.Usuarios');

    const menuToggle = document.getElementById('menuToggle');
    const containerMenu = document.querySelector('.containerMenu');

    if (menuToggle && containerMenu) {
        menuToggle.addEventListener('click', function() {
            containerMenu.classList.toggle('visible');
        });
    }
});