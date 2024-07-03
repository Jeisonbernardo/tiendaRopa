function copiarAlPortapapeles(event) {
    event.preventDefault(); // Evita el comportamiento por defecto del enlace

    // Obtiene el texto del enlace
    var texto = document.getElementById("textoParaCopiar").innerText;

    // Crea un campo de texto temporal
    var campoTemporal = document.createElement("textarea");
    campoTemporal.value = texto;
    document.body.appendChild(campoTemporal);

    // Selecciona el contenido del campo temporal
    campoTemporal.select();
    campoTemporal.setSelectionRange(0, 99999); // Para dispositivos móviles

    // Copia el contenido al portapapeles
    document.execCommand("copy");

    // Remueve el campo temporal
    document.body.removeChild(campoTemporal);

    // Alerta de éxito
    alert("Texto Copiado Correctamente");
}
