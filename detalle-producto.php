<?php
include 'conexion.php';
// Verificar si se ha proporcionado el ID del producto a ver
if (isset($_GET['id'])) {
  // Obtener el ID del producto desde la URL
  $productoId = $_GET['id'];

  // Consultar los datos de la tabla "tiendaconfig"
  $sql = "SELECT * FROM tiendaconfig";
  $result = $conn->query($sql);

  // Inicializar variables para almacenar los datos de la tabla
  $nombre_tienda = "";
  $facebook = "";
  $instagram = "";
  $whatsapp = "";
  $numero = "";
  $email = "";

  // Consulta SQL para obtener los datos del producto
  $sql = "SELECT * FROM producto WHERE id = $productoId";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Si se encontraron resultados, obtener la fila del producto
    $producto = $result->fetch_assoc();
    $nombre = $producto['nombre'];
    $id = $producto['id'];
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

    // Obtener el nombre de la subcategoría a partir del ID
    $sqlsubcategoria = "SELECT nombre FROM subcategoria WHERE id = $subcategoria_id";
    $resultsubcategoria = $conn->query($sqlsubcategoria);

    if ($resultsubcategoria->num_rows > 0) {
      $subcategoria = $resultsubcategoria->fetch_assoc();
      $nombresubcategoria = $subcategoria['nombre'];
    } else {
      $nombresubcategoria = "subcategoría desconocida";
    }

    // Obtener el nombre de la marca a partir del ID
    $sqlmarca = "SELECT nombre FROM marca WHERE id = ?";
    $stmt = $conn->prepare($sqlmarca);
    $stmt->bind_param("i", $marca_id);
    $stmt->execute();
    $resultmarca = $stmt->get_result();

    if ($resultmarca->num_rows > 0) {
      $marca = $resultmarca->fetch_assoc();
      $nombremarca = $marca['nombre'];
    } else {
      $nombremarca = "Marca desconocida";
    }

    // Obtener el nombre de la categoría a partir del ID
    $sqlcategoria = "SELECT nombre FROM categoria WHERE id = ?";
    $stmt = $conn->prepare($sqlcategoria);
    $stmt->bind_param("i", $categoria_id);
    $stmt->execute();
    $resultcategoria = $stmt->get_result();

    if ($resultcategoria->num_rows > 0) {
      $categoria = $resultcategoria->fetch_assoc();
      $nombrecategoria = $categoria['nombre'];
    } else {
      $nombrecategoria = "Categoría desconocida";
    }
  } else {
    echo "No se encontró el producto.";
  }
} else {
  // Si no se proporcionó el ID de la categoría, redirigir a la página de listar categorías
  header('Location: catalogo.php');
  exit;
}
include 'navbar.php';
?>
<!-- end navbar -->

<!-- Breadcrumb -->
<nav class="bg-secondary mb-3" aria-label="breadcrumb">
  <div class="container">
    <ol class="breadcrumb breadcrumb-alt mb-0">
      <li class="breadcrumb-item">
        <a href="index.php"><i class="cxi-home"></i></a>
      </li>
      <li class="breadcrumb-item">
        <a href="catalogo.php">Catálogo</a>
      </li>
      <li class="breadcrumb-item">
        <a href="catalogo.php#<?php echo strtolower($nombresubcategoria); ?>"><?php echo $nombresubcategoria; ?></a>
      </li>
      <li class="breadcrumb-item active" aria-current="page"><?php echo $nombre; ?></li>
    </ol>
  </div>
</nav>

<!-- Page title -->
<section class="container d-md-flex align-items-center justify-content-between py-3 py-md-4 mb-3">
  <h1 class="mb-2 mb-md-0"><?php echo $nombre; ?></h1>
  <span class="text-muted"><strong>Art. No.</strong><?php echo $id; ?></span>
</section>

