// Obtener el elemento select de la categoría y el formulario de subcategoría
const categoriaSelect = document.getElementById('categoria');
const subcategoriaForm = document.getElementById('subcategoriaForm');
const agregarsubcategoriaBtn = document.getElementById('agregarsubcategoria');

// Agregar un evento para detectar cambios en el select de la categoría
categoriaSelect.addEventListener('change', function() {
  // Obtener el valor seleccionado en el select
  const categoriaId = categoriaSelect.value;

  // Mostrar u ocultar el formulario de subcategoría según el valor seleccionado
  if (categoriaId !== '') {
    subcategoriaForm.style.display = 'block';
    agregarsubcategoriaBtn.style.display = 'block';
  } else {
    subcategoriaForm.style.display = 'none';
    agregarsubcategoriaBtn.style.display = 'none';
  }
});