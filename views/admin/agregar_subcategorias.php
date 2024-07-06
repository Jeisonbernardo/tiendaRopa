<?php include '../../config/conexion.php'; ?>
<?php include '../template/navbar_admin.php'; ?>


<?php

  if (!isset($_SESSION['token'])) {
    header('Location: loginconf.php');
    exit;
  }

 // Verificar si el formulario se ha enviado
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos ingresados por el usuario
    $nombresubcategoria = $_POST['nombre'];
    $categoriaId = $_POST['categoria'];

    $query = "INSERT INTO subcategoria (nombre, categoria_id) VALUES ('$nombresubcategoria', '$categoriaId')";
    $result = mysqli_query($conn, $query);

    if ($result) {
      echo '<script>
            $(document).ready(function() {
              $("#modalExito").modal("show");
            });
          </script>';
    } else {
      echo '<script>
            $(document).ready(function() {
              $("#modalError").modal("show");
            });
          </script>';
    }
  }

?>
<div class="container">
  <div class="border-bottom  mb-5">
            <h1 class="mt-2 mt-md-4 mb-3 pt-5">AÑADIR SUBCATEGORÍAS</h1>
            <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
              <p class="text-muted">Aquí puedes añadir las subcategorías</p>
              <p class="font-size-sm font-weight-medium pl-md-4"><a class="text-nowrap" href="./subcategoria.php" rel="noopener">Ver subcategorias añadidas<i class="cxi-angle-right font-size-base align-middle ml-1"></i></a></p>
            </div>
  </div>

  <div class="card box-shadow-sm">
            <div class="card-header">
              <h5 style="margin-bottom: 0px;">AÑADIR SUBCATEGORÍA</h5>
            </div>
            <div class="card-body">
              <form method="POST">
                <div class="form-group">
                  <b>
                  <label for="categoria">Escoge la categoría:</label><br><br>
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
                  <b>
                </div>
                <div class="form-group" id="subcategoriaForm" style="display: none;">
                  <label for="nombre">Nombre de la subcategoría:</label>
                  <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div> <br>
                <button type="submit" class="btn btn-primary" id="agregarsubcategoria" style="display: none;">Agregar subcategoría</button>
              </form>
            </div>
  </div>

  <!-- Modal de Éxito -->
  <div class="modal fade" id="modalExito" tabindex="-1" role="dialog" aria-labelledby="modalExitoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalExitoLabel">Éxito</h5>
        </div>
        <div class="modal-body">
          Subcategoría agregada correctamente.
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Error -->
  <div class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-labelledby="modalErrorLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalErrorLabel">Error</h5>
          
        </div>
        <div class="modal-body">
          Hubo un error al agregar la subcategoría.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

</div> <br><br><br>

<script src="../../admin/assets/js/agregar_subcategorias.js"></script>
<?php include '../template/footer_admin.php'; ?>