// Código JavaScript para mostrar la imagen de categoría en la vista previa
  // Este código asume que el elemento "imagen-preview" existe en el DOM.

  // Verificar si hay una imagen de categoría seleccionada y mostrarla en la vista previa
  var imagenInput = document.getElementById('imagen');
  var imagenPreview = document.getElementById('imagen-preview');

  // Función para mostrar la imagen de categoría actual
  function mostrarImagenActual() {
    imagenPreview.innerHTML = '';
    var imagenActual = new Image();
    imagenActual.src = '<?php echo $imagenCategoria; ?>';
    imagenActual.alt = 'Previsualización de la imagen';
    imagenActual.style.maxWidth = '200px';
    imagenActual.style.maxHeight = '200px';
    imagenPreview.appendChild(imagenActual);
  }

  imagenInput.addEventListener('input', function(event) {
    var file = event.target.files[0];
    var reader = new FileReader();

    reader.onload = function() {
      var image = new Image();
      image.src = reader.result;
      image.alt = 'Previsualización de la imagen';
      image.style.maxWidth = '100%';
      image.style.maxHeight = '100%';
      imagenPreview.innerHTML = '';
      imagenPreview.appendChild(image);
    };

    if (file) {
      reader.readAsDataURL(file);
    } else {
      // Si no se ha seleccionado ninguna imagen, mostrar la imagen de categoría actual
      mostrarImagenActual();
    }
  });

  // Mostrar la imagen de categoría actual al cargar la página
  mostrarImagenActual();