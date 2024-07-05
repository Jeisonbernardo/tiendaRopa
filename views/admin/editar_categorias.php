<?php include '../../config/config.php';
include BASE_PATH . 'config/conexion.php'; ?>
<?php include '../template/navbar_admin.php'; ?>

<?php


// Definir la ruta de la imagen por defecto
$rutaImagenDefecto = '../../admin/assets/img/categoria_img/default.jpg';


// Verificar si NO se ha iniciado sesión y NO hay un token almacenado
if (!isset($_SESSION['token'])) {
  header('Location: loginconf.php');
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
    header('Location: categorias.php');
    exit;
  }
} else {
  // Si no se proporcionó el ID de la categoría, redirigir a la página de listar categorías
  header('Location: categorias.php');
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
      $rutaImagen = '../../admin/assets/img/categoria_img/' . $nombreImagenFormateado;
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

?>

<div class="container">
  <div class="border-bottom pt-1 mt-2 mb-4">
    <h1 class="mt-2 mt-md-4 mb-3 pt-5">EDITAR CATEGORÍAS</h1>
    <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
      <p class="text-muted">Aquí puedes editar la categoria seleccionada</p>
      <p class="font-size-sm font-weight-medium pl-md-4">
        <a class="text-nowrap" href="./categorias.php" rel="noopener">
          Ver categorías actualizadas
          <i class="cxi-angle-right font-size-base align-middle ml-1"></i>
        </a>
      </p>
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
        <a href="categorias.php" class="btn btn-secondary">Cancelar</a>
      </form>

      <!-- Mostrar mensaje de éxito si existe -->
      <?php if (isset($success)) : ?>
        <script src="../../admin/assets/js/iziToast.min.js"></script>
        <link rel="stylesheet" href="../../admin/assets/css/iziToast.min.css">
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
        <script src="../../admin/assets/js/iziToast.min.js"></script>
        <link rel="stylesheet" href="../../admin/assets/css/iziToast.min.css">
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
</div> <br><br><br>


<script src="../../admin/assets/js/editar_categorias.js"></script>
<?php include '../template/footer_admin.php'; ?>
