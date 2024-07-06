<?php include '../../config/conexion.php'; ?>
<?php include '../template/navbar_admin.php'; ?>

<?php
// Definir la ruta de la imagen por defecto
$rutaImagenDefecto = '../../admin/assets/img/productos_img/default.png';


// Verificar si NO se ha iniciado sesión y NO hay un token almacenado
if (!isset($_SESSION['token'])) {
  header('Location: loginconf.php');
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
    $directorioProducto = '../../admin/assets/img/productos_img/' . $nombreCarpeta;
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

?>

<div class="container">
<div class="border-bottom pt-1 mt-2 mb-5">
  <h1 class="mt-2 mt-md-4 mb-3 pt-5">AÑADIR PRODUCTOS</h1>
  <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
    <p class="text-muted">Aquí puedes añadir los productos</p>
    <p class="font-size-sm font-weight-medium pl-md-4"><a class="text-nowrap" href="./productos.php" rel="noopener">Ver productos añadidos<i class="cxi-angle-right font-size-base align-middle ml-1"></i></a></p>
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
              <br><label for="stock">Stock</label>
              <input type="number" class="form-control" id="stock" name="stock" placeholder="Cantidad inicial" required min="0">
            </div>
            <div class="col-lg-4 form-group">
              <br><label for="precio">Precio (S/.)</label>
              <input type="number" class="form-control" id="precio" name="precio" placeholder="Precio" required min="0" step="any">
            </div>

            <div class="col-lg-4 form-group">
              <br><label for="marca_id">Marca</label>
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
              <br><label for="categoria_id">Categoría</label>
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
              <br><label for="talla">Talla</label>
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
              <br><label for="descripcion">Descripción</label>
              <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Descripción corta" required rows="5"></textarea>
            </div>
          </div>
          <!-- Fin del formulario principal -->
        </div>
        <div class="col-lg-4">
          <!-- Formulario de subida de imagen -->
          <div class="form-group">
            <label for="imagen-producto">Subir imagen del producto:</label>
            <br><br><input type="file" class="form-control-file" id="imagenes" name="imagenes[]" multiple accept=".png, .jpg, .jpeg, .webp">
          </div>
          <div class="form-group">
            <div id="imagen-producto-preview" class="d-flex flex-wrap">
              
            </div>
          </div>
          <!-- Fin del formulario de subida de imagen -->
        </div>
      </div><br>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Crear producto</button>
      </div>
    </form>
  </div>

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
</div> <br><br><br>


<script src="../../admin/assets/js/agregar_productos.js"></script>
<?php include '../template/footer_admin.php'; ?>