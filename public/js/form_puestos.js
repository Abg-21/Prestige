document.addEventListener('DOMContentLoaded', function () {
    const categoriaSelect = document.getElementById('Categoría');
    const conocimientosInput = document.getElementById('Conocimientos');
    const funcionesInput = document.getElementById('Funciones');
    const habilidadesInput = document.getElementById('Habilidades');
    const autoFields = document.getElementById('autoFields');
    const dynamicFields = document.getElementById('dynamicFields');

    categoriaSelect.addEventListener('change', function () {
        const categoria = categoriaSelect.value;
        console.log('Categoría seleccionada:', categoria);

        if (categoria === 'Otro') {
            clearInputs();
            console.log('Campos habilitados para edición manual.');

            // Mostrar los campos dinámicos y ocultar los automáticos
            dynamicFields.classList.remove('d-none');
            autoFields.classList.add('d-none');

            // Habilitar campos dinámicos y hacerlos requeridos
            document.querySelectorAll('#dynamicFields input').forEach(input => {
                input.removeAttribute('readonly');
                input.setAttribute('required', true);
            });

            // Asegurarse de que los campos automáticos no sean requeridos
            [conocimientosInput, funcionesInput, habilidadesInput].forEach(input => {
                input.removeAttribute('required');
            });
        } else {
            fillFields(categoria);
            console.log('Campos llenados automáticamente para:', categoria);

            // Mostrar los campos automáticos y ocultar los dinámicos
            autoFields.classList.remove('d-none');
            dynamicFields.classList.add('d-none');

            // Hacer los campos automáticos requeridos y solo lectura
            [conocimientosInput, funcionesInput, habilidadesInput].forEach(input => {
                input.setAttribute('readonly', true);
                input.setAttribute('required', true);
            });

            // Quitar 'required' de los campos dinámicos
            document.querySelectorAll('#dynamicFields input').forEach(input => {
                input.removeAttribute('required');
            });
        }
    });

    function fillFields(categoria) {
        switch (categoria) {
            case 'Promovendedor':
                conocimientosInput.value = "Características y beneficios del producto. Ventajas competitivas. Conocimiento de las necesidades y preferencias del cliente. Inventarios. Planogramas";
                funcionesInput.value = "Negociar espacios adicionales en los anaqueles. Rotación de Producto. Atención al cliente. Labor de Venta";
                habilidadesInput.value = "Atención al cliente. Argumentación y negociación. Dinamismo, Disciplina y vocación de servicio. Empatía y comprensión de las necesidades del cliente.";
                break;
            case 'Promotor':
                conocimientosInput.value = "Acomodo de mercancía de acuerdo a planograma, Inventarios, Revisión de producto agotado y próximo a caducar";
                funcionesInput.value = "Negociar espacios adicionales en los anaqueles, Limpieza y rotación del producto, Mantenimiento de la imagen de la marca en los puntos de venta, Montaje de exhibiciones adicionales en PDV";
                habilidadesInput.value = "Relaciones interpersonales con clientes y colegas. Representación de la marca y la tienda. Dinamismo, Disciplina y vocación de servicio";
                break;
            case 'Supervisor':
                conocimientosInput.value = "Paquetería Office 70%. Realización de planes de trabajo";
                funcionesInput.value = "Realización de plan de trabajo para las visitas a PDV. Supervisión del personal a su cargo. Reportes del producto (agotados, caducos, ofertas, etc). Bajas y altas del personal dentro de la zona (firma de contratos y finiquitos). Control y seguimiento de asistencias, incidencias y actas administrativas";
                habilidadesInput.value = "Relaciones interpersonales con clientes y colegas. Trato amable. Dinamismo, Disciplina y vocación de servicio.";
                break;
            default:
                clearInputs();
        }
    }

    function clearInputs() {
        conocimientosInput.value = '';
        funcionesInput.value = '';
        habilidadesInput.value = '';
        console.log('Campos limpiados.');
    }
});
function addInput(sectionId) {
    const section = document.getElementById(sectionId);
    const newInput = document.createElement('input');

    newInput.type = 'text';
    newInput.name = sectionId + '[]';  // Esto permitirá que se envíen como un array en el formulario
    newInput.classList.add('form-control', 'mb-2');
    newInput.setAttribute('required', true);  // Hacer que los nuevos campos también sean requeridos

    section.appendChild(newInput);
}
