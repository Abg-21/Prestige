$(document).ready(function() {
    // Al pasar el ratón por encima de una opción de menú
    $(".menu-item").hover(function() {
        // Muestra el submenú con fadeIn()
        var opciones = $(this).data("opciones");
        $("#" + "opciones-" + opciones).fadeIn();
    }, function() {
        // Oculta el submenú con fadeOut()
        var opciones = $(this).data("opciones");
        $("#" + "opciones-" + opciones).fadeOut();
    });
});