<!-- Single product -->
<section class="container mb-2">
  <div class="row">
    <div class="col-md-6 mb-md-0 mb-4">

      <div class="product-container">

        <!-- Product gallery -->
        <div style="max-width: 570px;">
          <div id="gallery" data-carousel-options=''>

            <?php
            $nombreCarpeta = str_replace(' ', '_', $nombre);
            $nombreImagen1 = $nombreCarpeta . '_1.png';
            $rutaImagen1 = "./admin/assets/img/productos_img/$nombreCarpeta/$nombreImagen1";

            $imagenesAdicionales = array();
            for ($i = 2; $i <= 50; $i++) { // Cambia el rango según el número máximo de imágenes adicionales
              $nombreImagenAdicional = $nombreCarpeta . "_$i.png";
              $rutaImagenAdicional = "./admin/assets/img/productos_img/$nombreCarpeta/$nombreImagenAdicional";
              if (file_exists($rutaImagenAdicional)) {
                $imagenesAdicionales[$i] = $rutaImagenAdicional;
              }
            }
            ?>

            <a id="item1" class="gallery-item" data-sub-html='<h6 class="text-light">Gallery image caption #1</h6>'>
              <img src="<?php echo $rutaImagen1; ?>" alt="Carousel image" style="width: 570px; height: 570px;" draggable="false">
            </a>

            <?php foreach ($imagenesAdicionales as $indice => $rutaImagenAdicional) : ?>
              <a id="item<?php echo $indice; ?>" class="gallery-item" data-sub-html='<h6 class="text-light">Gallery image caption #<?php echo $indice; ?></h6>' style="display: none;">
                <img src="<?php echo $rutaImagenAdicional; ?>" alt="Carousel image" style="width: 570px; height: 570px;" draggable="false">
              </a>
            <?php endforeach; ?>

          </div>
        </div>

        <!-- Product gallery icon -->
        <div class="gallery-icon">
          <button type="button" data-nav="0" onclick="changeGallery(1)" class="thumbnail-button" data-tooltip="Imagen 1">
            <img src="<?php echo $rutaImagen1; ?>" alt="Thumbnail" draggable="false">
            <div class="thumbnail-message">Imagen 1</div>
          </button>
          <?php foreach ($imagenesAdicionales as $indice => $rutaImagenAdicional) : ?>
            <button type="button" data-nav="0" onclick="changeGallery(<?php echo $indice; ?>)" class="thumbnail-button" data-tooltip="Imagen <?php echo $indice; ?>">
              <img src="<?php echo $rutaImagenAdicional; ?>" alt="Thumbnail_<?php echo $indice; ?>" draggable="false">
              <div class="thumbnail-message">Imagen <?php echo $indice; ?></div>
            </button>
          <?php endforeach; ?>
        </div>
      </div>


    </div>
    <div class="col-md-6 pl-xl-5">
      <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <!-- Price -->
        <div class="d-flex align-items-center mb-sm-0 mb-4">
          <span class="h3 d-inline-block mb-0 text-danger precio-span"><?php echo 'S/.' . $precio; ?></span>
          <del class="d-inline-block ml-2 pl-1 font-size-lg text-muted">$31.00</del>
          <span class="ml-4 p-2 badge badge-danger font-size-base font-weight-bold">- 50%</span>
        </div>

        <!-- Rating -->
        <div class="text-sm-right">
          <div class="star-rating ml-auto">
            <i class="sr-star cxi-star-filled active"></i>
            <i class="sr-star cxi-star-filled active"></i>
            <i class="sr-star cxi-star-filled active"></i>
            <i class="sr-star cxi-star-filled active"></i>
            <i class="sr-star cxi-star"></i>
          </div>
          <span class="font-size-sm text-muted">12 reviews</span>
        </div>
      </div>

      <!-- Ordering form -->
      <form class="row">
        <div class="col-12">
          <div class="form-group">
            <label>Nombre del producto:</label>
            <?php echo $nombre; ?>
            <br>
            <label>Categoría:</label>
            <?php echo $nombrecategoria; ?>
            <br>
            <label>Marca:</label>
            <?php echo $nombremarca; ?>
            <br>
            <?php if (!empty($tallasLimpio)) : ?>
              <label>Tallas disponibles:</label>
              <?php echo $tallasLimpio; ?>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-lg-6 order-lg-4 order-4">
          <button type="button" class="btn btn-primary btn-block add-to-cart-btn" data-product-id="<?php echo $id; ?>" data-product-nombre="<?php echo $nombre; ?>" data-product-precio="<?php echo $precio; ?>" data-product-imagen="<?php echo $rutaImagen1; ?>" data-product-marca="<?php echo $nombremarca; ?>" data-product-categoria="<?php echo $nombrecategoria; ?>" data-product-stock="<?php echo $stock; ?>">
            <i class="cxi-cart align-middle mt-n1 mr-2"></i>
            Añadir al carrito
          </button>
        </div>
        <div class="col-lg-4 col-8 order-lg-5 order-2">
          <?php
          // Verificar si el usuario tiene la sesión iniciada
          if (isset($_SESSION["user_token"])) {
            $user_token = $_SESSION["user_token"];
          ?>
            <button class="btn btn-block btn-outline-primary px-lg-4">
              <i class="cxi-heart mr-2"></i>
              Favoritos
            </button>
          <?php
          } else {
            // Aquí puedes agregar el contenido que deseas mostrar cuando el usuario no tiene sesión iniciada
          }
          ?>
        </div>
      </form>

      <!-- Terms -->
      <!-- Accordion made of cards -->
      <div class="accordion-alt mb-4" id="productTerms">

        <!-- Tarjeta -->
        <div class="card border-bottom">
          <div class="card-header" id="delivery-card">
            <h6 class="accordion-heading">
              <a href="#delivery" class="collapsed" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="delivery">Envío
                <span class="accordion-indicator"></span>
              </a>
            </h6>
          </div>
          <div class="collapse" id="delivery" aria-labelledby="delivery-card" data-parent="#productTerms">
            <div class="card-body">
              <p class="font-size-sm">
                Envío estándar gratuito en pedidos <strong>superiores a S/. 35</strong> antes de impuestos, además de devoluciones gratuitas.
              </p>

              <!-- Tabla de envío -->
              <div class="table-responsive px-md-3">
                <table class="table mb-0" style="min-width: 450px;">
                  <thead class="font-size-xs text-uppercase text-muted">
                    <tr>
                      <th class="border-0 font-weight-normal">Tipo</th>
                      <th class="border-0 font-weight-normal">Duración</th>
                      <th class="border-0 font-weight-normal">Costo</th>
                    </tr>
                  </thead>
                  <tbody class="font-size-sm">
                    <tr>
                      <th class="font-weight-normal">Envío estándar</th>
                      <td>1-4 días hábiles</td>
                      <td>S/. 4.50</td>
                    </tr>
                    <tr>
                      <th class="font-weight-normal">Envío express</th>
                      <td>1 día hábil</td>
                      <td>S/. 10.00</td>
                    </tr>
                    <tr>
                      <th class="font-weight-normal">Recoger en tienda</th>
                      <td>1-3 días hábiles</td>
                      <td>Gratis</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Tarjeta -->
        <div class="card border-bottom">
          <div class="card-header" id="return-card">
            <h6 class="accordion-heading">
              <a class="collapsed" href="#return" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="return">Devolución
                <span class="accordion-indicator"></span>
              </a>
            </h6>
          </div>
          <div class="collapse" id="return" aria-labelledby="return-card" data-parent="#productTerms">
            <p class="font-size-sm">
              Tienes <strong>60 días</strong> para devolver el artículo(s) utilizando cualquiera de los siguientes métodos:
            </p>
            <ul class="mb-4 pl-3">
              <li>Devolución gratuita en tienda</li>
              <li>Devolución gratuita a través del Servicio de Entrega de USPS</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Share -->
      <div class="mb-5 text-nowrap">
        <?php
        // Obtener la URL actual
        $url_actual = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        // Suponiendo que $nombre_tienda contiene el nombre de la tienda
        $mensaje_facebook = "¡Echa un vistazo a este increíble producto en $nombre_tienda!";
        $url_producto = $url_actual;
        ?>

        <h6 class="d-inline-block align-middle mr-2 mb-0">Compartir en:</h6>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url_producto); ?>&quote=<?php echo urlencode($mensaje_facebook); ?>" class="social-btn sb-solid align-middle mr-2" target="_blank" title="Facebook" onclick="window.open(this.href, 'Compartir en Facebook', 'width=600,height=400'); return false;">
          <i class="cxi-facebook"></i>
        </a>
        <!--<a href="#" class="social-btn sb-solid align-middle mr-2" data-toggle="tooltip" title="Twitter">
                <i class="cxi-twitter"></i>
              </a>
              <a href="#" class="social-btn sb-solid align-middle" data-toggle="tooltip" title="Pinterest">
                <i class="cxi-pinterest"></i>
              </a>-->
      </div>

      <!-- Payment -->
      <div class="pb-3 text-nowrap" data-simplebar data-simplebar-auto-hide="false">
        <a class="d-inline-block mb-3 mr-xl-4 mr-3">
          <img src="assets/img/ecommerce/cards/visa.jpg" alt="Visa" class="border rounded" draggable="false">
        </a>
        <a class="d-inline-block mb-3 mr-xl-4 mr-3">
          <img src="assets/img/ecommerce/cards/master-card.jpg" alt="Mastercard" class="border rounded" draggable="false">
        </a>
        <a class="d-inline-block mb-3">
          <img src="assets/img/ecommerce/cards/pay-pal.jpg" alt="PayPal" class="border rounded" draggable="false">
        </a>
      </div>
    </div>
  </div>
