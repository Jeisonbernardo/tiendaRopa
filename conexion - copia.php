<?php
// Datos de conexión a la base de datos
$servername = 'localhost'; // Cambia esto si tu servidor de base de datos no está en localhost
$username = 'root'; // Reemplaza "tu_usuario" por el nombre de usuario de tu base de datos
$password = 'fikmortvy02479bd'; // Reemplaza "tu_contraseña" por la contraseña de tu base de datos
$dbname = 'tiendaropa'; // Reemplaza "nombre_de_tu_base_de_datos" por el nombre de tu base de datos

// Establecer la conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if (!$conn) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}
?>
