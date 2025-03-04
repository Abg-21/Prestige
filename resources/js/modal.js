// Mostrar el modal y ocultarlo después de 5 segundos
function showModal() {
    var modal = document.getElementById('errorModal');
    modal.style.display = 'block'; // Muestra el modal

    // Ocultar el modal después de 5 segundos
    setTimeout(function() {
        modal.style.display = 'none'; // Oculta el modal
    }, 5000);
}

// Comprobar si el parámetro "error" está en la URL
function checkForError() {
    var urlParams = new URLSearchParams(window.location.search);
    // Verifica si el parámetro 'error' está presente y su valor es '1'
    if (urlParams.has('error') && urlParams.get('error') === '1') {
        showModal(); // Llama a la función para mostrar el modal
    }
}

// Ejecutar la función al cargar la página
window.onload = function() {
    checkForError(); // Llama a la función para verificar errores al cargar
};
