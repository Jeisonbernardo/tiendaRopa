<?php
// Incluir el archivo de conexión
include 'conexion.php';

$ruta_activa = true; // Cambiar a true para acceder

if (!$ruta_activa) {
  header('Location: acceso_denegado.php');
  exit;
}

// Procesar el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Obtener y limpiar los datos ingresados por el usuario
  $name = trim($_POST['name']);
  $lastname = trim($_POST['lastname']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  // Verificar que todos los campos estén completos
  if (empty($name) || empty($lastname) || empty($email) || empty($password) || empty($confirm_password)) {
    $error_message = "Error: Todos los campos son obligatorios. Por favor, completa todos los campos y vuelve a intentarlo.";
  } else {
    // Verificar que las contraseñas coincidan
    if ($password !== $confirm_password) {
      $error_message = "Error: Las contraseñas no coinciden. Por favor, verifica las contraseñas e intenta nuevamente.";
    } elseif (strlen($password) < 8) {
      $error_message = "Error: La contraseña debe tener al menos 8 caracteres.";
    } else {
      // Verificar que el email no exista previamente en la base de datos
      $query = "SELECT * FROM admin WHERE email='$email'";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0) {
        $error_message = "Error: El correo electrónico ya está registrado. Por favor, intenta con otro correo.";
      } else {
        // Agregar la imagen por defecto
        $foto = "assets/img/admin_img/default.png";

        // Hashear la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertar los datos en la base de datos
        $query = "INSERT INTO admin (nombre, apellido, contraseña, email, foto) VALUES ('$name', '$lastname', '$hashed_password', '$email', '$foto')";
        if (mysqli_query($conn, $query)) {
          $success_message = "Registro exitoso. Bienvenido, $name!";
        } else {
          $error_message = "Error al registrar el usuario: " . mysqli_error($conn);
        }
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>ADMIN</title>

  <!-- SEO Meta Tags-->
  <meta name="description" content="Createx - Multipurpose Bootstrap Template and UI Kit">
  <meta name="keywords" content="bootstrap, business, creative agency, construction, services, e-commerce, shopping cart, mobile app showcase, multipurpose, shop, ui kit, marketing, seo, landing, html5, css3, javascript, gallery, slider, touch, creative">
  <meta name="author" content="Createx Studio">

  <!-- Viewport-->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Favicon and Touch Icons-->
  <link rel="icon" type="image/png" href="assets/img/icon.png">
  <meta name="msapplication-TileColor" content="#766df4">
  <meta name="theme-color" content="#ffffff">

  <!-- Vendor Styles-->
  <link rel="stylesheet" media="screen" href="assets/vendor/simplebar/dist/simplebar.min.css" />

  <!-- Main Theme Styles + Bootstrap-->
  <link rel="stylesheet" media="screen" href="assets/css/theme.min.css">
</head>


<!-- Body-->

<body>

  <main class="container-fluid pb-3 pb-sm-4" style="width: 100%; height: 100%;">
    <div class="row" style="width: 100%; height: 100%;">
      <div class="mx-auto" style="display: flex; align-items: center;">
        <div class="modal-body px-md-5 px-4">
          <h3 class="modal-title mt-4 mb-0 text-center mb-5">Registrarse</h3>
          <div class="form-password-alert" id="form-password-alert"></div>
          <?php
          if (isset($error_message)) {
            echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
          } elseif (isset($success_message)) {
            echo '<div class="alert alert-success" role="alert">' . $success_message . '</div>';
          }
          ?>
          <p class="font-size-sm text-muted text-center">Crea tu cuenta de administrador proporcionando los siguientes detalles.</p>
          <form class="needs-validation" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onsubmit="return validatePassword();" novalidate>
            <div class="form-group">
              <label for="register-name">Nombre</label>
              <input type="text" class="form-control" id="register-name" name="name" placeholder="Tu nombre" required>
            </div>
            <div class="form-group">
              <label for="register-lastname">Apellido</label>
              <input type="text" class="form-control" id="register-lastname" name="lastname" placeholder="Tu apellido" required>
            </div>
            <div class="form-group">
              <label for="register-email">Correo electrónico</label>
              <input type="email" class="form-control" id="register-email" name="email" placeholder="Tu correo electrónico" required>
            </div>
            <div class="form-group">
              <label for="register-password" class="form-label">Contraseña</label>
              <div class="cs-password-toggle input-group-overlay">
                <input type="password" class="form-control appended-form-control" id="register-password" name="password" placeholder="Tu contraseña" required>
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
              <label for="register-confirm-password" class="form-label">Confirmar contraseña</label>
              <div class="cs-password-toggle input-group-overlay">
                <input type="password" class="form-control appended-form-control" id="register-confirm-password" name="confirm_password" placeholder="Confirmar tu contraseña" required>
                <div class="input-group-append-overlay">
                  <label class="btn cs-password-toggle-btn input-group-text">
                    <input type="checkbox" class="custom-control-input">
                    <i class="cxi-eye cs-password-toggle-indicator"></i>
                    <span class="sr-only">Mostrar contraseña</span>
                  </label>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
            <p class="font-size-sm pt-4 mb-0">
              ¿Ya tienes una cuenta?
              <a href="login.php" class="font-weight-bold text-decoration-none" data-view="#modal-signin-view">Iniciar sesión</a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </main>

  <a class="btn-scroll-top" href="#top" data-scroll data-fixed-element>
    <span class="btn-scroll-top-tooltip text-muted font-size-sm mr-2">Top</span>
    <i class="btn-scroll-top-icon cxi-angle-up"></i>
  </a>

  <!-- Vendor scripts: js libraries and plugins-->
  <script src="assets/vendor/jquery/dist/jquery.slim.min.js"></script>
  <!-- Agregar este código dentro del bloque <script> existente en la página HTML -->
  <script>
    function validatePassword() {
      const passwordInput = document.getElementById('register-password');
      const confirmPasswordInput = document.getElementById('register-confirm-password');
      const passwordAlertContainer = document.getElementById('form-password-alert');

      if (passwordInput.value.length < 8) {
        passwordAlertContainer.innerHTML = '<div class="alert alert-danger" role="alert">Error: La contraseña debe tener al menos 8 caracteres.</div>';
        return false;
      }

      if (passwordInput.value !== confirmPasswordInput.value) {
        passwordAlertContainer.innerHTML = '<div class="alert alert-danger" role="alert">Error: Las contraseñas no coinciden. Por favor, verifica las contraseñas e intenta nuevamente.</div>';
        return false;
      }

      return true;
    }
  </script>
  <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/simplebar/dist/simplebar.min.js"></script>
  <script src="assets/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

  <!-- Main theme script-->
  <script src="assets/js/theme.min.js"></script>
</body>

</html>