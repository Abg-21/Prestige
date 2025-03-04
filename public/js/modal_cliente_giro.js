document.addEventListener("DOMContentLoaded", () => {
    const formCrearCliente = document.getElementById("formCrearCliente");
    const formCrearGiro = document.getElementById("formCrearGiro");

    const selectCliente = document.getElementById("selectCliente");
    const selectGiro = document.getElementById("selectGiro");

    const enviarFormulario = async (form, url, selectElement) => {
        const formData = new FormData(form);
        try {
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData,
            });
            if (response.ok) {
                const nuevoElemento = await response.json();
                // Agregar la nueva opción al select
                const option = document.createElement("option");
                option.value = nuevoElemento.id;
                option.textContent = nuevoElemento.nombre;
                selectElement.appendChild(option);
                selectElement.value = nuevoElemento.id;

                // Cerrar el modal y reiniciar formulario
                const modalElement = form.closest(".modal");
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }

                form.reset();
            } else {
                const errorData = await response.json();
                console.error("Error al guardar el elemento:", errorData);
            }
        } catch (error) {
            console.error("Error en la petición:", error);
        }
    };

    formCrearCliente.addEventListener("submit", (e) => {
        e.preventDefault();
        enviarFormulario(formCrearCliente, "/clientes/store", selectCliente);
    });

    formCrearGiro.addEventListener("submit", (e) => {
        e.preventDefault();
        enviarFormulario(formCrearGiro, "/giros/store", selectGiro);
    });
});