</section>

<div class="py-5" style="background-color: #e5e8ed;">
  <section class="container my-lg-2 py-2 py-md-4">
    <div class="row">
      <div class="col-lg-7 col-md-8 mb-md-0 mb-4">
        <h3 class="h5 mb-3">Detalles</h3>
        <p><?php echo $descripcion; ?></p>
        <ul class="pl-3">
          <li>Marca: <?php echo $nombremarca; ?></li>
          <li>Diseño único y moderno que resalta tu estilo personal.</li>
          <li>Confeccionado con materiales de alta calidad para una durabilidad excepcional.</li>
          <li>Ofrece un equilibrio perfecto entre estilo y funcionalidad para tu día a día.</li>
          <li>Detalles cuidadosamente elaborados para resaltar tu individualidad.</li>
        </ul>
        <hr class="my-4">
        <h3 class="h5 mb-3">Care</h3>
        <ul class="pl-0">
          <li class="media">
            <img src="assets/img/ecommerce/care/hand-wash.svg" alt="Icon" class="d-block mr-3" draggable="false">
            <div class="media-body pl-1">
              Lavar a mano solamente (30°)
            </div>
          </li>
          <li class="media">
            <img src="assets/img/ecommerce/care/no-ironing.svg" alt="Icon" class="d-block mr-3" draggable="false">
            <div class="media-body pl-1">
              Sin planchar
            </div>
          </li>
          <li class="media">
            <img src="assets/img/ecommerce/care/no-bleach.svg" alt="Icon" class="d-block mr-3" draggable="false">
            <div class="media-body pl-1">
              No use lejía en este producto
            </div>
          </li>
          <li class="media">
            <img src="assets/img/ecommerce/care/no-tumble-dry.svg" alt="Icon" class="d-block mr-3" draggable="false">
            <div class="media-body pl-1">
              No secar en secadora
            </div>
          </li>
        </ul>
      </div>
      <div class="col-md-4 offset-lg-1">

        <!-- Product card carousel -->
        <div class="card card-product">
          <div class="card-product-img">
            <div class="cs-carousel cs-controls-onhover">
              <a class="cs-carousel-inner">
                <div><img class="card-img-top" src="<?php echo $rutaImagen1; ?>" alt="Product image" draggable="false"></div>

              </a>
            </div>
            <div class="card-product-widgets-top">
              <div class="ml-auto star-rating">
                <i class="sr-star cxi-star-filled active"></i>
                <i class="sr-star cxi-star-filled active"></i>
                <i class="sr-star cxi-star-filled active"></i>
                <i class="sr-star cxi-star-filled active"></i>
                <i class="sr-star cxi-star"></i>
              </div>
            </div>
            <div class="card-product-widgets-bottom">
              <a class="btn-wishlist ml-auto" href="#" data-toggle="tooltip" data-placement="left" title="Añadir a favoritos"></a>
            </div>
          </div>
          <div class="card-body pb-2" style="background-color: white;">
            <h3 class="card-product-title text-truncate mb-2">
              <a href="#" class="nav-link"><?php echo $nombre; ?></a>
            </h3>
            <div class="d-flex align-items-center">
              <span class="h5 d-inline-block mb-0 precio-span text-danger"><?php echo 'S/.' . $precio; ?></span>
              <del class="d-inline-block ml-2 pl-1 text-muted">$31.00</del>
            </div>
            <div class="d-flex align-items-center mb-2 pb-1">
            </div>
            <button type="button" class="btn btn-primary btn-block add-to-cart-btn" data-product-id="<?php echo $id; ?>" data-product-nombre="<?php echo $nombre; ?>" data-product-precio="<?php echo $precio; ?>" data-product-imagen="<?php echo $rutaImagen1; ?>" data-product-marca="<?php echo $nombremarca; ?>" data-product-categoria="<?php echo $nombrecategoria; ?>" data-product-stock="<?php echo $stock; ?>">
              <i class="cxi-cart align-middle mt-n1 mr-2"></i>
              Añadir al carrito
            </button>
          </div>
          <div class="card-footer">

          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<section class="container py-md-6 py-5 mb-2">
  <div class="row">
    <div class="col-lg-7 col-md-8 mb-md-0 mb-5">
      <div class="row mb-sm-5 mb-4 pb-2 pb-md-4">

        <!-- Rating -->
        <div class="col-sm-5 mb-sm-0 mb-4">
          <h3 class="h2 mb-3 pb-1">12 reviews</h3>
          <div class="star-rating mb-3 pb-1">
            <i class="sr-star cxi-star-filled active"></i>
            <i class="sr-star cxi-star-filled active"></i>
            <i class="sr-star cxi-star-filled active"></i>
            <i class="sr-star cxi-star-filled active"></i>
            <i class="sr-star cxi-star"></i>
          </div>
          <span class="font-size-sm text-muted">
            9 out of 12 (75%) <br>
            Customers recommended this product
          </span>
        </div>

        <!-- Reviews -->
        <div class="col-sm-7">

          <!-- 5 stars -->
          <div class="d-flex align-items-center mb-2">
            <div class="text-nowrap text-muted mr-3">
              <span class="d-inline-block align-middle">5</span>
              <i class="cxi-star font-size-sm align-middle ml-1"></i>
            </div>
            <div class="w-100">
              <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 80%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            <span class="text-muted ml-3">7</span>
          </div>

          <!-- 4 stars -->
          <div class="d-flex align-items-center mb-2">
            <div class="text-nowrap text-muted mr-3">
              <span class="d-inline-block align-middle">4</span>
              <i class="cxi-star font-size-sm align-middle ml-1"></i>
            </div>
            <div class="w-100">
              <div class="progress" style="height: 4px;">
                <div class="progress-bar" role="progressbar" style="width: 50%; background-color: #a7e453;" aria-valuenow="27" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            <span class="text-muted ml-3">2</span>
          </div>

          <!-- 3 stars -->
          <div class="d-flex align-items-center mb-2">
            <div class="text-nowrap text-muted mr-3">
              <span class="d-inline-block align-middle">3</span>
              <i class="cxi-star font-size-sm align-middle ml-1"></i>
            </div>
            <div class="w-100">
              <div class="progress" style="height: 4px;">
                <div class="progress-bar" role="progressbar" style="width: 30%; background-color: #ffda75;" aria-valuenow="17" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            <span class="text-muted ml-3">1</span>
          </div>

          <!-- 2 stars -->
          <div class="d-flex align-items-center mb-2">
            <div class="text-nowrap text-muted mr-3">
              <span class="d-inline-block align-middle">2</span>
              <i class="cxi-star font-size-sm align-middle ml-1"></i>
            </div>
            <div class="w-100">
              <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 30%;" aria-valuenow="9" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            <span class="text-muted ml-3">1</span>
          </div>

          <!-- 1 star -->
          <div class="d-flex align-items-center">
            <div class="text-nowrap text-muted mr-3">
              <span class="d-inline-block align-middle">1</span>
              <i class="cxi-star font-size-sm align-middle ml-1"></i>
            </div>
            <div class="w-100">
              <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 30%;" aria-valuenow="4" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            <span class="text-muted ml-3">1</span>
          </div>
        </div>
      </div>

      <!-- Button + sorting -->
      <div class="d-flex align-items-center justify-content-between mb-4 pb-2 pb-sm-4">
        <a href="#modal-review" data-toggle="modal" class="btn btn-primary">Leave a review</a>
        <div class="form-inline">
          <label for="sort-orders" class="d-none d-sm-block font-weight-bold mr-2 pr-1">Sort by</label>
          <select id="sort-orders" class="custom-select">
            <option>Newest</option>
            <option>Oldest</option>
            <option>High rating</option>
            <option>Low rating</option>
          </select>
        </div>
      </div>

      <!-- Review -->
      <div class="mb-4 pb-4 border-bottom">
        <div class="row">
          <div class="col-md-3 col-sm-4 mb-sm-0 mb-3">
            <h3 class="mb-2 font-size-lg">Devon Lane</h3>
            <span class="d-block mb-3 font-size-sm text-muted">3 days ago</span>
            <div class="mt-n1 star-rating">
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
            </div>
          </div>
          <div class="col">
            <p class="mb-3">Vel velit pulvinar montes, sit rhoncus odio molestie. Venenatis nunc fames ut proin adipiscing sit. Etiam egestas elit varius a, vestibulum bibendum nibh sociis in. Ut facilisis sit eget cum fringilla. Dapibus mauris viverra est sed risus quam lacinia.</p>
            <div class="d-flex justify-content-end">
              <a class="nav-link mr-3 p-0 font-size-sm" href="#">
                <i class="cxi-like mr-2 font-size-base align-middle mt-n1 text-success"></i>
                2
              </a>
              <a class="nav-link p-0 font-size-sm" href="#">
                <i class="cxi-dislike mr-2 font-size-base align-middle mt-n1 text-danger"></i>
                0
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Review -->
      <div class="mb-4 pb-4 border-bottom">
        <div class="row">
          <div class="col-md-3 col-sm-4 mb-sm-0 mb-3">
            <h3 class="mb-2 font-size-lg">Annette Black</h3>
            <span class="d-block mb-3 font-size-sm text-muted">November 29, 2020</span>
            <div class="mt-n1 star-rating">
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star"></i>
            </div>
          </div>
          <div class="col">
            <p class="mb-3">Amet ut augue dignissim lorem. Diam volutpat porttitor vel, pulvinar semper. Faucibus vel accumsan mi diam magna. Nunc diam lorem semper rhoncus in ut. Quis risus viverra bibendum eu.</p>
            <div class="d-flex justify-content-end">
              <a class="nav-link mr-3 p-0 font-size-sm" href="#">
                <i class="cxi-like mr-2 font-size-base align-middle mt-n1 text-success"></i>
                5
              </a>
              <a class="nav-link p-0 font-size-sm" href="#">
                <i class="cxi-dislike mr-2 font-size-base align-middle mt-n1 text-danger"></i>
                1
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Review -->
      <div class="mb-4 pb-4 border-bottom">
        <div class="row">
          <div class="col-md-3 col-sm-4 mb-sm-0 mb-3">
            <h3 class="mb-2 font-size-lg">Albert Flores</h3>
            <span class="d-block mb-3 font-size-sm text-muted">November 5, 2020</span>
            <div class="mt-n1 star-rating">
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star"></i>
            </div>
          </div>
          <div class="col">
            <p class="mb-3">Et, enim pellentesque ut malesuada interdum. Id odio proin molestie ac tellus ornare lectus amet semper. Bibendum non nulla leo pharetra, mi ultrices proin rhoncus diam.</p>
            <div class="d-flex justify-content-end">
              <a class="nav-link mr-3 p-0 font-size-sm" href="#">
                <i class="cxi-like mr-2 font-size-base align-middle mt-n1 text-success"></i>
                0
              </a>
              <a class="nav-link p-0 font-size-sm" href="#">
                <i class="cxi-dislike mr-2 font-size-base align-middle mt-n1 text-danger"></i>
                0
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Review -->
      <div class="mb-4 pb-4 border-bottom">
        <div class="row">
          <div class="col-md-3 col-sm-4 mb-sm-0 mb-3">
            <h3 class="mb-2 font-size-lg">Marvin McKinney</h3>
            <span class="d-block mb-3 font-size-sm text-muted">October 13, 2020</span>
            <div class="mt-n1 star-rating">
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
            </div>
          </div>
          <div class="col">
            <p class="mb-3">Eu duis ac, faucibus et egestas morbi elementum urna in. Feugiat natoque vestibulum, augue nisi. Vulputate vitae felis quis mauris morbi massa ut. Dolor in amet volutpat facilisi luctus duis mauris.</p>
            <div class="d-flex justify-content-end">
              <a class="nav-link mr-3 p-0 font-size-sm" href="#">
                <i class="cxi-like mr-2 font-size-base align-middle mt-n1 text-success"></i>
                9
              </a>
              <a class="nav-link p-0 font-size-sm" href="#">
                <i class="cxi-dislike mr-2 font-size-base align-middle mt-n1 text-danger"></i>
                2
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination: With icons -->
      <nav class="mt-4 pt-4" aria-label="Reviews pagination">
        <ul class="pagination justify-content-center mb-0">
          <li class="page-item d-sm-none">
            <span class="page-link page-link-static">1 / 4</span>
          </li>
          <li class="page-item active d-none d-sm-block" aria-current="page">
            <span class="page-link">1
              <span class="sr-only">(current)</span>
            </span>
          </li>
          <li class="page-item d-none d-sm-block">
            <a class="page-link" href="#">2</a>
          </li>
          <li class="page-item d-none d-sm-block">
            <a class="page-link" href="#">3</a>
          </li>
          <li class="page-item d-none d-sm-block">
            <a class="page-link" href="#">4</a>
          </li>
          <li class="page-item">
            <a class="page-link" href="#">
              <i class="cxi-arrow-right"></i>
            </a>
          </li>
        </ul>
      </nav>
    </div>
    <div class="col-md-4 offset-lg-1">

      <!-- Product card carousel -->
      <div class="card card-product">
        <div class="card-product-img">
          <div class="cs-carousel cs-controls-onhover">
            <a class="cs-carousel-inner">
              <div><img class="card-img-top" src="<?php echo $rutaImagen1; ?>" alt="Product image" draggable="false"></div>

            </a>
          </div>
          <div class="card-product-widgets-top">
            <div class="ml-auto star-rating">
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star-filled active"></i>
              <i class="sr-star cxi-star"></i>
            </div>
          </div>
          <div class="card-product-widgets-bottom">
            <a class="btn-wishlist ml-auto" href="#" data-toggle="tooltip" data-placement="left" title="Añadir a favoritos"></a>
          </div>
        </div>
        <div class="card-body pb-2" style="background-color: white;">
          <h3 class="card-product-title text-truncate mb-2">
            <a href="#" class="nav-link"><?php echo $nombre; ?></a>
          </h3>
          <div class="d-flex align-items-center">
            <span class="h5 d-inline-block mb-0 precio-span text-danger"><?php echo 'S/.' . $precio; ?></span>
            <del class="d-inline-block ml-2 pl-1 text-muted precio-span"><?php echo 'S/.' . $precio; ?></del>
          </div>
          <div class="d-flex align-items-center mb-2 pb-1">
          </div>
          <button type="button" class="btn btn-primary btn-block add-to-cart-btn" data-product-id="<?php echo $id; ?>" data-product-nombre="<?php echo $nombre; ?>" data-product-precio="<?php echo $precio; ?>" data-product-imagen="<?php echo $rutaImagen1; ?>" data-product-marca="<?php echo $nombremarca; ?>" data-product-categoria="<?php echo $nombrecategoria; ?>" data-product-stock="<?php echo $stock; ?>">
            <i class="cxi-cart align-middle mt-n1 mr-2"></i>
            Añadir al carrito
          </button>
        </div>
        <div class="card-footer">

        </div>
      </div>
    </div>
  </div>
