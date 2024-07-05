var imagenInput = document.getElementById('imagen');
var imagenPreview = document.getElementById('imagen-preview');

imagenInput.addEventListener('change', function(event) {
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
    // Si no se ha seleccionado ninguna imagen, mostrar la imagen por defecto
    var defaultImage = new Image();
    defaultImage.src = '../../Assetsassets/img/categoria_img/default.jpg';
    defaultImage.alt = 'Imagen por defecto';
    defaultImage.style.maxWidth = '100%';
    defaultImage.style.maxHeight = '100%';
    imagenPreview.innerHTML = '';
    imagenPreview.appendChild(defaultImage);
  }
});