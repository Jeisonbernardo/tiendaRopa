<?php include '../../config/config.php';
include BASE_PATH . 'config/conexion.php'; ?>
<?php include '../template/navbar_admin.php'; ?>


<?php

// Definir la ruta de la imagen por defecto
$rutaImagenDefecto = '../../admin/assets/img/marca_img/default.png';


// Verificar si NO se ha iniciado sesión y NO hay un token almacenado
if (!isset($_SESSION['token'])) {
  header('Location: loginconf.php');
  exit;
}

// Verificar si se ha proporcionado el ID de la marca a editar
if (isset($_GET['id'])) {
  // Obtener el ID de la marca desde la URL
  $marcaId = $_GET['id'];

  // Consultar los datos de la marca desde la base de datos utilizando el ID
  $query = "SELECT id, nombre, imagen FROM marca WHERE id = $marcaId";
  $result = mysqli_query($conn, $query);

  // Verificar si se encontró la marca
  if (mysqli_num_rows($result) > 0) {
    // Obtener los detalles de la marca
    $marca = mysqli_fetch_assoc($result);
    $nombremarca = $marca['nombre'];
    $imagenmarca = $marca['imagen'];

    // Mantener el valor de la imagen actual antes de procesar el formulario
    $rutaImagen_actual = $imagenmarca;

    // Resto del código...
  } else {
    // Si no se encontró la marca, redirigir a la página de listar marcas
    header('Location: marcas.php');
    exit;
  }
} else {
  // Si no se proporcionó el ID de la marca, redirigir a la página de listar marcas
  //header('Location: marcas.php');
  exit;
}

// Verificar si el formulario se ha enviado para actualizar la marca
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Variable para controlar si hubo algún error durante el proceso
  $errorOccurred = false;

  // Obtener el nombre ingresado por el usuario
  $nombremarca = $_POST['nombre'];

  // Verificar si el nombre de marca ha cambiado
  if ($nombremarca !=$nombremarca) {
    // Verificar si el nombre de marca ya existe en la base de datos
    $query = "SELECT id FROM marca WHERE nombre = ? AND id <> ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $nombremarca, $marcaId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
      // Si el nombre de marca ya existe, mostrar un mensaje de error
      $error = "El nombre de marca ya existe, por favor ingrese otro.";
      $errorOccurred = true; // Establecer la variable de error a true
    }
  }

  // Verificar si se seleccionó una nueva imagen
  if (!empty($_FILES['imagen']['name'])) {
    // Obtener el archivo de la nueva imagen
    $imagen = $_FILES['imagen']['tmp_name'];
    $nombreImagen = $_FILES['imagen']['name'];

    // Verificar si se seleccionó una imagen válida (formato .png)
    $extension = pathinfo($nombreImagen, PATHINFO_EXTENSION);
    if (strtolower($extension) != 'png') {
      // Mostrar mensaje de error si la imagen no es de formato .png
      $error = "La imagen debe estar en formato .png";
      $errorOccurred = true; // Establecer la variable de error a true
      $nombremarca = $nombremarca;
      $rutaImagen_actual = $imagenmarca;
    } else {
      // Formatear el nombre de la imagen
      $nombreImagenFormateado = time() . '_' . strtolower(str_replace(' ', '_', $nombremarca)) . '.png';

      // Mover la nueva imagen a la carpeta donde se almacenarán las imágenes de marca
      $rutaImagen = '../../admin/assets/img/marca_img/' . $nombreImagenFormateado;
      move_uploaded_file($imagen, $rutaImagen);

      // Actualizar el valor de $rutaImagen_actual con la nueva ruta de imagen
      $rutaImagen_actual = $rutaImagen;
    }
  } else {
    // Si no se seleccionó una nueva imagen, mantener la imagen actual
    $rutaImagen = $imagenmarca;
    $rutaImagen_actual = $imagenmarca;
  }

  // Actualizar la marca en la base de datos solo si no hubo errores
  if (!$errorOccurred) {
    // Actualizar la marca en la base de datos con el nuevo nombre (y la nueva imagen si aplica)
    $query = "UPDATE marca SET nombre = ?, imagen = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $nombremarca, $rutaImagen, $marcaId);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
      // Mostrar mensaje de éxito si los datos se actualizaron correctamente
      $success = "Marca actualizada correctamente.";
      $rutaImagen_actual = $rutaImagen; // Actualizar la ruta de la imagen actual con la nueva ruta
    } else {
      // Mostrar mensaje de error si no se pudieron actualizar los datos
      $error = "Hubo un error al actualizar la marca, inténtelo más tarde.";
    }
  }
}

?>

<div class="container">
  <div class="border-bottom pt-1 mt-2 mb-5">
    <h1 class="mt-2 mt-md-4 mb-3 pt-5">EDITAR MARCAS</h1>
    <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
      <p class="text-muted">Aquí puedes editar las marcas</p>
      <p class="font-size-sm font-weight-medium pl-md-4">
        <a class="text-nowrap" href="./marcas.php" rel="noopener">
          Ver marcas actualizadas
          <i class="cxi-angle-right font-size-base align-middle ml-1"></i>
        </a>
      </p>

    </div>
  </div>

  <div class="card box-shadow-sm">
    <div class="card-header">
      <h5 style="margin-bottom: 0px;">EDITAR MARCA</h5>
    </div>
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="nombre">Nombre de la marca:</label>
          <input type="text" class="form-control" id="nombre" name="nombre" required value="<?php echo $nombremarca; ?>">
        </div>
        <div class="form-group">
          <label for="imagen">Imagen de la marca (formato .png):</label>
          <input type="file" class="form-control-file" id="imagen" name="imagen" accept=".png">
          <br>
          <div id="imagen-preview" style="max-width: 200px; max-height: 200px;">
            <?php if (!empty($rutaImagen_actual)) : ?>
              <!-- Agregar un parámetro aleatorio a la URL de la imagen -->
              <img src="<?php echo $rutaImagen_actual . '?t=' . time(); ?>" alt="Previsualización de la imagen">
            <?php else : ?>
              <p>No se ha seleccionado ninguna imagen de marca</p>
            <?php endif; ?>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar marca</button>
        <a href="marcas.php" class="btn btn-secondary">Cancelar</a>
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

</div>


<script src="../../admin/assets/js/editar_marcas.js"></script>
<?php include '../template/footer_admin.php'; ?>