<?php
include 'conexion.php'; // Incluir el archivo de conexión a la base de datos

if (isset($_POST['buscar_subcategoria'])) {
    $nombre = $_POST['nombre'];

    // Escapar el valor del nombre para evitar problemas de seguridad
    $nombre = mysqli_real_escape_string($conn, $nombre);

    // Realizar la consulta para buscar subcategorías que coincidan con el nombre
    $query = "SELECT s.id, s.nombre AS subcategoria, COALESCE(c.nombre, 'Sin definir') AS categoria FROM subcategoria s LEFT JOIN categoria c ON s.categoria_id = c.id WHERE s.nombre LIKE '%$nombre%'";
    $result = mysqli_query($conn, $query);

    // Crear un array para almacenar las subcategorías encontradas
    $subcategorias = array();

    // Verificar si hay subcategorías
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Agregar cada subcategoría al array
            $subcategorias[] = $row;
        }
    }

    // Devolver el array de subcategorías como respuesta JSON
    echo json_encode($subcategorias);
    exit(); // Terminar la ejecución del script después de enviar la respuesta JSON
}

// El resto del código de "ver_subcategorias.php" para mostrar las categorías en la página:

// Iniciar la sesión
session_start();
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">';

// Verificar si se ha enviado el formulario para eliminar la categoría
if (isset($_POST['eliminar_subcategoria'])) {
    $subcategoria_id = $_POST['subcategoria_id'];

    // Realizar la consulta para eliminar la categoría de la base de datos
    $query = "DELETE FROM subcategoria WHERE id = $subcategoria_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Mostrar mensaje de éxito si la categoría se eliminó correctamente
        echo '<script src="assets/js/iziToast.min.js"></script>';
        echo '<link rel="stylesheet" href="assets/css/iziToast.min.css">';
        echo '<script>
                window.onload = function() {
                    iziToast.success({
                        title: "Éxito",
                        message: "subcategoría eliminada correctamente.",
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
                        message: "Hubo un problema al eliminar la subcategoría.",
                        position: "topRight"
                    });
                };
            </script>';
    }
}


include 'Vistas/subCategoria.php';
?>