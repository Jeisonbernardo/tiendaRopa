<?php
// Incluir el archivo de conexión
include 'conexion.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Obtener la URL actual
$current_url = $_SERVER['REQUEST_URI'];

// Verificar si NO se ha iniciado sesión y NO hay un token almacenado
if (!isset($_SESSION['token'])) {
  header('Location: login.php');
  exit;
}

// Verificar si se ha enviado el parámetro para cerrar la sesión
if (isset($_GET['logout'])) {
  // Eliminar el token almacenado
  unset($_SESSION['token']);

  // Destruir la sesión
  session_destroy();

  // Redireccionar a login.php
  header('Location: login.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>ADMIN</title>

  <!-- Viewport-->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Favicon and Touch Icons-->
  <link rel="icon" type="image/png" href="assets/img/icon.png">
  <meta name="msapplication-TileColor" content="#766df4">
  <meta name="theme-color" content="#ffffff">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  <!-- Vendor Styles-->
  <link rel="stylesheet" media="screen" href="assets/vendor/simplebar/dist/simplebar.min.css" />

  <!-- Main Theme Styles + Bootstrap-->
  <link rel="stylesheet" media="screen" href="assets/css/theme.min.css">

  <!-- Izitoast-->
  <link rel="stylesheet" href="assets/css/iziToast.min.css">
  <script src="assets/js/iziToast.min.js"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      // Al hacer clic en un enlace de la lista de navegación
      $(".nav-link").on("click", function() {
        // Si el enlace hace referencia a un menú desplegable
        if ($(this).attr("data-toggle") === "collapse") {
          // Cerrar todos los menús desplegables abiertos
          $(".collapse").collapse("hide");
        }
      });
    });
  </script>

</head>


<!-- Body-->

