<?php
// Incluir el archivo de conexión
include 'conexion.php';

// Definir la ruta de la imagen por defecto
$rutaImagenDefecto = './assets/img/productos_img/default.png';

// Iniciar la sesión
session_start();

// Verificar si NO se ha iniciado sesión y NO hay un token almacenado
if (!isset($_SESSION['token'])) {
  header('Location: login.php');
  exit;
}

// Verificar si se ha proporcionado el ID del producto a editar
if (isset($_GET['id'])) {
  // Obtener el ID del producto desde la URL
  $productoId = $_GET['id'];

  // Consultar los datos del producto desde la base de datos utilizando el ID
  $query = "SELECT * FROM producto WHERE id = $productoId";
  $result = mysqli_query($conn, $query);

  // Verificar si se encontró el producto
  if (mysqli_num_rows($result) > 0) {
    // Obtener los detalles del producto
    $producto = mysqli_fetch_assoc($result);
    $nombre = $producto['nombre'];
    $stock = $producto['stock'];
    $precio = $producto['precio'];
    $categoria_id = $producto['categoria_id'];
    $marca_id = $producto['marca_id'];
    $descripcion = $producto['descripcion'];
    $subcategoria_id = $producto['subcategoria_id'];
    $rutaImagen = $producto['imagenes'];
    $talla = $producto['tallas_seleccionadas'];

    // Eliminar el texto "ropa, " o "calzado, " utilizando str_replace
    $tallasLimpio = str_replace(['ropa, ', 'calzado, '], '', $talla);
  } else {
    // Si no se encontró el producto, redirigir a la página de listar productos
    header('Location: ver_productos.php');
    exit;
  }
} else {
  // Si no se proporcionó el ID del producto, redirigir a la página de listar productos
  header('Location: ver_productos.php');
  exit;
}

