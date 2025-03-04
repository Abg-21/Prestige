function addInput(containerId, inputName) {
    const container = document.getElementById(containerId);
    const inputs = container.querySelectorAll('input');
    
    if (inputs.length < 8) {
        const newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.name = inputName;
        newInput.className = 'form-control mb-2';
        newInput.required = true;
        container.appendChild(newInput);
    } else {
        alert('Máximo 8 elementos permitidos.');
    }
}
function removeLastInput(containerId) {
    const container = document.getElementById(containerId);
    const inputs = container.querySelectorAll('input');
    if (inputs.length > 1) {
        container.removeChild(inputs[inputs.length - 1]);
    }
}