<body>
  <main class="container-fluid pb-3 pb-sm-4">

    <!-- Main content-->
    <section class="cs-offcanvas-enabled row pb-3 pb-md-4">
      <div class="col-xl-9">

        

        <aside class="cs-offcanvas cs-offcanvas-expand bg-dark" id="componentsNav">
          <div class="cs-offcanvas-cap d-none d-lg-block py-4">
            <a class="navbar-brand py-2" href="./index.php">
              <img src="../assets/img/nav.png" alt="Logo" width="130">
            </a>
          </div>
          <div class="cs-offcanvas-cap d-flex d-lg-none">
            <div class="d-flex align-items-center mt-1">
              <h5 class="text-light mb-0 mr-3">Menú</h5>
            </div>
            <button class="close text-light" type="button" data-dismiss="offcanvas" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="d-flex d-lg-none align-items-center py-4 px-3 border-bottom border-light">
            <a class="btn btn-primary btn-block mr-3 px-4" href="../index.php"><i class="cxi-eye mt-n1 mr-2"></i>Ir a tienda</a>
            <a class="btn btn-success btn-block mt-0 px-4" href="?logout=true"><i class="cxi-layouts mt-n1 mr-2"></i>Cerrar sesión</a>
          </div>

          <div class="cs-offcanvas-body px-0 pt-4 pb-grid-gutter" data-simplebar data-simplebar-inverse>
            <h6 class="font-size-lg text-light px-4 mt-2 mb-3">Contenido</h6>
            <hr class="hr-gradient hr-light mb-2 opacity-25">
            <nav class="nav nav-light">
              <a class="nav-link px-4 <?php echo basename($current_url) === 'index.php' ? 'active' : ''; ?>" href="./index.php">Inicio</a>
              <a class="nav-link px-4 <?php echo basename($current_url) === 'cuenta.php' ? 'active' : ''; ?>" href="./cuenta.php">Cuenta</a>

              <!-- Categorías -->
              <a class="nav-link px-4 <?php echo basename($_SERVER['PHP_SELF']) === 'agregar_categorias.php' || basename($_SERVER['PHP_SELF']) === 'ver_categorias.php' ? 'active' : ''; ?>" data-toggle="collapse" href="#categoriasCollapse" role="button" aria-expanded="false" aria-controls="categoriasCollapse">
                Categorías
              </a>
              <div class="collapse <?php echo basename($_SERVER['PHP_SELF']) === 'agregar_categorias.php' || basename($_SERVER['PHP_SELF']) === 'ver_categorias.php' ? 'show' : ''; ?>" id="categoriasCollapse">
                <a class="dropdown-item" href="./agregar_categorias.php">Añadir categorías</a>
                <a class="dropdown-item" href="./ver_categorias.php">Ver categorías</a>
              </div>

              <!-- SubCategorías -->
              <a class="nav-link px-4 <?php echo basename($_SERVER['PHP_SELF']) === 'agregar_subcategorias.php' || basename($_SERVER['PHP_SELF']) === 'ver_subcategorias.php' ? 'active' : ''; ?>" data-toggle="collapse" href="#subCollapse" role="button" aria-expanded="false" aria-controls="subCollapse">
                Subcategorías
              </a>
              <div class="collapse <?php echo basename($_SERVER['PHP_SELF']) === 'agregar_subcategorias.php' || basename($_SERVER['PHP_SELF']) === 'ver_subcategorias.php' ? 'show' : ''; ?>" id="subCollapse">
                <a class="dropdown-item" href="./agregar_subcategorias.php">Añadir subcategorías</a>
                <a class="dropdown-item" href="./ver_subcategorias.php">Ver subcategorías</a>
              </div>

              <!-- Marcas -->
              <a class="nav-link px-4 <?php echo basename($_SERVER['PHP_SELF']) === 'agregar_marcas.php' || basename($_SERVER['PHP_SELF']) === 'ver_marcas.php' ? 'active' : ''; ?>" data-toggle="collapse" href="#marcasCollapse" role="button" aria-expanded="false" aria-controls="marcasCollapse">
                Marcas
              </a>
              <div class="collapse <?php echo basename($_SERVER['PHP_SELF']) === 'agregar_marcas.php' || basename($_SERVER['PHP_SELF']) === 'ver_marcas.php' ? 'show' : ''; ?>" id="marcasCollapse">
                <a class="dropdown-item" href="./agregar_marcas.php">Añadir marcas</a>
                <a class="dropdown-item" href="./ver_marcas.php">Ver marcas</a>
              </div>

              <!-- Productos -->
              <a class="nav-link px-4 <?php echo basename($_SERVER['PHP_SELF']) === 'agregar_productos.php' || basename($_SERVER['PHP_SELF']) === 'ver_productos.php' ? 'active' : ''; ?>" data-toggle="collapse" href="#productosCollapse" role="button" aria-expanded="false" aria-controls="productosCollapse">
                Productos
              </a>
              <div class="collapse <?php echo basename($_SERVER['PHP_SELF']) === 'agregar_productos.php' || basename($_SERVER['PHP_SELF']) === 'ver_productos.php' ? 'show' : ''; ?>" id="productosCollapse">
                <a class="dropdown-item" href="./agregar_productos.php">Añadir Productos</a>
                <a class="dropdown-item" href="./ver_productos.php">Ver Productos</a>
              </div>

              <!-- Clientes
              <a class="nav-link px-4 <?php echo basename($_SERVER['PHP_SELF']) === 'agregar_clientes.php' || basename($_SERVER['PHP_SELF']) === 'ver_clientes.php' ? 'active' : ''; ?>" data-toggle="collapse" href="#clientesCollapse" role="button" aria-expanded="false" aria-controls="clientesCollapse">
                Clientes
              </a>
              <div class="collapse <?php echo basename($_SERVER['PHP_SELF']) === 'agregar_clientes.php' || basename($_SERVER['PHP_SELF']) === 'ver_clientes.php' ? 'show' : ''; ?>" id="clientesCollapse">
                <a class="dropdown-item" href="./agregar_clientes.php">Añadir Clientes</a>
                <a class="dropdown-item" href="./ver_clientes.php">Ver Clientes</a>
              </div> -->

              <!-- Promociones
              <a class="nav-link px-4 <?php echo basename($_SERVER['PHP_SELF']) === 'agregar_cupon.php' || basename($_SERVER['PHP_SELF']) === 'agregar_cupon.php' || basename($_SERVER['PHP_SELF']) === 'agregar_descuento.php' || basename($_SERVER['PHP_SELF']) === 'ver_descuento.php' ? 'active' : ''; ?>" data-toggle="collapse" href="#promocionCollapse" role="button" aria-expanded="false" aria-controls="promocionCollapse">
                Promociones
              </a>
              <div class="collapse <?php echo basename($_SERVER['PHP_SELF']) === 'agregar_cupon.php' || basename($_SERVER['PHP_SELF']) === 'agregar_cupon.php' || basename($_SERVER['PHP_SELF']) === 'agregar_descuento.php' || basename($_SERVER['PHP_SELF']) === 'ver_descuento.php' ? 'show' : ''; ?>" id="promocionCollapse">
                <a class="dropdown-item" href="./agregar_productos.php">Agregar cupon</a>
                <a class="dropdown-item" href="./ver_productos.php">Ver cupon</a>
                <a class="dropdown-item" href="./agregar_productos.php">Agregar descuento</a>
                <a class="dropdown-item" href="./ver_productos.php">Ver descuento</a>
              </div>-->
              <a class="nav-link px-4 <?php echo basename($current_url) === 'contacto.php' ? 'active' : ''; ?>" href="./ver_contacto.php">Contactos</a>
              <a class="nav-link px-4 <?php echo basename($current_url) === 'configuraciones.php' ? 'active' : ''; ?>" href="./configuraciones.php">Configurar tienda</a>

            </nav>
          </div>

          <!-- Número con fecha y hora -->
          <div style="position: fixed; z-index: 999; bottom: 20px; right: 20px; background-color: #fff; padding: 5px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
            <?php

            // Establecer la zona horaria a Lima
            date_default_timezone_set('America/Lima');

            // Traducción de nombres de meses al español
            function traducirMes($mes)
            {
              $meses = array(
                'January' => 'de Enero', 'February' => 'de Febrero', 'March' => 'de Marzo',
                'April' => 'de Abril', 'May' => 'de Mayo', 'June' => 'de Junio',
                'July' => 'de Julio', 'August' => 'de Agosto', 'September' => 'de Septiembre',
                'October' => 'de Octubre', 'November' => 'de Noviembre', 'December' => 'de Diciembre'
              );
              return $meses[$mes];
            }

            // Establecer la zona horaria a español
            setlocale(LC_TIME, 'es_ES');

            // Obtener la fecha y hora actual en formato español
            $fechaHoraActual = date('d') . ' ' . traducirMes(date('F')) . ' ' . date('Y H:i');
            echo $fechaHoraActual;
            ?>
          </div>

        </aside>