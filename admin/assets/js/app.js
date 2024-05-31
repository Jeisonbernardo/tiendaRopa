    // Función para evitar el enfoque en el elemento colapsable cuando se hace clic en el enlace
    document.querySelectorAll('.nav-link[data-toggle="collapse"]').forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
        });
    });