<?php

// Incluir el archivo de conexión
include 'conexion.php';
session_start();

if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    session_destroy();
    echo '<script>window.location.href = window.location.pathname;</script>';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $nombre = trim($_POST['name']);
    $apellido = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $contrasena = $_POST['password'];
    $confirmarContrasena = $_POST['confirm_password'];

    if ($contrasena !== $confirmarContrasena) {
        $error = "Error: Las contraseñas no coinciden. Por favor, verifica las contraseñas e intenta nuevamente.";
    } elseif (strlen($contrasena) < 8) {
        $error = "Error: La contraseña debe tener al menos 8 caracteres.";
    } else {
        $query = "SELECT * FROM cliente WHERE email='$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $error = "Error: El correo electrónico ya está registrado. Por favor, intenta con otro correo.";
        } else {
            $contrasenaHash = md5($contrasena); // Considera usar password_hash() para mayor seguridad
            $con_contraseñaHash= md5($confirmarContrasena);
            $sqlInsert = "INSERT INTO cliente (nombre, apellido, email, contraseña,confirmar_contraseña) VALUES ('$nombre', '$apellido', '$email', '$contrasenaHash','$con_contraseñaHash')";

            if ($conn->query($sqlInsert) === TRUE) {
                $success = "¡Éxito! Estás registrado. Inicia sesión.";
            } else {
                $error = "Error en el registro. Inténtalo de nuevo.";
            }
        }
    }
}

// Proceso de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
  $email = trim($_POST['login_email']);
  $contrasena = $_POST['login_password'];

  // Verifica el correo y la contraseña en la base de datos
  $sqlSelect = "SELECT * FROM cliente WHERE email='$email'";
  $result = $conn->query($sqlSelect);

  if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      if (md5($contrasena) === $row['contraseña']) {
          // Genera un token personalizado
          $token = md5(uniqid(rand(), true));

          // Actualiza el token en la base de datos
          $sqlUpdateToken = "UPDATE cliente SET token='$token' WHERE email='$email'";
          $conn->query($sqlUpdateToken);

          // Almacena el token y el email en la sesión
          $_SESSION["user_token"] = $token;
          $_SESSION["email"] = $email;

          // Marca al usuario como administrador si corresponde
          if ($email == 'admin1@gmail.com') {
              $_SESSION["is_admin"] = true;
          }

          // Redirige al usuario a la página actual después de iniciar sesión
          header("Location: " . $_SERVER['REQUEST_URI']);
          exit();
      } else {
          $error = "Error: Contraseña incorrecta.";
      }
  } else {
      $error = "Error: Correo electrónico no registrado.";
  }
}

