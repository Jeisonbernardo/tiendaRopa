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

  // Obtener el archivo de las imágenes
  $imagenes = $_FILES['imagenes']['tmp_name'];
  $nombreImagenes = $_FILES['imagenes']['name'];

  // Verificar si se seleccionaron imágenes
  if ($imagenes && $nombreImagenes) {
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

    // Obtener el número de imágenes existentes en el directorio del producto
    $numImagenesExistentes = count(glob($directorioProducto . '/*'));

    // Recorrer las imágenes seleccionadas y guardarlas en la carpeta del producto
    for ($i = 0; $i < $numImagenes; $i++) {
      $imagen = $imagenes[$i];
      $nombreImagen = $nombreImagenes[$i];
      $extension = pathinfo($nombreImagen, PATHINFO_EXTENSION);
      $extension = strtolower($extension); // Convertir a minúsculas para mayor consistencia

      // Verificar si la extensión de la imagen es válida
      if (!in_array($extension, $extensionesPermitidas)) {
        // Mostrar mensaje de error si la imagen no tiene una extensión permitida
        $error = "Las imágenes deben estar en formato JPG, JPEG, PNG o WEBP.";
        $insertarproducto = false; // No insertar el producto en la base de datos
        break; // Salir del bucle for en caso de error
      }

      // Formatear el nombre de la imagen
      $nombreImagenFormateado = $nombreCarpeta . '_' . ($numImagenesExistentes + $i + 1) . '.png';

      // Ruta de la imagen
      $rutaImagen = $directorioProducto . '/' . $nombreImagenFormateado;

      // Mover la imagen a la carpeta del producto
      move_uploaded_file($imagen, $rutaImagen);

      // Redimensionar y convertir la imagen a formato PNG
      $imagenRedimensionada = imagecreatefromstring(file_get_contents($rutaImagen));
      $nuevoAncho = 340;
      $nuevoAlto = 340;
      $imagenRedimensionada = imagescale($imagenRedimensionada, $nuevoAncho, $nuevoAlto);
      imagepng($imagenRedimensionada, $rutaImagen, 9);
      imagedestroy($imagenRedimensionada);
    }

    // Verificar si se seleccionaron tallas
    if (empty($_POST['tallas_ropa']) && empty($_POST['tallas_calzado'])) {
      $error = "Debes seleccionar al menos una talla.";
      $insertarproducto = false; // No insertar el producto en la base de datos
    } elseif (empty($subcategoria_id)) {
      $error = "Primero crea una subcategoría.";
      $insertarproducto = false; // No insertar el producto en la base de datos
    } else {
      $insertarproducto = true; // Insertar el producto en la base de datos

      $tallasSeleccionadas = array(); // Crear un array para las tallas seleccionadas

      if (isset($_POST['tallas_ropa']) && is_array($_POST['tallas_ropa'])) {
        $tallasRopa = $_POST['tallas_ropa'];

        // Verificar si se eligió ropa y la talla "ropa"
        if (in_array('ropa', $tallasRopa)) {
          $key = array_search('ropa', $tallasRopa);
          unset($tallasRopa[$key]);
          $tallasSeleccionadas[] = 'ropa';
        }

        $tallasSeleccionadas = array_merge($tallasSeleccionadas, $tallasRopa);
      }

      if (isset($_POST['tallas_calzado']) && !empty($_POST['tallas_calzado'])) {
        $tallasCalzado = $_POST['tallas_calzado'];
        $tallasCalzadoArray = explode(',', $tallasCalzado);
        $tallasSeleccionadas[] = 'calzado';
        $tallasSeleccionadas = array_merge($tallasSeleccionadas, $tallasCalzadoArray);

        // Eliminar cualquier duplicado de "calzado"
        $tallasSeleccionadas = array_unique($tallasSeleccionadas);
      }

      // Concatenar las tallas seleccionadas en un solo texto, separado por comas
      $tallasSeleccionadas = implode(', ', $tallasSeleccionadas);
    }
  } //

  // Verificar si ya existe un producto con el mismo nombre
  $queryExistenciaProducto = "SELECT id FROM producto WHERE nombre = '$nombre'";
  $resultExistenciaProducto = mysqli_query($conn, $queryExistenciaProducto);

  if (mysqli_num_rows($resultExistenciaProducto) > 0) {
    // Si ya existe un producto con el mismo nombre, mostrar mensaje de error
    $error = "El nombre de este producto ya existe. Por favor, elige otro nombre.";
    $insertarproducto = false;
  }

  // Insertar los datos en la tabla "producto" si el nombre de producto no está en uso
  if ($insertarproducto) {
    $query = "INSERT INTO producto (nombre, imagenes, stock, precio, categoria_id, marca_id, descripcion, created_at, updated_at, subcategoria_id, tallas_seleccionadas) VALUES ('$nombre', '$directorioProducto', '$stock', '$precio', '$categoria_id', '$marca_id', '$descripcion', NOW(), NOW(), '$subcategoria_id', '$tallasSeleccionadas')";
    $result = mysqli_query($conn, $query);

    if ($result) {
      // Mostrar mensaje de éxito si los datos se agregaron correctamente
      $success = "Producto agregado correctamente.";
    } else {
      // Mostrar mensaje de error si no se pudieron agregar los datos
      $error = "Hubo un error al agregar el producto.";
    }
  }
}

