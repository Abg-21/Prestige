function confirmDelete(giroId) {
    if (!giroId) {
        alert('No se pudo identificar el giro para eliminar.');
        return;
    }

    const form = document.getElementById(`deleteForm-${giroId}`);
    if (!form) {
        alert('No se encontró el formulario para el giro especificado.');
        return;
    }

    if (confirm('¿Estás seguro de que deseas eliminar este giro? Esta acción no se puede deshacer.')) {
        form.submit();
    }
}

