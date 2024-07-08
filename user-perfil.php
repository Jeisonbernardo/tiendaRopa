<?php
include 'conexion.php';
include 'navbar.php';

if (!isset($_SESSION['user_token'])) {
    echo '<div class="border-bottom text-center"><br><br><br>';
    echo '<h5 class="text-muted">"No se encontró usuario con sesión iniciada."</h5></div>';
    exit();
}

// Obtener el token del usuario actual
$token = $_SESSION['user_token'];

// Realizar la consulta SQL para obtener los datos del usuario
$query = "SELECT nombre, apellido, email FROM cliente WHERE token=?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    // Obtener los datos del usuario y almacenarlos en variables
    $row = mysqli_fetch_assoc($result);
    $nombreUsuario = $row['nombre'];
    $apellidoUsuario = $row['apellido'];
    $emailUsuario = $row['email'];

    // Procesar el formulario de modificación de datos
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos ingresados por el usuario
        $nuevoNombre = $_POST['nombre'];
        $nuevoApellido = $_POST['apellido'];
        $nuevoEmail = $_POST['correo'];
        $nuevaContrasena = $_POST['password'];
        $confirmarContrasena = $_POST['confirm_password'];

        // Validar y actualizar la contraseña si se ingresó una nueva y las contraseñas coinciden
        if (!empty($nuevaContrasena) && !empty($confirmarContrasena) && $nuevaContrasena === $confirmarContrasena) {
            $hashed_password = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
            $query = "UPDATE cliente SET contraseña=?, confirmar_contraseña=? WHERE token=?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sss", $hashed_password, $hashed_password, $token);
            mysqli_stmt_execute($stmt);
        }

        // Actualizar otros datos del usuario
        $query = "UPDATE cliente SET nombre=?, apellido=?, email=? WHERE token=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $nuevoNombre, $nuevoApellido, $nuevoEmail, $token);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo '<link rel="stylesheet" href="../../admin/assets/css/iziToast.min.css">';
            echo '<script src="../../admin/assets/js/iziToast.min.js"></script>';
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
            echo '<link rel="stylesheet" href="../../admin/assets/css/iziToast.min.css">';
            echo '<script src="../../admin/assets/js/iziToast.min.js"></script>';
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
?>

<!-- Aquí comienza el HTML -->
<div class="container mt-5">
    <div class="border-bottom text-center">
        <h1 class="mt-2 mt-md-4 mb-3 pt-3">TU PERFIL</h1>
        <p class="text-muted">Aquí puedes modificar tus datos</p>
    </div>

    <div class="card box-shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 style="margin-bottom: 0px;">MODIFICAR DATOS</h5>
        </div>

        <div class="card-body">
            <form id="updateForm" method="POST" enctype="multipart/form-data" onsubmit="return validatePasswords(event)">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombreUsuario); ?>" placeholder="Ingresa tu nombre" required>
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($apellidoUsuario); ?>" placeholder="Ingresa tu apellido" required>
                </div>

                <div class="form-group">
                    <label for="correo">Correo electrónico:</label>
                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($emailUsuario); ?>" placeholder="Ingresa tu correo electrónico" required>
                </div>

                <div class="form-group">
                    <label for="register-password" class="form-label">Nueva contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="register-password" name="password" placeholder="Ingresa tu contraseña">
                    </div>
                </div>

                <div class="form-group">
                    <label for="register-confirm-password" class="form-label">Confirmar nueva contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="register-confirm-password" name="confirm_password" placeholder="Confirma tu nueva contraseña">
                    </div>
                </div>

                <button href="user-perfil.php" type="submit" class="btn btn-primary">Actualizar Perfil</button>
            </form>
        </div>
    </div>
</div><br><br><br><br><br>

<?php include 'footer.php'; ?>