</section>

<!-- Productos relacionados segun la categoria -->
<section class="py-5" style="background-color: #e5e8ed;">
  <div class="container my-lg-2 py-2 py-md-4">
    <div class="d-flex align-items-center justify-content-between pb-sm-1 mb-5">
      <h2 class="h1 mb-0">Relacionados:</h2>
      <div class="tns-custom-controls cs-controls-inverse" id="custom-controls-trending" tabindex="0">
        <button type="button" data-controls="prev" tabindex="-1">
          <i class="cxi-arrow-left"></i>
        </button>
        <button type="button" data-controls="next" tabindex="-1">
          <i class="cxi-arrow-right"></i>
        </button>
      </div>
    </div>
    <div class="cs-carousel pb-2">
      <div class="cs-carousel-inner" data-carousel-options='{
          "nav": false,
          "controlsContainer": "#custom-controls-trending",
          "responsive": {
            "0": {
              "items": 1,
              "gutter": 20
            },
            "480": {
              "items": 2,
              "gutter": 24
            },
            "700": {
              "items": 3,
              "gutter": 24
            },
            "1100": {
              "items": 4,
              "gutter": 30
            }
          }
        }'>

        <?php
        // Consulta SQL para obtener la categoría del producto actual
        $sqlcategoria = "SELECT categoria_id FROM producto WHERE id = ?";
        $stmtCategoria = $conn->prepare($sqlcategoria);
        $stmtCategoria->bind_param("i", $productoId);
        $stmtCategoria->execute();
        $resultCategoria = $stmtCategoria->get_result();

        if ($resultCategoria->num_rows > 0) {
          $rowCategoria = $resultCategoria->fetch_assoc();
          $categoria_id = $rowCategoria['categoria_id'];

          // Consulta SQL para obtener productos relacionados de la misma categoría
          $sqlRelacionados = "SELECT * FROM producto WHERE categoria_id = ? AND id <> ? LIMIT 8";
          $stmtRelacionados = $conn->prepare($sqlRelacionados);
          $stmtRelacionados->bind_param("ii", $categoria_id, $productoId);
          $stmtRelacionados->execute();
          $resultRelacionados = $stmtRelacionados->get_result();

          if ($resultRelacionados->num_rows > 0) {
            while ($rowRelacionados = $resultRelacionados->fetch_assoc()) {
              $nombreCarpetaRelacionado = str_replace(' ', '_', $rowRelacionados['nombre']);
              $nombreImagenRelacionado = $nombreCarpetaRelacionado . '_1.png';
              $rutaImagenRelacionado = "./admin/assets/img/productos_img/$nombreCarpetaRelacionado/$nombreImagenRelacionado";
        ?>
              <!-- Item -->
              <div>
                <div class="card card-product mx-auto">
                  <div class="card-product-img">
                    <a href="detalle-producto.php?id=<?php echo $rowRelacionados['id']; ?>" class="card-img-top">
                      <img src="<?php echo $rutaImagenRelacionado; ?>" alt="Product image" draggable="false">
                    </a>
                    <div class="card-product-widgets-top">
                      <div class="star-rating ml-auto">
                        <i class="sr-star cxi-star-filled active"></i>
                        <i class="sr-star cxi-star-filled active"></i>
                        <i class="sr-star cxi-star-filled active"></i>
                        <i class="sr-star cxi-star-filled active"></i>
                        <i class="sr-star cxi-star"></i>
                      </div>
                    </div>
                    <div class="card-product-widgets-bottom">
                      <a href="#" class="btn-wishlist ml-auto" data-toggle="tooltip" data-placement="left" title="Añadir a favoritos"></a>
                    </div>
                  </div>
                  <div class="card-body bg-0 pb-2">
                    <h3 class="card-product-title text-truncate mb-2">
                      <a href="detalle-producto.php?id=<?php echo $rowRelacionados['id']; ?>" class="nav-link"><?php echo $rowRelacionados['nombre']; ?></a>
                    </h3>
                    <div class="d-flex align-items-center">
                      <span class="h5 d-inline-block mb-0 precio-span">S/. <?php echo $rowRelacionados['precio']; ?></span>
                    </div>
                  </div>
                </div>
              </div>
        <?php
            }
          }
        }
        ?>

      </div>
    </div>
    <div class="text-center pt-4 pt-md-5">
      <a href="catalogo.php" class="btn btn-outline-primary btn-lg">Ver Catálogo</a>
    </div>
  </div>
</section>

</main>


<!-- Footer -->
<?php include 'footer.php'; ?>