// Obtener las rutas de los archivos desde la base de datos
$icon = "../assets/img/favicon.png";
$logo = "../assets/img/logo.png";
$logonav = "../assets/img/nav.png";

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

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $nombre_tienda = $row['nombre'];
  $facebook = $row['facebook'];
  $instagram = $row['instagram'];
  $whatsapp = $row['whatsapp'];
  $numero = $row['numero'];
  $email = $row['email'];
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

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title><?php echo $nombre_tienda; ?></title>
  <script src="./assets/js/java.js"></script>
  <!-- Viewport-->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://checkout.culqi.com/js/v4"></script>





  <!-- Favicon and Touch Icons-->
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <meta name="msapplication-TileColor" content="#766df4">
  <meta name="theme-color" content="#ffffff">

  <!-- Vendor Styles-->
  <link rel="stylesheet" media="screen" href="assets/vendor/tiny-slider/dist/tiny-slider.css" />

  <!-- Style-->
  <link rel="stylesheet" media="screen" href="assets/css/demo/ecommerce/style.css" />
  <link rel="stylesheet" media="screen" href="assets/css/demo/ecommerce/nouislider.min.css">
  <link rel="stylesheet" media="screen" href="assets/css/demo/ecommerce/simplebar.min.css">

  <!-- Main Theme Styles + Bootstrap-->
  <link rel="stylesheet" media="screen" href="assets/css/demo/ecommerce/theme.min.css">
  

  <!-- Page loading scripts-->
  <script>
    window.addEventListener("load", function() {
      var preloader = document.getElementById("preloader");
      preloader.style.display = "none"; // Oculta el precargador cuando la página se haya cargado completamente
    });
  </script>


  <script>
    // Función para cambiar la moneda 
    function changeCurrency() {
      const penLink = document.getElementById("penLink");
      const usdLink = document.querySelector(".dropdown-menu a.dropdown-item");
      const precioSpan = document.querySelector(".precio-span");

      // Obtén el contenido y la ruta de la imagen de PEN
      const penText = penLink.textContent.trim();
      const penImageSrc = penLink.querySelector("img").getAttribute("src");

      // Obtén el contenido y la ruta de la imagen de USD
      const usdText = usdLink.textContent.trim();
      const usdImageSrc = usdLink.querySelector("img").getAttribute("src");

      // Verifica si se seleccionó USD y guarda en el almacenamiento local
      if (usdText.includes("USD")) {
        localStorage.setItem("selectedCurrency", "USD");
        precioSpan.textContent = precioSpan.textContent.replace("S/.", "$");
      } else {
        localStorage.setItem("selectedCurrency", "PEN");
        precioSpan.textContent = precioSpan.textContent.replace("$", "S/.");
      }

      // Actualiza los enlaces con el contenido e imagen de la otra moneda
      penLink.innerHTML = `
      <img src="${usdImageSrc}" class="mr-2" width="20" alt="E.E.U.U">
      ${usdText}
    `;
      usdLink.innerHTML = `
      <img src="${penImageSrc}" class="mr-2" width="20" alt="Perú">
      ${penText}
    `;
    }

    // Función para restaurar la selección de moneda al cargar la página
    window.onload = function() {
      const selectedCurrency = localStorage.getItem("selectedCurrency");
      if (selectedCurrency === "USD") {
        // Llama a la función para cambiar a USD
        changeCurrency();
      }
    };
  </script>


  <!-- funcion del carrito -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
      const cartBody = document.querySelector('.cs-offcanvas-body');
      const cartCountElement = document.querySelector('.cart-count');

      const storedCart = JSON.parse(localStorage.getItem('cart')) || [];
      let totalAmount = 0;

      function updateTotal() {
        const totalElement = document.getElementById('total-amount');
        if (storedCart.length === 0) {
          totalElement.textContent = 'S/. 0.00 ($0.00)';
        } else {
          totalElement.textContent = `S/. ${totalAmount.toFixed(2)} ($${(totalAmount / 4.0).toFixed(2)})`;
        }
      }

      function renderCart() {
        cartBody.innerHTML = '';
        if (cartCountElement !== null) {
          cartCountElement.textContent = storedCart.length;
        }

        const cartTitleElement = document.getElementById('cart-title');
        const cartItemElement = document.getElementById('cart-item');

        cartTitleElement.innerHTML = `Tú carrito (${storedCart.length})`;
        cartItemElement.innerHTML = `${storedCart.length}`;

        totalAmount = 0;

        storedCart.forEach((producto, index) => {
          const cartItem = document.createElement('div');
          cartItem.classList.add('media', 'p-4', 'border-bottom', 'mx-n4');
          const cartItemId = `cart-item-${index}`;

          const precioTotal = parseFloat(producto.precio) * producto.cantidad;

          cartItem.innerHTML = `
        <a href="detalle-producto.php?id=${producto.id}" style="min-width: 80px;">
          <img src="${producto.imagen}" width="80" alt="Product thumb">
        </a>
        <div class="media-body pl-3">
          <div class="d-flex justify-content-between">
            <div class="pr-2">
              <h3 class="font-size-sm mb-3">
                <a href="detalle-producto.php?id=${producto.id}" class="nav-link font-weight-bold">${producto.nombre}</a>
              </h3>
              <ul class="list-unstyled font-size-xs mt-n2 mb-2">
                <li class="mb-0"><span class="text-muted">Categoria:</span>${producto.categoria}</li>
                <li class="mb-0"><span class="text-muted">Marca:</span>${producto.marca}</li>
              </ul>
              <div class="d-flex align-items-center">
              <input type="number" class="form-control form-control-sm bg-light mr-3 cart-quantity-input" style="width: 4.5rem;" value="${producto.cantidad}" required min="0" max="${producto.stock}" data-cart-item="${cartItemId}">
                <span class="h5 d-inline-block mb-0" data-cart-total="${cartItemId}">
                  S/. ${precioTotal.toFixed(2)} ($${(precioTotal / 4.0).toFixed(2)})
                </span>
              </div>
              <button class="btn btn-link btn-sm text-decoration-none px-0 pb-0" data-product-index="${index}">
                Agregar a
                <i class="cxi-heart ml-1"></i>
              </button>
              </div>
              <div class="nav-muted mr-n2">
              <a href="#" class="btn btn-link btn-sm text-decoration-none px-0 pb-0 delete-product" data-product-index="${index}">
                <i class="cxi-delete ml-1"></i>
              </a>
              </div>
          </div>
        </div>
      `;

          cartBody.appendChild(cartItem);

          if (storedCart.length > 0) {
            totalAmount += parseFloat(producto.precioTotal);
          }

          const cantidadInput = cartItem.querySelector(`[data-cart-item="${cartItemId}"]`);
          cantidadInput.addEventListener('input', function(event) {
            const newCantidad = parseInt(event.target.value, 10);
            if (!isNaN(newCantidad) && newCantidad >= 0) {
              producto.cantidad = newCantidad;
              producto.precioTotal = parseFloat(producto.precio) * newCantidad;

              localStorage.setItem('cart', JSON.stringify(storedCart));

              const precioTotalElement = cartItem.querySelector(`[data-cart-total="${cartItemId}"]`);
              precioTotalElement.textContent = `S/. ${producto.precioTotal.toFixed(2)} ($${(producto.precioTotal / 4.0).toFixed(2)})`;

              totalAmount = storedCart.reduce((total, prod) => total + prod.precioTotal, 0);
              updateTotal();
            }
          });

          const deleteButton = cartItem.querySelector('.delete-product');
          deleteButton.addEventListener('click', function(event) {
            event.preventDefault();
            storedCart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(storedCart));
            renderCart();

            iziToast.show({
              title: '¡Producto eliminado!',
              message: 'El producto ha sido eliminado del carrito.',
              color: 'success',
              position: 'topLeft',
              timeout: 1500,
            });
          });


          const deleteCartButtons = document.querySelectorAll('.delete-cart-item');
          deleteCartButtons.forEach(deleteButton => {
            deleteButton.addEventListener('click', function(event) {
              event.preventDefault();
              const productIndex = parseInt(deleteButton.getAttribute('data-product-index'));
              storedCart.splice(productIndex, 1);
              localStorage.setItem('cart', JSON.stringify(storedCart));
              renderCart();

              iziToast.show({
                title: '¡Producto eliminado!',
                message: 'El producto ha sido eliminado del carrito.',
                color: 'success',
                position: 'topLeft',
                timeout: 1500,
              });
            });
          });
        });

        updateTotal();
      }

      function updateTotal() {
        const totalElement = document.getElementById('total-amount');
        if (storedCart.length === 0) {
          totalElement.textContent = 'S/. 0.00';
        } else {
          totalElement.textContent = `S/. ${totalAmount.toFixed(2)} ($${(totalAmount / 4.0).toFixed(2)})`;
        }
      }

      // Mostrar los productos en el carrito al cargar la página
      renderCart();

      addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
          const nombre = button.getAttribute('data-product-nombre');
          const precio = button.getAttribute('data-product-precio');
          const imagen = button.getAttribute('data-product-imagen');
          const marca = button.getAttribute('data-product-marca');
          const categoria = button.getAttribute('data-product-categoria');
          const id = button.getAttribute('data-product-id');
          const stock = parseInt(button.getAttribute('data-product-stock'));

          // Verificar si el producto ya está en el carrito
          const productoExistente = storedCart.find(item => item.nombre === nombre);
          if (productoExistente) {
            if (productoExistente.cantidad >= stock) { // Verificar si se alcanzó el límite de stock
              iziToast.error({
                title: 'Error',
                timeout: 1500,
                message: 'Se alcanzó el límite de stock para este producto',
                position: 'topLeft'
              });
              return;
            }
            // Aumentar la cantidad y actualizar el precio
            productoExistente.cantidad += 1;
            productoExistente.precioTotal = parseFloat(productoExistente.precio) * productoExistente.cantidad;

            localStorage.setItem('cart', JSON.stringify(storedCart));

            iziToast.success({
              title: 'Éxito',
              timeout: 1500,
              message: `Se aumentó la cantidad de ${nombre} en el carrito`,
              position: 'topLeft'
            });

            // Actualizar la visualización del carrito
            renderCart();
            return;
          }

          storedCart.push({
            nombre: nombre,
            precio: precio,
            imagen: imagen,
            marca: marca,
            categoria: categoria,
            cantidad: 1, // Agregar la cantidad inicial
            precioTotal: parseFloat(precio), // Agregar el precio total inicial
            stock: stock,
            id: id
          });

          localStorage.setItem('cart', JSON.stringify(storedCart));

          iziToast.success({
            title: 'Éxito',
            timeout: 1500,
            message: "Producto añadido al carrito: " + nombre,
            position: 'topLeft'
          });

          // Actualizar la visualización del carrito
          renderCart();
        });
      });


      // Después de definir cartCountElement
      const cartTitleElement = document.getElementById('cart-title');
      const cartItemElement = document.getElementById('cart-item');
    });
  </script>


