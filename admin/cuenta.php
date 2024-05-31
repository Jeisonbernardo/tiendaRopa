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

  // Obtener el token del usuario actual
  $token = $_SESSION['token'];

  // Realizar la consulta SQL para obtener los datos del usuario
  $query = "SELECT nombre, apellido, email, foto FROM admin WHERE token='$token'";
  $result = mysqli_query($conn, $query);

  // Verificar si se encontraron datos del usuario
  if (mysqli_num_rows($result) > 0) {
    // Obtener los datos del usuario y almacenarlos en variables
    $row = mysqli_fetch_assoc($result);
    $nombreUsuario = $row['nombre'];
    $apellidoUsuario = $row['apellido'];
    $emailUsuario = $row['email'];
    $fotoPerfil = $row['foto'];

    // Procesar el formulario de modificación de datos
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos ingresados por el usuario
      $nuevoNombre = $_POST['nombre'];
      $nuevoApellido = $_POST['apellido'];
      $nuevoEmail = $_POST['correo'];
      $nuevaContrasena = $_POST['password'];
      $confirmarContrasena = $_POST['confirm_password'];

      // Procesar la imagen de perfil solo si se ha seleccionado una nueva
      if (isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
        $fotoPermitida = array('jpg', 'jpeg', 'png', 'gif'); // Extensiones de imagen permitidas
        $fotoNombre = $_FILES['foto']['name'];
        $fotoTipo = $_FILES['foto']['type'];
        $fotoTamano = $_FILES['foto']['size'];
        $fotoTemp = $_FILES['foto']['tmp_name'];

        // Obtener la extensión de la imagen
        $fotoExtension = pathinfo($fotoNombre, PATHINFO_EXTENSION);
        $fotoExtension = strtolower($fotoExtension);

        // Verificar si la extensión de la imagen es válida
        if (in_array($fotoExtension, $fotoPermitida)) {
            // Generar un nuevo nombre único para la imagen
            $nuevoNombreImagen = uniqid('', true) . '.' . $fotoExtension;

            // Ruta de destino para mover la imagen
            $rutaDestino = 'assets/img/admin_img/' . $nuevoNombreImagen;

            // Mover la imagen a la carpeta de destino
            move_uploaded_file($fotoTemp, $rutaDestino);

            // Actualizar la columna 'foto' en la base de datos con la nueva ruta
            $query = "UPDATE admin SET foto='$rutaDestino' WHERE token='$token'";
            mysqli_query($conn, $query);
        }
      }

      // Verificar si se ingresó una nueva contraseña
      if (!empty($nuevaContrasena) && !empty($confirmarContrasena) && $nuevaContrasena === $confirmarContrasena) {
        // Hashear la nueva contraseña
        $hashed_password = password_hash($nuevaContrasena, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $query = "UPDATE admin SET contraseña='$hashed_password' WHERE token='$token'";
        mysqli_query($conn, $query);
      }

      // Actualizar los demás datos del usuario en la base de datos
      $query = "UPDATE admin SET nombre='$nuevoNombre', apellido='$nuevoApellido', email='$nuevoEmail'";

      // Agregar la columna de foto solo si se ha seleccionado una nueva imagen
      if (isset($rutaDestino)) {
          $query .= ", foto='$rutaDestino'";
      }

      $query .= " WHERE token='$token'";

      // Ejecutar la consulta
      $result = mysqli_query($conn, $query);

      // Mostrar mensaje de éxito o error según el resultado de la consulta
      if ($result) {
        echo '<link rel="stylesheet" href="assets/css/iziToast.min.css">';
        echo '<script src="assets/js/iziToast.min.js"></script>';
        echo '<script>
            window.onload = function() {
                iziToast.success({
                    title: "Éxito",
                    message: "Los datos han sido actualizados correctamente.",
                    position: "bottomRight"
                });
            };
        </script>';
      } else {
          echo '<link rel="stylesheet" href="assets/css/iziToast.min.css">';
          echo '<script src="assets/js/iziToast.min.js"></script>';
          echo '<script>
              window.onload = function() {
                  iziToast.warning({
                      title: "Error",
                      message: "Hubo un error al actualizar los datos.",
                      position: "bottomRight"
                  });
              };
          </script>';
      }
    }
  } else {
    // Si no se encontraron datos del usuario, puedes mostrar un mensaje de error o redireccionar a una página de error.
    echo "Error: No se encontraron datos del usuario.";
    exit;
  }

  include 'vistas/cuenta.php';
?>