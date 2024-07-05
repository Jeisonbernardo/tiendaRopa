// Función para previsualizar imágenes seleccionadas
function previewImage(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = "#";
        preview.style.display = "none";
    }
}

// Script para mostrar la visualización sin usar el windows.onload
document.addEventListener("DOMContentLoaded", function() {
    // Código JavaScript adicional (si es necesario)
});