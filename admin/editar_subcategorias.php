<?php
// Incluir el archivo de conexión
include 'conexion.php';

// Iniciar la sesión
session_start();

// Verificar si NO se ha iniciado sesión y NO hay un token almacenado
if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}

// Verificar si se ha proporcionado el ID de la subcategoría a editar
if (isset($_GET['id'])) {
  // Obtener el ID de la subcategoría desde la URL y validar/sanitizar el valor
  $subcategoriaId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

  // Verificar si el valor de ID es válido (es decir, un entero mayor que 0)
  if ($subcategoriaId === false || $subcategoriaId <= 0) {
      // Redirigir a la página de listar subcategorías si el ID no es válido
      header('Location: ver_subcategorias.php');
      exit;
  }

  // Consultar los datos de la subcategoría desde la base de datos utilizando el ID
  $query = "SELECT id, nombre, categoria_id FROM subcategoria WHERE id = $subcategoriaId";
  $result = mysqli_query($conn, $query);

  // Verificar si se encontró la subcategoría
  if (mysqli_num_rows($result) > 0) {
      // Obtener los detalles de la subcategoría
      $subcategoria = mysqli_fetch_assoc($result);
      $nombresubcategoria = $subcategoria['nombre'];
      $categoria_id = $subcategoria['categoria_id'];
  } else {
      // Si no se encontró la subcategoría, redirigir a la página de listar subcategorías
      header('Location: ver_subcategorias.php');
      exit;
  }
} else {
  // Si no se proporcionó el ID de la subcategoría, redirigir a la página de listar subcategorías
  header('Location: ver_subcategorias.php');
  exit;
}

// Verificar si el formulario se ha enviado
// Verificar si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Obtener los datos ingresados por el usuario
  $nombresubcategoria = $_POST['nombresubcategoria'];
  $categoriaId = $_POST['categoria'];

  // Verificar si ya existe una subcategoría con el mismo nombre
  $queryExistesubcategoria = "SELECT id FROM subcategoria WHERE nombre='$nombresubcategoria' AND id != $subcategoriaId";
  $resultExistesubcategoria = mysqli_query($conn, $queryExistesubcategoria);

  if (mysqli_num_rows($resultExistesubcategoria) > 0) {
    // Mostrar mensaje de error si ya existe una subcategoría con el mismo nombre
    echo '<script src="assets/js/iziToast.min.js"></script>';
    echo '<link rel="stylesheet" href="assets/css/iziToast.min.css">';
    echo '<script>
          window.onload = function() {
            iziToast.error({
              title: "Error",
              message: "Ese nombre ya está en uso, elige otro.",
              position: "topRight"
            });
          };
        </script>';
  } else {
    // Actualizar los datos en la tabla "subcategoria" con el ID de la subcategoría seleccionada
    $query = "UPDATE subcategoria SET nombre='$nombresubcategoria', categoria_id='$categoriaId' WHERE id='$subcategoriaId'";
    $result = mysqli_query($conn, $query);

    if ($result) {
      // Mostrar mensaje de éxito si los datos se actualizaron correctamente
      echo '<script src="assets/js/iziToast.min.js"></script>';
      echo '<link rel="stylesheet" href="assets/css/iziToast.min.css">';
      echo '<script>
            window.onload = function() {
              iziToast.success({
                title: "Éxito",
                message: "subcategoría actualizada correctamente.",
                position: "topRight"
              });
            };
          </script>';
    } else {
      // Mostrar mensaje de error si no se pudieron actualizar los datos
      echo '<script src="assets/js/iziToast.min.js"></script>';
      echo '<link rel="stylesheet" href="assets/css/iziToast.min.css">';
      echo '<script>
            window.onload = function() {
              iziToast.error({
                title: "Error",
                message: "Hubo un error al actualizar la subcategoría.",
                position: "topRight"
              });
              setTimeout(function() {
                window.location.href = "ver_subcategorias.php";
              }, 2000); 
            };
          </script>';
    }
  }
}
// Obtener el ID de la subcategoría que deseas editar (reemplaza 'ID_DE_LA_subcategoRIA' con el ID correspondiente)
// Ya tenemos el valor de $subcategoriaId obtenido previamente en el código anterior

// Consultar los datos de la subcategoría desde la base de datos
$querysubcategoria = "SELECT nombre, categoria_id FROM subcategoria WHERE id='$subcategoriaId'";
$resultsubcategoria = mysqli_query($conn, $querysubcategoria);

if ($resultsubcategoria && mysqli_num_rows($resultsubcategoria) > 0) {
  $rowsubcategoria = mysqli_fetch_assoc($resultsubcategoria);
  $nombresubcategoria_actual = $rowsubcategoria['nombre'];
  $categoria_id_actual = $rowsubcategoria['categoria_id'];
} else {
  // Manejar el caso donde no se encontró la subcategoría con el ID especificado
  $nombresubcategoria_actual = "";
  $categoria_id_actual = "";
}

include 'vistas/template/navbar.php';
?>

<!-- Page title-->
<div class="border-bottom pt-5 mt-2 mb-5">
    <h1 class="mt-2 mt-md-4 mb-3 pt-5">EDITAR SUBCATEGORÍAS</h1>
    <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
        <p class="text-muted">Aquí puedes editar las subcategorías seleccionadas</p>
    </div>
</div>

<div class="card box-shadow-sm">
    <div class="card-header">
        <h5 style="margin-bottom: 0px;">EDITAR SUBCATEGORÍA</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label for="nombresubcategoria">Nombre de la subcategoría:</label>
                <input type="text" class="form-control" id="nombresubcategoria" name="nombresubcategoria" required value="<?php echo $nombresubcategoria_actual; ?>">
            </div>
            <div class="form-group">
                <label for="categoria">Escoge la categoría:</label>
                <select class="form-control" id="categoria" name="categoria" required>
                    <option value=""></option>
                    <?php
                    // Consultar las opciones de categorías disponibles y mostrarlas en el select
                    $querycategorias = "SELECT id, nombre FROM categoria";
                    $resultcategorias = mysqli_query($conn, $querycategorias);

                    if ($resultcategorias && mysqli_num_rows($resultcategorias) > 0) {
                        while ($rowcategoria = mysqli_fetch_assoc($resultcategorias)) {
                            // Marcar como seleccionada la categoría actual en el select
                            $selected = ($rowcategoria['id'] == $categoria_id_actual) ? "selected" : "";
                            echo '<option value="' . $rowcategoria['id'] . '" ' . $selected . '>' . $rowcategoria['nombre'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="ver_subcategorias.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>


</div>
</section>
</main>


    <!-- Back to top button-->
    <a class="btn-scroll-top" href="#top" data-scroll data-fixed-element>
        <span class="btn-scroll-top-tooltip text-muted font-size-sm mr-2"></span>
        <i class="btn-scroll-top-icon cxi-angle-up"></i>
    </a>

    <script src="assets/vendor/jquery/dist/jquery.slim.min.js"></script>
    <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/simplebar/dist/simplebar.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

    <!-- Main theme script-->
    <script src="assets/js/theme.min.js"></script>

  </body>
</html>