// Verificar si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Obtener y limpiar los datos ingresados por el usuario
  $nombre = trim($_POST['nombre']);
  $stock = $_POST['stock'];
  $precio = $_POST['precio'];
  $categoria_id = $_POST['categoria_id'];
  $subcategoria_id = $_POST['subcategoria_id'];
  $descripcion = trim($_POST['descripcion']);
  $marca_id = $_POST['marca_id'];

  // Obtener las tallas seleccionadas (ropa, calzado) del checkbox
  $tallas_seleccionadas = isset($_POST['tallas_seleccionadas']) ? $_POST['tallas_seleccionadas'] : array();

  // Obtener las tallas ingresadas en el campo de texto
  $tallasLimpio = isset($_POST['tallasLimpio']) ? trim($_POST['tallasLimpio']) : '';

  // Combinar las tallas seleccionadas con las tallas ingresadas
  $tallasCombinadas = array_merge($tallas_seleccionadas, explode(', ', $tallasLimpio));

  // Construir el valor para la columna 'tallas_seleccionadas'
  $talla = implode(', ', $tallasCombinadas);

  // Obtener el archivo de las imágenes
  $imagenes = isset($_FILES['imagenes']['tmp_name']) ? $_FILES['imagenes']['tmp_name'] : array();
  $nombreImagenes = isset($_FILES['imagenes']['name']) ? $_FILES['imagenes']['name'] : array();

  // Verificar si se ha marcado el checkbox para borrar las imágenes existentes
  $borrarImagenes = isset($_POST['borrar_imagenes']) && $_POST['borrar_imagenes'] == 1;

  // Verificar si se seleccionaron imágenes
  if (!empty($imagenes) && !empty($nombreImagenes)) {
    // Obtener el número de imágenes seleccionadas
    $numImagenes = count($imagenes);

    // Definir un array con las extensiones de imagen permitidas
    $extensionesPermitidas = array('jpg', 'jpeg', 'png', 'webp'); // Agregar las extensiones permitidas
    $nombreCarpeta = str_replace(' ', '_', $nombre);

    // Crear el directorio para el producto si no existe
    $directorioProducto = './assets/img/productos_img/' . $nombreCarpeta;
    if (!is_dir($directorioProducto)) {
      mkdir($directorioProducto, 0777, true);
    }

    // Borrar imágenes existentes si se ha marcado el checkbox
    if ($borrarImagenes && isset($directorioProducto) && is_dir($directorioProducto)) {
      $imagenesProducto = glob($directorioProducto . '/*');
      foreach ($imagenesProducto as $imagenProducto) {
        if (is_file($imagenProducto)) {
          unlink($imagenProducto); // Eliminar la imagen existente
        }
      }
    }

    // Obtener el número de imágenes existentes en el directorio del producto
    $numImagenesExistentes = count(glob($directorioProducto . '/*'));

    // Validar las extensiones de las imágenes
    $validarExtensiones = true;
    for ($i = 0; $i < $numImagenes; $i++) {
      $imagen = $imagenes[$i];
      $nombreImagen = $nombreImagenes[$i];
      $extension = pathinfo($nombreImagen, PATHINFO_EXTENSION);
      $extension = strtolower($extension);

      // Verificar si la extensión de la imagen es válida
      if (!in_array($extension, $extensionesPermitidas)) {
        // Mostrar mensaje de error si la imagen no tiene una extensión permitida
        $success = "Producto actualizado correctamente.";
        $validarExtensiones = false;
        break;
      }

      // Formatear el nombre de la imagen
      $nombreImagenFormateado = $nombreCarpeta . '_' . ($numImagenesExistentes + $i + 1) . '.png';

      // Ruta de la imagen
      $rutaImagen = $directorioProducto . '/' . $nombreImagenFormateado;

      // Mover la imagen a la carpeta del producto
      move_uploaded_file($imagen, $rutaImagen);

      // Redimensionar y convertir la imagen a formato PNG
      $imagenRedimensionada = imagecreatefromstring(file_get_contents($rutaImagen));
      $nuevoAncho = 1200;
      $nuevoAlto = 1200;
      $imagenRedimensionada = imagescale($imagenRedimensionada, $nuevoAncho, $nuevoAlto);
      imagepng($imagenRedimensionada, $rutaImagen, 9);
      imagedestroy($imagenRedimensionada);
    }

    $insertarproducto = true; // Actualizar el producto en la base de datos
  } else {
    // Si no se seleccionaron imágenes, mantener la imagen actual del producto
    $insertarproducto = true; // Actualizar el producto en la base de datos
  }

  // Verificar si ya existe un producto con el mismo nombre (excepto el producto actual)
  $queryExistenciaProducto = "SELECT id FROM producto WHERE nombre = '$nombre' AND id <> $productoId";
  $resultExistenciaProducto = mysqli_query($conn, $queryExistenciaProducto);

  if (mysqli_num_rows($resultExistenciaProducto) > 0) {
    // Si ya existe un producto con el mismo nombre, mostrar mensaje de error
    $success = "Producto actualizado correctamente.";
    $insertarproducto = false;
  }

  // Actualizar los datos en la tabla "producto" si el nombre de producto no está en uso
  if ($insertarproducto) {
    // Generar la consulta de actualización con la ruta de la imagen actualizada
    $queryUpdate = "UPDATE producto SET nombre = '$nombre', stock = '$stock', precio = '$precio', categoria_id = '$categoria_id', marca_id = '$marca_id', descripcion = '$descripcion', updated_at = NOW(), subcategoria_id = '$subcategoria_id'";

    // Agregar las tallas a la consulta de actualización
    $queryUpdate .= ", tallas_seleccionadas = '$talla'";

    // Si se seleccionaron nuevas imágenes, incluir la actualización de la ruta de las imágenes
    if ($imagenes && $nombreImagenes) {
      $queryUpdate .= ", imagenes = '$directorioProducto'";
    }

    // Completar la consulta con la condición para actualizar el producto específico
    $queryUpdate .= " WHERE id = $productoId";

    // Ejecutar la consulta de actualización
    $resultUpdate = mysqli_query($conn, $queryUpdate);

    if ($resultUpdate) {
      // Mostrar mensaje de éxito si los datos se actualizaron correctamente
      $success = "Producto actualizado correctamente.";
      // Obtener la ruta de la imagen actualizada para mostrarla en la vista previa
      $rutaImagen = $directorioProducto;
    } else {
      // Mostrar mensaje de error si no se pudieron actualizar los datos
      $error = "Hubo un error al actualizar el producto.";
    }
  }
}

include 'vistas/template/navbar.php';
?>


