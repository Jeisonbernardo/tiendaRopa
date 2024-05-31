<?php
// Incluir el archivo de conexión
include 'conexion.php';

session_start();

// Verificar si ya se ha iniciado sesión y hay un token almacenado
if (isset($_SESSION['token'])) {
    header('Location: index.php');
    exit;
}

// Verificar si se ha enviado el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $email = $_POST['signin-email'];
    $password = $_POST['signin-password'];

    // Consultar la base de datos para verificar el inicio de sesión
    $query = "SELECT * FROM admin WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $stored_password = $row['contraseña'];

        // Verificar la contraseña
        if (password_verify($password, $stored_password)) {
            // Inicio de sesión exitoso, generar y almacenar el token en la sesión
            $token = bin2hex(random_bytes(32));
            $_SESSION['token'] = $token;

            // Actualizar el token en la columna "token" de la tabla "admin"
            $query = "UPDATE admin SET token='$token' WHERE email='$email'";
            mysqli_query($conn, $query);

            // Redireccionar al usuario a index.php
            header('Location: index.php');
            exit;
        } else {
            $error_message = "Error: La contraseña es incorrecta. Por favor, verifica la contraseña e intenta nuevamente.";
        }
    } else {
        $error_message = "Error: El correo electrónico no está registrado. Por favor, intenta con otro correo.";
    }
}

// Incluir la vista HTML
include 'vistas/login.php';
?>
