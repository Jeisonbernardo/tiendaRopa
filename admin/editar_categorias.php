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

// Verificar si se ha proporcionado el ID de la categoría a editar
if (isset($_GET['id'])) {
  // Obtener el ID de la categoría desde la URL
  $categoriaId = $_GET['id'];

  // Consultar los datos de la categoría desde la base de datos utilizando el ID
  $query = "SELECT id, nombre, imagen FROM categoria WHERE id = $categoriaId";
  $result = mysqli_query($conn, $query);

  // Verificar si se encontró la categoría
  if (mysqli_num_rows($result) > 0) {
    // Obtener los detalles de la categoría
    $categoria = mysqli_fetch_assoc($result);
    $nombreCategoria = $categoria['nombre'];
    $imagenCategoria = $categoria['imagen'];
  } else {
    // Si no se encontró la categoría, redirigir a la página de listar categorías
    header('Location: ver_categorias.php');
    exit;
  }
} else {
  // Si no se proporcionó el ID de la categoría, redirigir a la página de listar categorías
  header('Location: ver_categorias.php');
  exit;
}

// Verificar si el formulario se ha enviado para actualizar la categoría
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Obtener los datos ingresados por el usuario
  $nombrecategoria = $_POST['nombre'];

  // Verificar si el nombre de categoría ha cambiado
  if ($nombrecategoria != $nombreCategoria) {
    // Verificar si el nombre de categoría ya existe en la base de datos
    $query = "SELECT id FROM categoria WHERE nombre = ? AND id <> ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $nombrecategoria, $categoriaId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
      // Si el nombre de categoría ya existe, mostrar un mensaje de error
      $error = "El nombre de categoría ya existe, por favor ingrese otro.";
    }
  }

  // Verificar si se seleccionó una nueva imagen
  if (!empty($_FILES['imagen']['name'])) {
    // Obtener el archivo de la nueva imagen
    $imagen = $_FILES['imagen']['tmp_name'];
    $nombreImagen = $_FILES['imagen']['name'];

    // Verificar si se seleccionó una imagen válida
    $extension = pathinfo($nombreImagen, PATHINFO_EXTENSION);
    $extension = strtolower($extension); // Convertir a minúsculas para mayor consistencia
    $extensionesPermitidas = array('jpg', 'jpeg', 'png', 'gif');

    if (!in_array($extension, $extensionesPermitidas)) {
      // Mostrar mensaje de error si la imagen no tiene una extensión permitida
      $error = "La imagen debe estar en un formato válido (jpg, jpeg, png o gif)";
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
    }
  } else {
    // Si no se seleccionó una nueva imagen, mantener la imagen actual
    $rutaImagen = $imagenCategoria;
  }

  // Verificar si no hay errores antes de actualizar la categoría en la base de datos
  if (!isset($error)) {
    // Actualizar la categoría en la base de datos
    $query = "UPDATE categoria SET nombre = ?, imagen = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $nombrecategoria, $rutaImagen, $categoriaId);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
      // Mostrar mensaje de éxito si los datos se actualizaron correctamente
      $success = "Categoría actualizada correctamente.";
      $nombreCategoria = $nombrecategoria; // Actualizar el nombre actual con el nuevo nombre
      $imagenCategoria = $rutaImagen;
    } else {
      // Mostrar mensaje de error si no se pudieron actualizar los datos
      $error = "Hubo un error al actualizar la categoría, intentelo mas tarde.";
    }
  }
}

include 'vistas/template/navbar.php';
?>

<!-- Page title-->
<div class="border-bottom pt-5 mt-2 mb-5">
  <h1 class="mt-2 mt-md-4 mb-3 pt-5">EDITAR CATEGORÍAS</h1>
  <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
    <p class="text-muted">Aquí puedes editar la categoria seleccionada</p>
  </div>
</div>

<div class="card box-shadow-sm">
  <div class="card-header">
    <h5 style="margin-bottom: 0px;">EDITAR CATEGORÍA</h5>
  </div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="nombre">Nombre de la categoría:</label>
        <!-- Rellenar el campo con el nombre de la categoría obtenido de la base de datos -->
        <input type="text" class="form-control" id="nombrecategoria" name="nombre" value="<?php echo $nombreCategoria; ?>" required>
      </div>
      <div class="form-group">
        <label for="imagen">Imagen de la categoria:</label>
        <input type="file" class="form-control-file" id="imagen" name="imagen">
        <br>
        <div id="imagen-preview" style="max-width: 200px; max-height: 200px;">
          <!-- Mostrar la imagen de la categoría obtenida de la base de datos -->
          <?php if (!empty($imagenCategoria)) : ?>
            <img src="<?php echo $imagenCategoria . '?t=' . time(); ?>" alt="Previsualización de la imagen" style="max-width: 200px; max-height: 200px;">
          <?php else : ?>
            <p>No se ha seleccionado ninguna imagen para la categoría</p>
          <?php endif; ?>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Actualizar categoría</button>
      <!-- Añadir el botón "Cancelar" que redirige a la página de listar categorías -->
      <a href="ver_categorias.php" class="btn btn-secondary">Cancelar</a>
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