include 'Vistas/template/navbar.php';
?>

<!-- Page title-->
<div class="border-bottom pt-5 mt-2 mb-5">
  <h1 class="mt-2 mt-md-4 mb-3 pt-5">AÑADIR PRODUCTOS</h1>
  <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
    <p class="text-muted">Aquí puedes añadir los productos</p>
    <p class="font-size-sm font-weight-medium pl-md-4"><a class="text-nowrap" href="./ver_productos.php" rel="noopener">Ver productos añadidos<i class="cxi-angle-right font-size-base align-middle ml-1"></i></a></p>
  </div>
</div>

<div class="card box-shadow-sm">
  <div class="card-header">
    <h5 style="margin-bottom: 0px;">Registro de producto</h5>
  </div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <div class="row">
        <div class="col-lg-8">
          <!-- Formulario principal -->
          <div class="row">
            <div class="col-lg-12 form-group">
              <label for="nombre">Titulo de producto</label>
              <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Titulo de producto" required>
            </div>
            <div class="col-lg-4 form-group">
              <label for="stock">Stock</label>
              <input type="number" class="form-control" id="stock" name="stock" placeholder="Cantidad inicial" required min="0">
            </div>
            <div class="col-lg-4 form-group">
              <label for="precio">Precio (S/.)</label>
              <input type="number" class="form-control" id="precio" name="precio" placeholder="Precio" required min="0" step="any">
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
                  echo "<option value='$marcaId'>$nombreMarca</option>";
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
                  echo "<option value='$categoriaId'>$nombreCategoria</option>";
                }
                ?>
              </select>
            </div>

            <div class="col-lg-4 form-group" id="subcategoriaDiv" style="display: none;">
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
                  echo "<option value='$subcategoriaId' data-categoria='$categoriaIdSubcategoria'>$nombreSubcategoria</option>";
                }
                ?>
              </select>
            </div>

            <div class="col-lg-4 form-group">
              <label for="talla">Talla</label>
              <select class="form-control custom-select" name="tallas_ropa[]" id="talla" class="form-control">
                <option value="">Seleccionar Talla para:</option>
                <option value="ropa">Ropa</option>
                <option value="calzado">Calzado</option>
              </select>
            </div>

            <div class="col-lg-4 form-group" id="tallas_seleccionadas" style="display: none;">
              <!-- Espacio para mostrar tallas de ropa o de calzado según la selección -->
            </div>

            <div class="col-lg-12 form-group">
              <label for="descripcion">Descripción</label>
              <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Descripción corta" required rows="5"></textarea>
            </div>
          </div>
          <!-- Fin del formulario principal -->
        </div>
        <div class="col-lg-4">
          <!-- Formulario de subida de imagen -->
          <div class="form-group">
            <label for="imagen-producto">Subir imagen del producto:</label>
            <input type="file" class="form-control-file" id="imagenes" name="imagenes[]" multiple accept=".png, .jpg, .jpeg, .webp">
          </div>
          <div class="form-group">
            <div id="imagen-producto-preview" class="d-flex flex-wrap">
              <?php if (isset($rutaImagenDefecto) && !empty($rutaImagenDefecto)) : ?>
                <img src="<?php echo $rutaImagenDefecto . '?t=' . time(); ?>" alt="Previsualización de la imagen">
              <?php else : ?>
                <p>No se ha seleccionado ninguna imagen para la categoria</p>
              <?php endif; ?>
            </div>
          </div>
          <!-- Fin del formulario de subida de imagen -->
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Crear producto</button>
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
          defaultImage.src = 'assets/img/productos_img/default.png';
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
  document.addEventListener('DOMContentLoaded', function() {
    var tallaSelect = document.getElementById('talla');
    var tallasDiv = document.getElementById('tallas_seleccionadas');

    tallaSelect.addEventListener('change', function() {
      var tallaSeleccionada = tallaSelect.value;
      tallasDiv.innerHTML = '';
      tallasDiv.style.display = 'block';

      if (tallaSeleccionada === 'ropa') {
        var tallasRopa = ['XS', 'S', 'M', 'L', 'XL'];
        tallasRopa.forEach(function(talla) {
          tallasDiv.innerHTML += '<input type="checkbox" name="tallas_ropa[]" value="' + talla + '"> ' + talla + '<br>';
        });
      } else if (tallaSeleccionada === 'calzado') {
        tallasDiv.innerHTML += '<label for="tallas_calzado">Ingrese las tallas de calzado (separadas por comas):</label><br>';
        tallasDiv.innerHTML += '<input type="text" name="tallas_calzado" id="tallas_calzado" class="form-control">';
      }
    });
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