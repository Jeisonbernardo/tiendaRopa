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

  // Verificar si el formulario se ha enviado
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos ingresados por el usuario
    $nombresubcategoria = $_POST['nombre'];
    $categoriaId = $_POST['categoria'];

    // Insertar los datos en la tabla "subcategoria" con el id de la categoría seleccionada
    $query = "INSERT INTO subcategoria (nombre, categoria_id) VALUES ('$nombresubcategoria', '$categoriaId')";
    $result = mysqli_query($conn, $query);

    if ($result) {
      // Mostrar mensaje de éxito si los datos se agregaron correctamente
      echo '<script src="assets/js/iziToast.min.js"></script>';
      echo '<link rel="stylesheet" href="assets/css/iziToast.min.css">';
      echo '<script>
              window.onload = function() {
                iziToast.success({
                  title: "Éxito",
                  message: "subcategoría agregada correctamente.",
                  position: "topRight"
                });
              };
            </script>';
    } else {
      // Mostrar mensaje de error si no se pudieron agregar los datos
      echo '<script src="assets/js/iziToast.min.js"></script>';
      echo '<link rel="stylesheet" href="assets/css/iziToast.min.css">';
      echo '<script>
              window.onload = function() {
                iziToast.warning({
                  title: "Error",
                  message: "Hubo un error al agregar la subcategoría.",
                  position: "topRight"
                });
              };
            </script>';
    }
  }

  include 'Vistas/template/navbar.php';
?>

<!-- Page title-->
<div class="border-bottom pt-5 mt-2 mb-5">
          <h1 class="mt-2 mt-md-4 mb-3 pt-5">AÑADIR SUBCATEGORÍAS</h1>
          <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
            <p class="text-muted">Aquí puedes añadir las subcategorías</p>
            <p class="font-size-sm font-weight-medium pl-md-4"><a class="text-nowrap" href="./ver_subcategorias.php" rel="noopener">Ver subcategorias añadidas<i class="cxi-angle-right font-size-base align-middle ml-1"></i></a></p>
          </div>
</div>

<div class="card box-shadow-sm">
          <div class="card-header">
            <h5 style="margin-bottom: 0px;">AÑADIR SUBCATEGORÍA</h5>
          </div>
          <div class="card-body">
            <form method="POST">
              <div class="form-group">
                <label for="categoria">Escoge la categoría:</label>
                <select class="form-control" id="categoria" name="categoria" required>
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
              <div class="form-group" id="subcategoriaForm" style="display: none;">
                <label for="nombre">Nombre de la subcategoría:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
              </div>
              <button type="submit" class="btn btn-primary" id="agregarsubcategoria" style="display: none;">Agregar subcategoría</button>
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

    <script>
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
    </script>

    <script src="assets/vendor/jquery/dist/jquery.slim.min.js"></script>
    <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/simplebar/dist/simplebar.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

    <!-- Main theme script-->
    <script src="assets/js/theme.min.js"></script>

  </body>
</html>