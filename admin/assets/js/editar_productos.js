var tallaRopaCheckbox = document.getElementById('talla_ropa');
  var tallaCalzadoCheckbox = document.getElementById('talla_calzado');
  var tallasLimpioInput = document.getElementById('tallasLimpio');

  tallaRopaCheckbox.addEventListener('change', function() {
    if (this.checked) {
      tallaCalzadoCheckbox.checked = false;
      tallasLimpioInput.value = '';
      tallasLimpioInput.placeholder = 'Ejemplo: M, L';
      tallasLimpioInput.removeAttribute('disabled');
    }
  });

  tallaCalzadoCheckbox.addEventListener('change', function() {
    if (this.checked) {
      tallaRopaCheckbox.checked = false;
      tallasLimpioInput.value = '';
      tallasLimpioInput.placeholder = 'Ejemplo: 12, 15';
      tallasLimpioInput.removeAttribute('disabled');
    }
  });

  tallasLimpioInput.addEventListener('input', function() {
    if (this.value.trim() === '') {
      tallaRopaCheckbox.checked = false;
      tallaCalzadoCheckbox.checked = false;
    }
  });

 // Código JavaScript para mostrar la imagen del producto en la vista previa
  // Este código asume que el elemento "imagen-producto-preview" existe en el DOM.

  // Verificar si hay imágenes del producto seleccionadas y mostrarlas en la vista previa
  var imagenProductoInput = document.getElementById('imagenes');
  var imagenProductoPreview = document.getElementById('imagen-producto-preview');

  imagenProductoInput.addEventListener('change', function(event) {
    var files = event.target.files;
    imagenProductoPreview.innerHTML = '';

    function readImage(index) {
      if (index >= files.length) {
        // Si no se ha seleccionado ninguna imagen, mostrar la imagen por defecto del producto
        if (files.length === 0) {
          var defaultImage = new Image();
          defaultImage.src = '../../Assets/img/productos_img/default.png';
          defaultImage.alt = 'Imagen por defecto';
          defaultImage.style.maxWidth = '100%';
          defaultImage.style.maxHeight = '100%';
          imagenProductoPreview.appendChild(defaultImage);
        }
        return;
      }

      var file = files[index];
      var reader = new FileReader();

      reader.onload = function() {
        var image = new Image();
        image.src = reader.result;
        image.alt = 'Previsualización de la imagen del producto';
        image.style.maxWidth = '100px'; // Ajusta el tamaño de la previsualización
        image.style.maxHeight = '100px'; // Ajusta el tamaño de la previsualización
        image.style.margin = '5px'; // Agrega margen entre las imágenes en la previsualización
        imagenProductoPreview.appendChild(image);

        // Llamar a la función recursivamente para leer la siguiente imagen
        readImage(index + 1);
      };

      if (file) {
        reader.readAsDataURL(file);
      }
    }

    // Iniciar la función recursiva para mostrar las imágenes seleccionadas
    readImage(0);
  });

  function showSubcategorias() {
    var categoriaId = document.getElementById('categoria_id').value;
    var subcategoriaDiv = document.getElementById('subcategoriaDiv');
    var subcategoriaSelect = document.getElementById('subcategoria_id');

    // Verificar si se ha seleccionado una categoría
    if (categoriaId !== '') {
      // Mostrar el campo de selección de subcategoría
      subcategoriaDiv.style.display = 'block';

      // Mostrar u ocultar las opciones de subcategorías según la categoría seleccionada
      var subcategoriaOptions = subcategoriaSelect.getElementsByTagName('option');
      for (var i = 0; i < subcategoriaOptions.length; i++) {
        var option = subcategoriaOptions[i];
        var categoriaData = option.getAttribute('data-categoria');
        if (categoriaData === categoriaId) {
          option.style.display = 'block';
        } else {
          option.style.display = 'none';
        }
      }
    } else {
      // Si no se ha seleccionado una categoría, ocultar el campo de selección de subcategoría
      subcategoriaDiv.style.display = 'none';
    }

    // Restablecer el campo de selección de subcategoría a "Seleccionar Subcategoría"
    subcategoriaSelect.value = '';
  }