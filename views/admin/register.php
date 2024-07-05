
<?php include '../../config/config.php';
include BASE_PATH . 'config/conexion.php'; ?>
<?php include '../template/navbar_admin.php'; ?>

<?php

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
        $foto = "../../admin/assets/img/admin_img/default.png";

        // Hashear la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertar los datos en la base de datos
        $query = "INSERT INTO admin (nombre, apellido, contraseña, email, foto) VALUES ('$name', '$lastname', '$hashed_password', '$email', '$foto')";
        if (mysqli_query($conn, $query)) {
          $success_message = "Registro exitoso para , $name!";
        } else {
          $error_message = "Error al registrar el usuario: " . mysqli_error($conn);
        }
      }
    }
  }
}

?>


<div class="container">
    <div class="row border-bottom pt-1 mt-2 mb-5" style="width: 100%; height: 100%;">
      <div class="mx-auto" style="display: flex; align-items: center;">
        <div class="modal-body px-md-5 px-4">
          <h3 class="modal-title mt-4 mb-0 text-center mb-5">Registrar</h3>
          <div class="form-password-alert" id="form-password-alert"></div>
          <?php
          if (isset($error_message)) {
            echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
          } elseif (isset($success_message)) {
            echo '<div class="alert alert-success" role="alert">' . $success_message . '</div>';
          }
          ?>
          <p class="font-size-sm text-muted text-center">Crea una nueva cuenta de administrador proporcionando los siguientes detalles.</p>
          <form class="needs-validation" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onsubmit="return validatePassword();" novalidate>
            <div class="form-group">
              <label for="register-name">Nombre</label>
              <input type="text" class="form-control" id="register-name" name="name" placeholder="Tu nombre" required>
            </div> <br>
            <div class="form-group">
              <label for="register-lastname">Apellido</label>
              <input type="text" class="form-control" id="register-lastname" name="lastname" placeholder="Tu apellido" required>
            </div><br>
            <div class="form-group">
              <label for="register-email">Correo electrónico</label>
              <input type="email" class="form-control" id="register-email" name="email" placeholder="Tu correo electrónico" required>
            </div><br>

            <div class="form-group">
              <label for="register-password" class="form-label">Contraseña</label>
              <div class="input-group">
                <input type="password" class="form-control" id="register-password" name="password" placeholder="Tu contraseña" required>
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('register-password')">
                    <i class="fa fa-eye"></i>
                  </button>
                </div>
              </div>
            </div> <br>

            <div class="form-group">
              <label for="register-confirm-password" class="form-label">Confirmar contraseña</label>
              <div class="input-group">
                <input type="password" class="form-control appended-form-control" id="register-confirm-password" name="confirm_password" placeholder="Confirmar tu contraseña" required>
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('register-confirm-password')">
                    <i class="fa fa-eye"></i>
                  </button>
                </div>
              </div>
            </div> <br>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
            
          </form>
        </div>
      </div>
    </div>
</div> <br><br><br>
  



<script src="../../admin/assets/js/register.js"></script>
  <?php include '../template/footer_admin.php'; ?>