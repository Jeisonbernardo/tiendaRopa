<?php
// Incluir el archivo de conexión
include 'conexion.php';

// Definir la ruta de la imagen por defecto
$rutaImagenDefecto = 'assets/img/categoria_img/default.jpg';

// Iniciar la sesión
session_start();

// Verificar si NO se ha iniciado sesión y NO hay un token almacenado
if (!isset($_SESSION['token'])) {
  header('Location: login.php');
  exit;
}

// Verificar si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Obtener los datos ingresados por el usuario
  $nombrecategoria = $_POST['nombre'];

  // Obtener el archivo de la imagen
  $imagen = $_FILES['imagen']['tmp_name'];
  $nombreImagen = $_FILES['imagen']['name'];

  // Verificar si se seleccionó una imagen
  if ($imagen && $nombreImagen) {
    // Obtener la extensión de la imagen
    $extension = pathinfo($nombreImagen, PATHINFO_EXTENSION);
    $extension = strtolower($extension); // Convertir a minúsculas para mayor consistencia

    // Definir un array con las extensiones de imagen permitidas
    $extensionesPermitidas = array('jpg', 'jpeg', 'png', 'gif', 'webp'); // Puedes agregar más extensiones si lo deseas

    if (!in_array($extension, $extensionesPermitidas)) {
      // Mostrar mensaje de error si la imagen no tiene una extensión permitida
      $error = "La imagen debe estar en un formato válido (jpg, jpeg, png o gif)";
      $insertarcategoria = false; // No insertar la categoría en la base de datos
    } else {
      // Formatear el nombre de la imagen
      $nombreImagenFormateado = time() . '_' . strtolower(str_replace(' ', '_', $nombrecategoria)) . '.' . $extension;

      // Mover la imagen a la carpeta donde se almacenarán las imágenes de categoría
      $rutaImagen = './assets/img/categoria_img/' . $nombreImagenFormateado;
      move_uploaded_file($imagen, $rutaImagen);

      // Redimensionar la imagen a 340x340 píxeles
      list($anchoOriginal, $altoOriginal) = getimagesize($rutaImagen);
      $nuevoAncho = 340;
      $nuevoAlto = 340;
      $imagenRedimensionada = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

      if ($extension == 'jpg' || $extension == 'jpeg') {
        $imagenOriginal = imagecreatefromjpeg($rutaImagen);
      } elseif ($extension == 'png') {
        $imagenOriginal = imagecreatefrompng($rutaImagen);
      } elseif ($extension == 'gif') {
        $imagenOriginal = imagecreatefromgif($rutaImagen);
      }

      imagecopyresampled($imagenRedimensionada, $imagenOriginal, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $anchoOriginal, $altoOriginal);

      // Guardar la imagen redimensionada en la ruta final
      if ($extension == 'jpg' || $extension == 'jpeg') {
        imagejpeg($imagenRedimensionada, $rutaImagen, 100);
      } elseif ($extension == 'png') {
        imagepng($imagenRedimensionada, $rutaImagen, 9);
      } elseif ($extension == 'gif') {
        imagegif($imagenRedimensionada, $rutaImagen);
      }

      // Liberar memoria
      imagedestroy($imagenRedimensionada);
      imagedestroy($imagenOriginal);

      $insertarcategoria = true; // Insertar la categoría en la base de datos
    }
  } else {
    // Si no se seleccionó una imagen, utilizar la imagen por defecto
    $rutaImagen = './assets/img/categoria_img/default.jpg';
    $insertarcategoria = true; // Insertar la categoría en la base de datos
  }

  // Verificar si el nombre de categoría ya está en uso
  $queryExistenciaCategoria = "SELECT id FROM categoria WHERE nombre = '$nombrecategoria'";
  $resultExistenciaCategoria = mysqli_query($conn, $queryExistenciaCategoria);

  if (mysqli_num_rows($resultExistenciaCategoria) > 0) {
    // Si ya existe una categoría con el mismo nombre, mostrar mensaje de error
    $error = "El nombre de categoría ya está en uso. Por favor, elige otro nombre.";
    $insertarcategoria = false; // No insertar la categoría en la base de datos
  }

  // Insertar los datos en la tabla "categoria" si el nombre de categoría no está en uso
  if ($insertarcategoria) {
    $query = "INSERT INTO categoria (nombre, imagen) VALUES ('$nombrecategoria', '$rutaImagen')";
    $result = mysqli_query($conn, $query);

    if ($result) {
      // Mostrar mensaje de éxito si los datos se agregaron correctamente
      $success = "Categoría agregada correctamente.";
    } else {
      // Mostrar mensaje de error si no se pudieron agregar los datos
      $error = "Hubo un error al agregar la categoría.";
    }
  }
}

include 'Vistas/template/navbar.php';
?>

<!-- Page title-->
<div class="border-bottom pt-5 mt-2 mb-5">
  <h1 class="mt-2 mt-md-4 mb-3 pt-5">AÑADIR CATEGORÍAS</h1>
  <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
    <p class="text-muted">Aquí puedes añadir las categorias</p>
    <p class="font-size-sm font-weight-medium pl-md-4"><a class="text-nowrap" href="./ver_categorias.php" rel="noopener">Ver categorias añadidas<i class="cxi-angle-right font-size-base align-middle ml-1"></i></a></p>
  </div>
</div>

<div class="card box-shadow-sm">
  <div class="card-header">
    <h5 style="margin-bottom: 0px;">AÑADIR CATEGORÍA</h5>
  </div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="nombre">Nombre de la categoría:</label>
        <input type="text" class="form-control" id="nombrecategoria" name="nombre" required>
      </div>
      <div class="form-group">
        <label for="imagen">Imagen de la categoria:</label>
        <input type="file" class="form-control-file" id="imagen" name="imagen">
        <br>
        <div id="imagen-preview" style="max-width: 200px; max-height: 200px;">
          <?php if (isset($rutaImagenDefecto) && !empty($rutaImagenDefecto)) : ?>
            <img src="<?php echo $rutaImagenDefecto . '?t=' . time(); ?>" alt="Previsualización de la imagen">
          <?php else : ?>
            <p>No se ha seleccionado ninguna imagen para la categoria</p>
          <?php endif; ?>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Agregar categoría</button>
    </form>

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
</div>

</div>
</section>
</main>

<script>
  // Código JavaScript para mostrar la imagen de marca en la vista previa
  // Este código asume que el elemento "imagen-preview" existe en el DOM.

  // Verificar si hay una imagen de marca seleccionada y mostrarla en la vista previa
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
      defaultImage.src = 'assets/img/categoria_img/default.jpg';
      defaultImage.alt = 'Imagen por defecto';
      defaultImage.style.maxWidth = '100%';
      defaultImage.style.maxHeight = '100%';
      imagenPreview.innerHTML = '';
      imagenPreview.appendChild(defaultImage);
    }
  });
</script>

<!-- Back to top button-->
<a class="btn-scroll-top" href="#top" data-scroll data-fixed-element>
  <span class="btn-scroll-top-tooltip text-muted font-size-sm mr-2"></span>
  <i class="btn-scroll-top-icon cxi-angle-up"></i>
</a>

<script src="assets/vendor/jquery/dist/jquery.slim.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/simplebar/dist/simplebar.min.js"></script>
<script src="assets/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

<!-- Main theme script-->
<script src="assets/js/theme.min.js"></script>
</body>

</html>