</head>


<!-- Body-->

<body>

  <!-- Google Tag Manager (noscript)-->
  <noscript>
    <iframe src="//www.googletagmanager.com/ns.php?id=GTM-WKV3GT5" height="0" width="0" style="display: none; visibility: hidden;"></iframe>
  </noscript>

  <!-- Inicia Precargador. -->
  <div id="preloader">
    <div id="preloader-logo">
      <img src="assets/img/logo.png" alt="Logo">
    </div>
  </div>
  <!-- Fin Precargador. -->

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

  <div class="whatsapp-icon">
    <a href="https://api.whatsapp.com/send?phone=51<?php echo $whatsapp; ?>" target="_blank">
      <img src="./assets/img/icon_whast_vf.png" alt="WhatsApp Icon">
      <div class="message">Unete a nuestro grupo!</div>
    </a>
  </div>

  <!-- Page wrapper for sticky footer -->
  <!-- Wraps everything except footer to push footer to the bottom of the page if there is little content -->
  <main class="cs-page-wrapper">

    <!-- Formulario -->
    <div class="modal fade" id="modal-signin" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">

          <!-- Formulario de Inicio -->
          <div class="cs-view show" id="modal-signin-view">
            <div class="modal-header border-0 pb-0 px-md-5 px-4 d-block position-relative">
              <h3 class="modal-title mt-4 mb-0 text-center">Iniciar sesión</h3>
              <button type="button" class="close position-absolute" style="top: 1.5rem; right: 1.5rem;" onclick="closeModal()" aria-label="Close">
                <i class="cxi-cross" aria-hidden="true"></i>
              </button>
            </div>
            <div class="modal-body px-md-5 px-4">
              <p class="font-size-sm text-muted text-center">Inicie sesión en su cuenta utilizando el correo electrónico y la contraseña proporcionados durante el registro.</p>
              <form class="needs-validation" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" novalidate>
                <div class="form-group">
                  <label for="signin-email">Correo electrónico</label>
                  <input type="email" class="form-control" id="signin-email" name="login_email" placeholder="Su dirección de correo electrónico" required>
                </div>
                <div class="form-group">
                  <label for="signin-password" class="form-label">Contraseña</label>
                  <div class="cs-password-toggle input-group-overlay">
                    <input type="password" class="form-control appended-form-control" id="signin-password" name="login_password" placeholder="Tu contraseña" required>
                    <div class="input-group-append-overlay">
                      <label class="btn cs-password-toggle-btn input-group-text">
                        <input type="checkbox" class="custom-control-input">
                        <i class="cxi-eye cs-password-toggle-indicator"></i>
                        <span class="sr-only">Mostrar contraseña</span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="d-flex justify-content-between align-items-center form-group">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="remember-me" checked>
                    <label for="remember-me" class="custom-control-label">Recuérdame</label>
                  </div>
                  <a href="#" class="font-size-sm text-decoration-none">¿Olvidaste tu contraseña?</a>
                </div>
                <button type="submit" class="btn btn-primary btn-block" name="login">Iniciar sesión</button>
                <p class="font-size-sm pt-4 mb-0">
                  ¿No tienes una cuenta?
                  <a href="#" class="font-weight-bold text-decoration-none" data-view="#modal-signup-view">Regístrate</a>
                </p>
              </form>
            </div>
          </div>

          <!-- Formulario de Registro -->
          <div class="cs-view" id="modal-signup-view">
            <div class="modal-header border-0 pb-0 px-md-5 px-4 d-block position-relative">
              <h3 class="modal-title mt-4 mb-0 text-center">Registrarse</h3>
              <button type="button" class="close position-absolute" style="top: 1.5rem; right: 1.5rem;" onclick="closeModal()" aria-label="Cerrar">
                <i class="cxi-cross" aria-hidden="true"></i>
              </button>
            </div>
            <div class="modal-body px-md-5 px-4">
              <p class="font-size-sm text-muted text-center">Regístrese utilizando su dirección de correo electrónico y una contraseña proporcionada durante el registro.</p>
              <form class="needs-validation" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onsubmit="return validatePassword();" novalidate>
                <div class="form-group">
                  <label for="signup-name">Nombres</label>
                  <input type="text" class="form-control" id="signup-name" name="name" placeholder="Su nombre" required>
                </div>
                <div class="form-group">
                  <label for="register-lastname">Apellido</label>
                  <input type="text" class="form-control" id="register-lastname" name="lastname" placeholder="Tu apellido" required>
                </div>
                <div class="form-group">
                  <label for="signup-email">Correo electrónico</label>
                  <input type="email" class="form-control" id="signup-email" name="email" placeholder="Su dirección de correo electrónico" required>
                </div>
                <div class="form-group">
                  <label for="signup-password" class="form-label">Contraseña</label>
                  <div class="cs-password-toggle input-group-overlay">
                    <input type="password" class="form-control appended-form-control" id="signup-password" name="password" placeholder="Su contraseña" required>
                    <div class="input-group-append-overlay">
                      <label class="btn cs-password-toggle-btn input-group-text">
                        <input type="checkbox" class="custom-control-input">
                        <i class="cxi-eye cs-password-toggle-indicator"></i>
                        <span class="sr-only">Mostrar contraseña</span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="signup-confirm-password" class="form-label">Confirmar contraseña</label>
                  <div class="cs-password-toggle input-group-overlay">
                    <input type="password" class="form-control appended-form-control" id="signup-confirm-password" name="confirm_password" placeholder="Confirme su contraseña" required>
                    <div class="input-group-append-overlay">
                      <label class="btn cs-password-toggle-btn input-group-text">
                        <input type="checkbox" class="custom-control-input">
                        <i class="cxi-eye cs-password-toggle-indicator"></i>
                        <span class="sr-only">Mostrar contraseña</span>
                      </label>
                    </div>
                  </div>
                </div>
                <button class="btn btn-primary btn-block" type="submit" name="register">Registrarse</button>
                <p class="font-size-sm pt-4 mb-0">¿Ya tiene una cuenta?
                  <a href="#" class="font-weight-bold text-decoration-none" data-view="#modal-signin-view">Inicie sesión</a>
                </p>
              </form>
            </div>
          </div>

          <div class="modal-body text-center px-0 pt-2 pb-4">
            <hr>
            <p class="font-size-sm text-heading mb-3 pt-4">O inicie sesión con:</p>
            <a href="#" class="social-btn sb-solid mx-1 mb-2" data-toggle="tooltip" title="Facebook">
              <i class="cxi-facebook"></i>
            </a>
            <a href="#" class="social-btn sb-solid mx-1 mb-2" data-toggle="tooltip" title="Google">
              <i class="cxi-google"></i>
            </a>
          </div>

        </div>
      </div>
    </div>

    <!-- Shopping cart off-canvas -->
    <div id="cart" class="cs-offcanvas cs-offcanvas-right">

      <!-- Header -->
      <div class="cs-offcanvas-cap align-items-center border-bottom">
        <h2 id="cart-title" class="h5 mb-0">Tú carrito (0)</h2>
        <button class="close mr-n1" type="button" data-dismiss="offcanvas" aria-label="Close">
          <span class="h3 font-weight-normal mb-0" aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Body -->
      <div class="cs-offcanvas-body">
      </div>

      <!-- Footer -->
      <div class="cs-offcanvas-cap flex-column border-top">
        <div class="d-flex align-items-center justify-content-between mb-3 pb-1">
          <span class="text-muted mr-2">Total:</span>
          <span id="total-amount" class="h5 mb-0"></span>
        </div>
        <button id="checkout-button" class="btn btn-primary btn-lg btn-block">
          <i class="cxi-credit-card font-size-lg mt-n1 mr-1"></i>
          Ir al carrito
        </button>
      </div>
    </div>


    <!-- Header (Topbar + Navbar) -->
    <header class="cs-header">

      <!-- Topbar -->
      <div class="topbar topbar-dark bg-dark">
        <div class="container d-flex align-items-center px-0 px-xl-3">
          <div class="mr-3">
            <a href="tel:+51 <?php echo $numero; ?>" class="topbar-link d-md-inline-block d-none">
              Para mas información:
              <span class='font-weight-bold'>+51 <?php echo $numero; ?></span>
            </a>
            <a href="tel:+51 <?php echo $numero; ?>" class="topbar-link d-md-none d-inline-block text-nowrap">
              <i class="cxi-iphone align-middle"></i>
              +51 <?php echo $numero; ?>
            </a>
          </div>
          <a class="topbar-link ml-auto mr-4 pr-sm-2 text-nowrap">
            <!--<i class="cxi-world mr-1 font-size-base align-middle"></i>
            Cambiar <span class="d-none d-sm-inline">idioma</span>!-->
          </a>
          <div>
            <a href="#" class="topbar-link" id="penLink" onclick="window.location.href = window.location.href; changeCurrency()">
              <img src="./assets/img/ecommerce/flags/pe.png" class="mr-2" width="20" alt="Perú">
              PEN - S/.
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item">
                <img src="./assets/img/ecommerce/flags/en.png" class="mr-2" width="20" alt="E.E.U.U">
                USD - $
              </a>
            </div>
          </div>
          <?php
          // Verificar si el usuario tiene la sesión iniciada
          if (isset($_SESSION["user_token"])) {
            // Conectar a la base de datos y obtener el nombre del usuario
            $user_token = $_SESSION["user_token"];
            $sqlSelectUser = "SELECT nombre FROM cliente WHERE token = '$user_token'";
            $result = $conn->query($sqlSelectUser);

            if ($result->num_rows == 1) {
              $row = $result->fetch_assoc();
              $username = $row["nombre"];

              // Mostrar el menú desplegable de usuario
              echo '<div class="dropdown">
            <a href="#" class="topbar-link dropdown-toggle d-lg-inline-block d-none ml-4 pl-1 text-decoration-none text-nowrap" data-toggle="dropdown">
                <i class="cxi-profile mr-1 font-size-base align-middle"></i>
                ' . $username . '
            </a>
            <div class="dropdown-menu dropdown-menu-right">
            <a href="account-profile.php" class="dropdown-item d-flex align-items-center">
              <i class="cxi-profile font-size-base mr-2"></i>
              <span>Mi Perfil</span>
            </a>
            <a href="account-orders.php" class="dropdown-item d-flex align-items-center">
              <i class="cxi-bag font-size-base mr-2"></i>
              <span>Mis Pedidos</span>
            </a>
            <a href="account-wishlist.php" class="dropdown-item d-flex align-items-center">
              <i class="cxi-heart font-size-base mr-2"></i>
              <span>Favoritos</span>
              <span class="badge badge-warning ml-auto">2</span>
            </a>
            <a href="account-recently-viewed.php" class="dropdown-item d-flex align-items-center">
              <i class="cxi-eye font-size-base mr-2"></i>
              <span>Vistos recientemente</span>
            </a>
            <a href="account-reviews.php" class="dropdown-item d-flex align-items-center">
              <i class="cxi-star font-size-base mr-2"></i>
              <span>Mis Reseñas</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="?logout=1" class="dropdown-item d-flex align-items-center">
            <i class="cxi-logout font-size-base mr-2"></i>
            <span>Cerrar Sesión</span>
            </a>
          </div>
        </div>';
            }
            // No es necesario usar else ya que si no hay una coincidencia en la base de datos, simplemente no se mostrará el menú.
          } else {
            // Si el usuario no tiene la sesión iniciada, mostrar el enlace de Iniciar Sesión / Registrarse
            echo '<a href="#" class="topbar-link d-lg-inline-block d-none ml-4 pl-1 text-decoration-none text-nowrap" onclick="openModal(); return false;">
            <i class="cxi-profile mr-1 font-size-base align-middle"></i>
            Iniciar Sesión / Registrarse
          </a>';
          }
          ?>
        </div>
      </div>

      <!-- Navbar -->
      <!-- Remove "navbar-sticky" class to make navigation bar scrollable with the page -->
      <div class="navbar navbar-expand-lg navbar-light bg-light navbar-sticky" data-fixed-element>
        <div class="container px-0 px-xl-3">
          <a href="./" class="navbar-brand order-lg-1 mr-0 pr-lg-3 mr-lg-4">
            <img src="assets/img/nav.png" alt="Nav Logo" width="130">
          </a>
          <!-- Toolbar -->
          <div class="d-flex align-items-center order-lg-3">
            <ul class="nav nav-tools flex-nowrap">
              <?php
              // Verificar si el usuario tiene la sesión iniciada
              if (isset($_SESSION["user_token"])) {
                $user_token = $_SESSION["user_token"];
              ?>
                <li class="nav-item d-lg-block d-none mb-0">
                  <a href="account-wishlist.php" class="nav-tool">
                    <i class="cxi-heart nav-tool-icon"></i>
                    <span class="nav-tool-label">2</span>
                  </a>
                </li>
                <li class="divider-vertical mb-0 d-lg-block d-none"></li>
              <?php
              } else {
                // Aquí puedes agregar el contenido que deseas mostrar cuando el usuario no tiene sesión iniciada
              }
              ?>
              
              <?php
              if (basename($_SERVER['PHP_SELF']) !== 'detalle-compra.php') {
              ?>
                <li class="nav-item align-self-center mb-0">
                  <a href="#" class="nav-tool pr-lg-0" data-toggle="offcanvas" data-target="cart">
                    <i class="cxi-cart nav-tool-icon"></i>
                    <span id="cart-item" class="badge badge-success align-middle mt-n1 ml-2 px-2 py-1 font-size-xs">0</span>
                  </a>
                </li>
              <?php
              }
              ?>
              <li class="divider-vertical mb-0 d-lg-none d-block"></li>
              <li class="nav-item mb-0">
                <button class="navbar-toggler mt-n1 mr-n3" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-expanded="false">
                  <span class="navbar-toggler-icon"></span>
                </button>
              </li>
            </ul>
          </div>
          <!-- Navbar collapse -->
          <nav class="collapse navbar-collapse order-lg-2" id="navbarCollapse">
            <!-- Search mobile -->
            <div class="input-group-overlay form-group mb-0 d-lg-none d-block">
              <input type="text" class="form-control prepended-form-control rounded-0 border-0" placeholder="Search for products...">
              <div class="input-group-prepend-overlay">
                <span class="input-group-text">
                  <i class="cxi-search font-size-lg align-middle mt-n1"></i>
                </span>
              </div>
            </div>

            
            <!-- Menu -->
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <a href="index.php" class="nav-link">Inicio</a>
              </li>
              <li class="nav-item">
                <a href="catalogo.php" class="nav-link" >Tienda</a>
          
              </li>
              <li class="nav-item dropdown mega-dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Menú</a>
                <div class="dropdown-menu">
                  <div class="container pt-lg-1 pb-lg-3">
                    <div class="row">
                      <div class="col-lg-2 col-md-3 py-2">
                        <ul class="list-unstyled">
                          <li><a href="#" class="dropdown-item">Recien añadidos</a></li>
                          <li><a href="#" class="dropdown-item">Populares</a></li>
                        </ul>
                      </div>
                      <?php
                      // Suponiendo que ya tienes un arreglo $categorias con la información de las categorías y subcategorías
                      $contador = 0;
                      foreach ($categorias as $categoria) {
                        $nombre_categoria = $categoria['nombre'];
                        $subcategorias = $categoria['subcategorias'];
                        $contador++; // Incrementar el contador

                        // Detener el bucle cuando el contador alcance 3
                        if ($contador > 3) {
                          break;
                        }
                      ?>

                        <!-- Estructura HTML para mostrar las categorías y subcategorías -->
                        <div class="col-lg-2 col-md-3 py-2">
                          <h4 class="font-size-sm text-uppercase pt-1 mb-2"><?php echo $nombre_categoria; ?></h4>
                          <ul class="list-unstyled">
                            <?php foreach ($subcategorias as $subcategoria) : ?>
                              <li><a href="catalogo.php#<?php echo strtolower($subcategoria); ?>" class="dropdown-item"><?php echo $subcategoria; ?></a></li>
                            <?php endforeach; ?>
                          </ul>
                        </div>

                      <?php } ?>
                      <div class="col-lg-1 d-none d-lg-block py-2">
                        <span class="divider-vertical h-100 mx-auto"></span>
                      </div>
                      <div class="col-lg-3 d-none d-lg-block py-2">
                        <a href="#" class="d-block text-decoration-none pt-1">
                          <img src="./assets/img/ecommerce/megamenu.jpg" class="d-block rounded mb-3" alt="Promo banner">
                          <h5 class="font-size-sm mb-3">Back to school. Sale up to 50%</h5>
                          <div class="btn btn-outline-primary btn-sm">
                            See offers
                            <i class="cxi-arrow-right ml-1"></i>
                          </div>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Información</a>
                <ul class="dropdown-menu">
                  <li><a href="contacto.php" class="dropdown-item">Contactanos</a></li>
    
                </ul>
              </li>
              
              <li class="nav-item">
               <?php
    if (isset($error)) {
        echo "<p>$error</p>";
    }

    // Mostrar el botón de administración si el usuario es admin
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
        echo '<a href="admin/index.php" class="nav-link">Ir al panel de administración</a>';
    }
    ?>
          
              </li>

              <!--modificar pa cel-->

              <li class="nav-item dropdown d-md-none">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                  <i class="cxi-profile font-size-base align-middle mr-1"></i>
                  User 1
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a href="account-profile.php" class="dropdown-item d-flex align-items-center">
                      <i class="cxi-profile font-size-base mr-2"></i>
                      <span>Mi Perfil</span>
                    </a>
                  </li>
                  <li>
                    <a href="account-orders.php" class="dropdown-item d-flex align-items-center">
                      <i class="cxi-bag font-size-base mr-2"></i>
                      <span>Mis Pedidos</span>
                    </a>
                  </li>
                  <li>
                    <a href="account-wishlist.php" class="dropdown-item d-flex align-items-center">
                      <i class="cxi-heart font-size-base mr-2"></i>
                      <span>Favoritos</span>
                    </a>
                  </li>
                  <li>
                    <a href="account-recently-viewed.php" class="dropdown-item d-flex align-items-center">
                      <i class="cxi-eye font-size-base mr-2"></i>
                      <span>Recientemente</span>
                    </a>
                  </li>
                  <li>
                    <a href="account-reviews.php" class="dropdown-item d-flex align-items-center">
                      <i class="cxi-star font-size-base mr-2"></i>
                      <span>Mis Reseñas</span>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="dropdown-item d-flex align-items-center">
                      <i class="cxi-logout font-size-base mr-2"></i>
                      <span>Cerrar Sesión</span>
                    </a>
                  </li>
                </ul>
              </li>



            </ul>
          </nav>
        </div>
      </div>
    </header>


    <!-- Promo bar -->
    <section class="cs-promo-bar bg-primary py-2">
      <div class="container d-flex justify-content-center">
        <div class="cs-carousel cs-controls-inverse">
          <div class="cs-carousel-inner" data-carousel-options='{"mode": "gallery", "nav": false}'>
            <div class="font-size-xs text-light px-2">
              <strong class="mr-1">Up to 70% Off.</strong>
              <a href="#" class="text-light">Shop our latest sale offers</a>
            </div>
            <div class="font-size-xs text-light px-2">
              <strong class="mr-1">Money back guarantee.</strong>
              <a href="#" class="text-light">Learn more</a>
            </div>
            <div class="font-size-xs text-light px-2">
              <strong class="mr-1">Friendly customer support.</strong>
              <a href="#" class="text-light">Contact 24/7</a>
            </div>
          </div>
        </div>
      </div>
    </section>