<!-- Page title-->
<div class="border-bottom pt-5 mt-2 mb-5">
  <h1 class="mt-2 mt-md-4 mb-3 pt-5">EDITAR PRODUCTOS</h1>
  <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
    <p class="text-muted">Aquí puedes editar el producto seleccionado</p>
  </div>
</div>

<div class="card box-shadow-sm">
  <div class="card-header">
    <h5 style="margin-bottom: 0px;">EDITAR PRODUCTO</h5>
  </div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <div class="row">
        <div class="col-lg-8">
          <!-- Formulario principal -->
          <div class="row">
            <div class="col-lg-12 form-group">
              <label for="nombre">Titulo de producto <br>(Si se cambia el nombre, volver a subir las imagenes!)</label>
              <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Titulo de producto" required value="<?php echo $nombre; ?>">
            </div>
            <div class="col-lg-4 form-group">
              <label for="stock">Stock</label>
              <input type="number" class="form-control" id="stock" name="stock" placeholder="Cantidad inicial" required min="0" value="<?php echo $stock; ?>">
            </div>
            <div class="col-lg-4 form-group">
              <label for="precio">Precio (S/.)</label>
              <input type="number" class="form-control" id="precio" name="precio" placeholder="Precio" required min="0" step="any" value="<?php echo $precio; ?>">
            </div>

            <div class="col-lg-4 form-group">
              <label for="marca_id">Marca</label>
              <select class="form-control custom-select" id="marca_id" name="marca_id">
                <option value="">Seleccionar Marca</option>
                <?php
                // Consultar las marcas disponibles
                $queryMarcas = "SELECT id, nombre FROM marca";
                $resultMarcas = mysqli_query($conn, $queryMarcas);
                while ($rowMarca = mysqli_fetch_assoc($resultMarcas)) {
                  $marcaId = $rowMarca['id'];
                  $nombreMarca = $rowMarca['nombre'];
                  $selected = ($marcaId == $marca_id) ? "selected" : "";
                  echo "<option value='$marcaId' $selected>$nombreMarca</option>";
                }
                ?>
              </select>
            </div>

            <div class="col-lg-4 form-group">
              <label for="categoria_id">Categoría</label>
              <select class="form-control custom-select" id="categoria_id" name="categoria_id" onchange="showSubcategorias()">
                <option value="">Seleccionar Categoría</option>
                <?php
                // Consultar las categorías disponibles
                $queryCategorias = "SELECT id, nombre FROM categoria";
                $resultCategorias = mysqli_query($conn, $queryCategorias);
                while ($rowCategoria = mysqli_fetch_assoc($resultCategorias)) {
                  $categoriaId = $rowCategoria['id'];
                  $nombreCategoria = $rowCategoria['nombre'];
                  $selected = ($categoriaId == $categoria_id) ? "selected" : "";
                  echo "<option value='$categoriaId' $selected>$nombreCategoria</option>";
                }
                ?>
              </select>
            </div>

            <div class="col-lg-4 form-group" id="subcategoriaDiv" style="display: <?php echo ($subcategoria_id) ? 'block' : 'none'; ?>;">
              <label for="subcategoria_id">Subcategoría</label>
              <select class="form-control custom-select" id="subcategoria_id" name="subcategoria_id">
                <option value="">Seleccionar Subcategoría</option>
                <?php
                // Consultar todas las subcategorías disponibles
                $querySubcategorias = "SELECT id, nombre, categoria_id FROM subcategoria";
                $resultSubcategorias = mysqli_query($conn, $querySubcategorias);
                while ($rowSubcategoria = mysqli_fetch_assoc($resultSubcategorias)) {
                  $subcategoriaId = $rowSubcategoria['id'];
                  $nombreSubcategoria = $rowSubcategoria['nombre'];
                  $categoriaIdSubcategoria = $rowSubcategoria['categoria_id'];
                  $selected = ($subcategoriaId == $subcategoria_id) ? "selected" : "";
                  echo "<option value='$subcategoriaId' data-categoria='$categoriaIdSubcategoria' $selected>$nombreSubcategoria</option>";
                }
                ?>
              </select>
            </div>

            <div class="col-lg-12 form-group">
              <label for="descripcion">Descripción</label>
              <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Descripción del producto" required rows="5"><?php echo $descripcion; ?></textarea>
            </div>
            <div class="col-lg-4 form-group">
              <label for="talla">Talla seleccionada:</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="talla_ropa" name="tallas_seleccionadas[]" value="ropa" <?php if (isset($talla) && in_array('ropa', explode(', ', $talla))) echo 'checked'; ?>>
                <label class="form-check-label" for="talla_ropa">Ropa</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="talla_calzado" name="tallas_seleccionadas[]" value="calzado" <?php if (isset($talla) && in_array('calzado', explode(', ', $talla))) echo 'checked'; ?>>
                <label class="form-check-label" for="talla_calzado">Calzado</label>
              </div>
              <div class="mt-3">
                <label for="tallasLimpio">Tallas (separadas por comas):</label>
                <input type="text" class="form-control" id="tallasLimpio" name="tallasLimpio" placeholder="<?php echo isset($talla) && in_array('ropa', explode(', ', $talla)) ? 'Ejemplo: M, L' : 'Ejemplo: 12, 15'; ?>" value="<?php echo htmlspecialchars($tallasLimpio); ?>" <?php if (isset($talla) && (in_array('ropa', explode(', ', $talla)) || in_array('calzado', explode(', ', $talla)))) echo 'disabled'; ?>>
              </div>
            </div>
          </div>
          <!-- Fin del formulario principal -->
        </div>
        <div class="col-lg-4">
          <!-- Formulario de subida de imagen -->
          <div class="form-group">
            <label for="borrar-imagenes">Borrar imágenes existentes</label>
            <input type="checkbox" class="form-check-input" id="borrar-imagenes" name="borrar_imagenes" value="1">
          </div>

          <div class="form-group">
            <label for="imagen-producto">
              Subir imagen del producto <br class="red-text"> (Para ordenarlos, subir luego actualizar y asi con el siguiente!)
            </label>
            <input type="file" class="form-control-file" id="imagenes" name="imagenes[]" multiple accept=".png, .jpg, .jpeg, .webp">
          </div>
          <div class="form-group">
            <div id="imagen-producto-preview" class="d-flex flex-wrap">
              <?php if (isset($rutaImagen) && !empty($rutaImagen)) : ?>
                <?php
                $imagenesProducto = glob($rutaImagen . '/*');
                foreach ($imagenesProducto as $imagenProducto) {
                  echo "<img src='$imagenProducto' alt='Previsualización de la imagen'>";
                }
                ?>
              <?php else : ?>
                <p>No se ha seleccionado ninguna imagen para el producto</p>
              <?php endif; ?>
            </div>
          </div>
          <!-- Fin del formulario de subida de imagen -->
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Actualizar producto</button>
        <a href="ver_productos.php" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
  </div>

  <!-- Mostrar mensaje de éxito si existe -->
  <?php if (isset($success)) : ?>
    <script src="assets/js/iziToast.min.js"></script>
    <link rel="stylesheet" href="assets/css/iziToast.min.css">
    <script>
      window.onload = function() {
        iziToast.success({
          title: "Éxito",
          message: "<?php echo $success; ?>",
          position: "topRight"
        });
      };
    </script>
  <?php endif; ?>

  <!-- Mostrar mensaje de error si existe -->
  <?php if (isset($error)) : ?>
    <script src="assets/js/iziToast.min.js"></script>
    <link rel="stylesheet" href="assets/css/iziToast.min.css">
    <script>
      window.onload = function() {
        iziToast.error({
          title: "Error",
          message: "<?php echo $error; ?>",
          position: "topRight"
        });
      };
    </script>
  <?php endif; ?>


</div>


</section>
</main>

<!-- Back to top button-->
<a class="btn-scroll-top" href="#top" data-scroll data-fixed-element>
  <span class="btn-scroll-top-tooltip text-muted font-size-sm mr-2">Top</span>
  <i class="btn-scroll-top-icon cxi-angle-up"></i>
</a>

<!-- Vendor scripts: js libraries and plugins-->
<script src="assets/vendor/jquery/dist/jquery.slim.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/simplebar/dist/simplebar.min.js"></script>
<script src="assets/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

<script>
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
</script>


<script>
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
          defaultImage.src = './assets/img/productos_img/default.png';
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
</script>


<script>
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
</script>


<!-- Main theme script-->
<script src="assets/js/theme.min.js"></script>
</body>

</html>