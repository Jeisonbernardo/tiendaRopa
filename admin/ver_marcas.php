<?php
include 'conexion.php'; // Incluir el archivo de conexión a la base de datos

if (isset($_POST['buscar_marca'])) {
    $nombre = $_POST['nombre'];

    // Escapar el valor del nombre para evitar problemas de seguridad
    $nombre = mysqli_real_escape_string($conn, $nombre);

    // Realizar la consulta para buscar marcas que coincidan con el nombre
    $query = "SELECT id, nombre, imagen FROM marca WHERE nombre LIKE '%$nombre%'";
    $result = mysqli_query($conn, $query);

    // Crear un array para almacenar las marcas encontradas
    $marcas = array();

    // Verificar si hay marcas
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Agregar cada marca al array
            $marcas[] = $row;
        }
    }

    // Devolver el array de marcas como respuesta JSON
    echo json_encode($marcas);
    exit(); // Terminar la ejecución del script después de enviar la respuesta JSON
}



// Iniciar la sesión
session_start();
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">';

// Verificar si se ha enviado el formulario para eliminar la categoría
if (isset($_POST['eliminar_marca'])) {
    $marca_id = $_POST['marca_id'];

    // Realizar la consulta para eliminar la categoría de la base de datos
    $query = "DELETE FROM marca WHERE id = $marca_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Mostrar mensaje de éxito si la categoría se eliminó correctamente
        echo '<script src="assets/js/iziToast.min.js"></script>';
        echo '<link rel="stylesheet" href="assets/css/iziToast.min.css">';
        echo '<script>
                window.onload = function() {
                    iziToast.success({
                        title: "Éxito",
                        message: "Categoría eliminada correctamente.",
                        position: "topRight"
                    });
                };
            </script>';
    } else {
        // Mostrar mensaje de error si hubo un problema al eliminar la categoría
        echo '<script src="assets/js/iziToast.min.js"></script>';
        echo '<link rel="stylesheet" href="assets/css/iziToast.min.css">';
        echo '<script>
                window.onload = function() {
                    iziToast.error({
                        title: "Error",
                        message: "Hubo un problema al eliminar la categoría.",
                        position: "topRight"
                    });
                };
            </script>';
    }
}


include 'vistas/marcas.php';
?>