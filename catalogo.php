      <!-- HTML + navbar -->
      <?php include 'navbar.php';

      // Incluir el archivo de conexión
      include 'conexion.php';

      // Hacer la consulta SQL para obtener las marcas
      $query_marcas = "SELECT id, nombre FROM marca";
      $result_marcas = mysqli_query($conn, $query_marcas);

      // Crear un array para almacenar las marcas
      $marcas = array();

      while ($row_marcas = mysqli_fetch_assoc($result_marcas)) {
        $marca_id = $row_marcas['id'];
        $marca_nombre = $row_marcas['nombre'];

        // Agregar la marca al array de marcas
        $marcas[$marca_id] = array(
          'nombre' => $marca_nombre
        );
      }


      // Hacer la consulta SQL para obtener los productos con información de subcategoría
      $query = "SELECT p.id AS producto_id, p.nombre, p.precio, p.stock, c.nombre AS categoria, m.nombre AS marca, p.created_at, sc.nombre AS subcategoria
      FROM producto p
      LEFT JOIN categoria c ON p.categoria_id = c.id
      LEFT JOIN marca m ON p.marca_id = m.id
      LEFT JOIN subcategoria sc ON p.subcategoria_id = sc.id";
      $result = mysqli_query($conn, $query);

      // Inicializar un array para almacenar los datos de los productos
      $productos = array();

      // Recorrer los resultados y guardar los datos de los productos en el array
      while ($row = mysqli_fetch_assoc($result)) {
        $productos[] = $row;
      }


      // Hacer la consulta SQL para obtener las categorías
      $query = "SELECT c.id AS categoria_id, c.nombre AS categoria, s.nombre AS subcategoria
      FROM categoria c
      LEFT JOIN subcategoria s ON c.id = s.categoria_id";
      $result = mysqli_query($conn, $query);

      // Crear un array para almacenar las categorías y sus subcategorías asociadas
      $categorias = array();

      while ($row = mysqli_fetch_assoc($result)) {
        $categoria_id = $row['categoria_id'];
        $categoria = $row['categoria'];
        $subcategoria = $row['subcategoria'];

        // Si la categoría aún no existe en el array, agregarla
        if (!isset($categorias[$categoria_id])) {
          $categorias[$categoria_id] = array(
            'nombre' => $categoria,
            'subcategorias' => array()
          );
        }

        // Agregar la subcategoría a la categoría correspondiente
        if ($subcategoria) {
          $categorias[$categoria_id]['subcategorias'][] = $subcategoria;
        }
      }

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
              <a href="catalogo.php">Tienda</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Catálogo</li>
          </ol>
        </div>
      </nav>


      <!-- Page container -->
      <section class="container pt-3 pb-5 pb-md-6 mb-2 mb-lg-0">


        <!-- Toolbar + Pagination -->
        <div class="row mb-4 pb-2">
          <div class="col-md-3 pr-lg-4 mb-3 mb-md-0">

            <!-- Show / hide filters on Desktop -->
            <div class="d-none d-lg-block">
              <button type="button" class="btn btn-primary w-100 d-none d-lg-block" data-filters-hide="#filtersSidebar">
                <i class="cxi-filter-1 me-1"></i>
                Ocultar Filtros
              </button>
              <button type="button" class="btn btn-primary w-100 mt-0 d-lg-none" data-filters-show="#filtersSidebar">
                <i class="cxi-filter-2 me-1"></i>
                Mostrar Filtros
              </button>
            </div>

            <!-- Show / hide filters (off-canvas) on Mobile -->
            <button type="button" class="btn btn-primary btn-block mt-0 d-lg-none" data-toggle="offcanvas" data-target="filtersOffcanvas">
              <i class="cxi-filter-2 mr-1"></i>
              Mostrar Filtros
            </button>
          </div>

          <!-- Filtros y paginación -->
          <div class="col-md-9">
            <div class="d-flex align-items-center">
              <div class="form-inline flex-nowrap mr-3 mr-xl-5">
                <label for="sorting-top" class="font-weight-bold text-nowrap mr-2 pr-1 d-none d-lg-block">Ordenar por</label>
                <select id="sorting-top" class="custom-select">
                  <option>Por defecto</option>
                  <option>Nuevos</option>
                  <option>Precio bajo - alto</option>
                  <option>Precio alto - bajo</option>
                  <option>Popularidad</option>
                  <option>Ordenar A - Z</option>
                  <option>Ordenar Z - A</option>
                </select>
              </div>
              <div class="form-inline flex-nowrap d-none d-sm-flex mr-3 mr-xl-5">
                <label for="pager-top" class="font-weight-bold text-nowrap mr-2 pr-1 d-none d-lg-block">Mostrar</label>
                <select id="pager-top" class="custom-select">
                  <option>Todos</option>
                  <option>12</option>
                  <option>24</option>
                  <option>48</option>
                  <option>72</option>
                  <option>96</option>
                </select>
                <span class="font-size-sm text-muted text-nowrap ml-2 d-none d-lg-block">Productos por página</span>
              </div>
            </div>
          </div>
        </div>

        <div class="row flex-lg-nowrap">


          <!-- Filters (sidebar) -->
          <div id="filtersSidebar" class="col-lg-3 pr-lg-4">
            <div id="filtersOffcanvas" class="cs-offcanvas cs-offcanvas-collapse">
              <div class="cs-offcanvas-cap align-items-center border-bottom mb-3">
                <h2 class="h5 mb-0">Filtros</h2>
                <button class="close mr-n1" type="button" data-dismiss="offcanvas" aria-label="Close">
                  <span class="h2 font-weight-normal mt-n1 mb-0" aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="cs-offcanvas-body accordion-alt pb-4">

                <!-- Busqueda -->

                <div class="input-group-overlay mb-3">
                  <input type="text" class="cs-filter-search form-control form-control-sm appended-form-control" placeholder="Búsqueda de productos" id="searchInput" oninput="performSearch()">
                  <div class="input-group-append-overlay">
                    <span class="input-group-text">
                      <i class="cxi-search font-size-sm"></i>
                    </span>
                  </div>
                </div>


                <!-- Categorias -->
                <?php foreach ($categorias as $categoria_id => $categoria_data) : ?>
                  <div class="card border-bottom">
                    <div class="card-header py-3 text-center" id="category-panel-<?php echo $categoria_id; ?>">
                      <h6 class="accordion-heading">
                        <a href="#" role="button" data-toggle="collapse" data-target="#category-<?php echo $categoria_id; ?>" aria-expanded="false" aria-controls="category-<?php echo $categoria_id; ?>" class="collapsed categoria-link" data-categoria="<?php echo $categoria_data['nombre']; ?>">
                          <?php echo $categoria_data['nombre']; ?>
                          <span class="accordion-indicator"></span>
                        </a>
                      </h6>
                    </div>
                    <div class="collapse" id="category-<?php echo $categoria_id; ?>" aria-labelledby="category-panel-<?php echo $categoria_id; ?>">
                      <div class="cs-widget-data-list cs-filter">
                        <div class="input-group-overlay mb-3">
                          <input type="text" class="cs-filter-search form-control form-control-sm appended-form-control" placeholder="Busqueda">
                          <div class="input-group-append-overlay">
                            <span class="input-group-text">
                              <i class="cxi-search font-size-sm"></i>
                            </span>
                          </div>
                        </div>
                        <ul class="cs-filter-list list-unstyled pr-3" style="height: 12rem;" data-simplebar data-simplebar-auto-hide="false">
                          <?php foreach ($categoria_data['subcategorias'] as $subcategoria) : ?>
                            <li class="cs-filter-item" data-subcategoria="<?php echo strtolower($subcategoria); ?>">
                              <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="<?php echo strtolower($subcategoria); ?>">
                                <label for="<?php echo strtolower($subcategoria); ?>" class="custom-control-label">
                                  <span class="cs-filter-item-text"><?php echo $subcategoria; ?></span>
                                </label>
                              </div>
                            </li>
                          <?php endforeach; ?>
                        </ul>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>

                <!-- Precio 
                <div class="card border-bottom">
                  <div class="card-header py-3 text-center" id="price-panel">
                    <h6 class="accordion-heading">
                      <a href="#price" role="button" data-toggle="collapse" aria-expanded="true" aria-controls="price">
                        Precio
                        <span class="accordion-indicator"></span>
                      </a>
                    </h6>
                  </div>
                  <div class="collapse" id="price" aria-labelledby="price-panel">
                    <div class="cs-widget pl-1 pr-3 pb-4 mt-n3">
                      <div class="cs-range-slider" data-start-min="0" data-start-max="1000" data-min="0" data-max="1000" data-step="1">
                        <div class="cs-range-slider-ui"></div>
                        <div class="d-flex align-items-center mt-3">
                          <div class="w-50">
                            <div class="form-group position-relative mb-0">
                              <input type="number" class="form-control form-control-sm cs-range-slider-value-min" min="0">
                            </div>
                          </div>
                          <div class="mx-1 px-2 font-size-xs">—</div>
                          <div class="w-50">
                            <div class="form-group position-relative mb-0">
                              <input type="number" class="form-control form-control-sm cs-range-slider-value-max" min="0">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> -->

                <!-- Marca
                <div class="card border-bottom">
                  <div class="card-header py-3" id="marca-panel">
                    <h6 class="accordion-heading">
                      <a href="#marca" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="marca" class="collapsed">
                        Marca
                        <span class="accordion-indicator"></span>
                      </a>
                    </h6>
                  </div>
                  <div class="collapse" id="marca" aria-labelledby="marca-panel">
                    <div class="cs-widget-data-list cs-filter">
                      <div class="input-group-overlay mb-3">
                        <input type="text" class="cs-filter-search form-control form-control-sm appended-form-control" placeholder="Busqueda">
                        <div class="input-group-append-overlay">
                          <span class="input-group-text">
                            <i class="cxi-search font-size-sm"></i>
                          </span>
                        </div>
                      </div>
                      <ul class="cs-filter-list list-unstyled pr-3" style="height: 12rem;" data-simplebar data-simplebar-auto-hide="false">
                        //<?php foreach ($marcas as $marca_id => $marca_data) : ?>//
                          <li class="cs-filter-item" data-marca>
                            <div class="custom-control custom-checkbox">
                              <input type="checkbox" class="custom-control-input" id="<?php echo strtolower($marca_data['nombre']); ?>">
                              <label for="<?php echo strtolower($marca_data['nombre']); ?>" class="custom-control-label">
                                <span class="cs-filter-item-text"><?php echo $marca_data['nombre']; ?></span>
                              </label>
                            </div>
                          </li>
                        //<?php endforeach; ?>//
                      </ul>
                    </div>
                  </div>
                </div> -->



              </div>
            </div>
          </div>


          <!-- Product grid -->
          <div class="col">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3" data-filters-columns>
              <?php if (!empty($productos)) : ?>
                <?php foreach ($productos as $producto) : ?>
                  <!-- Item -->
                  <div class="col pb-sm-2 mb-grid-gutter" id="producto-<?php echo $producto['producto_id']; ?>" data-created-at="<?php echo $producto['created_at']; ?>" data-subcategoria="<?php echo strtolower($producto['subcategoria']); ?>" data-marca="<?php echo strtolower($producto['marca']); ?>" data-producto-id="<?php echo $producto['producto_id']; ?>">
                    <div class="card card-product mx-auto">
                      <div class="card-product-img">
                        <?php
                        $nombreCarpeta = str_replace(' ', '_', $producto['nombre']);
                        $nombreImagen = $nombreCarpeta . '_1.png';
                        $rutaImagen = "./admin/assets/img/productos_img/$nombreCarpeta/$nombreImagen";
                        $rutaImagenDefault = "./admin/assets/img/productos_img/default.png";

                        // Verificar si la imagen en la ruta existe
                        if (file_exists($rutaImagen)) {
                          $imagenMostrar = $rutaImagen;
                        } else {
                          $imagenMostrar = $rutaImagenDefault;
                        }
                        ?>
                        <a href="detalle-producto.php?id=<?php echo $producto['producto_id']; ?>" class="card-img-top">
                          <img src="<?php echo $imagenMostrar; ?>" alt="Product image" draggable="false">
                        </a>
                        <div class="card-product-widgets-top">
                          <div class="star-rating ml-auto">
                            <i class="sr-star cxi-star-filled active"></i>
                            <i class="sr-star cxi-star-filled active"></i>
                            <i class="sr-star cxi-star-filled active"></i>
                            <i class="sr-star cxi-star-filled active"></i>
                            <i class="sr-star cxi-star-filled active"></i>
                          </div>
                        </div>
                        <?php
                        // Verificar si el usuario tiene la sesión iniciada
                        if (isset($_SESSION["user_token"])) {
                          $user_token = $_SESSION["user_token"];
                        ?>
                          <div class="card-product-widgets-bottom">
                            <a href="#" class="btn-wishlist ml-auto" data-toggle="tooltip" data-placement="left" title="Añadir a favoritos"></a>
                          </div>
                        <?php
                        } else {
                          // Aquí puedes agregar el contenido que deseas mostrar cuando el usuario no tiene sesión iniciada
                        }
                        ?>
                      </div>
                      <div class="card-body pb-2">
                        <h3 class="card-product-title text-truncate mb-2">
                          <a href="detalle-producto.php?id=<?php echo $producto['producto_id']; ?>" class="nav-link"><?php echo $producto['nombre']; ?></a>
                        </h3>
                        <div class="d-flex align-items-center">
                          <span class="h5 d-inline-block mb-0 precio-span">
                            <?php echo 'S/.' . $producto['precio']; ?>
                          </span>
                        </div>
                      </div>
                      <div class="card-footer">
                        <button type="button" class="btn btn-primary btn-block add-to-cart-btn" data-product-id="<?php echo $producto['producto_id']; ?>" data-product-nombre="<?php echo $producto['nombre']; ?>" data-product-precio="<?php echo $producto['precio']; ?>" data-product-imagen="<?php echo $rutaImagen; ?>" data-product-marca="<?php echo $producto['marca']; ?>" data-product-categoria="<?php echo $producto['categoria']; ?>" data-product-stock="<?php echo $producto['stock']; ?>">
                          <i class="cxi-cart align-middle mt-n1 mr-2"></i>
                          Añadir al carrito
                        </button>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
            </div>
          <?php else : ?>
            <p>No hay productos disponibles.</p>
          <?php endif; ?>
          </div>


        </div>
      </section>
      </main>


      <!-- Footer -->
      <?php include 'footer.php'; ?>