// Mostrar el modal cuando se haga clic en el icono de notificaciones
document.querySelector('.notificaciones').addEventListener('click', function(event) {
    event.preventDefault(); // Evita que se redirija al hacer clic
    document.getElementById('modalNotificaciones').style.display = 'block';
});

// Cerrar el modal cuando se haga clic en la "X"
document.getElementById('cerrarModal').addEventListener('click', function() {
    document.getElementById('modalNotificaciones').style.display = 'none';
});
