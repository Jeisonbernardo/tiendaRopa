<?php include '../../config/config.php';
include BASE_PATH . 'config/conexion.php'; ?>
<?php

if (isset($_POST['buscar_categoria'])) {
    $nombre = $_POST['nombre'];

    // Escapar el valor del nombre para evitar problemas de seguridad
    $nombre = mysqli_real_escape_string($conn, $nombre);

    // Realizar la consulta para buscar categorías que coincidan con el nombre
    $query = "SELECT id, nombre, imagen FROM categoria WHERE nombre LIKE '%$nombre%'";
    $result = mysqli_query($conn, $query);

    // Crear un array para almacenar las categorías encontradas
    $categorias = array();

    // Verificar si hay categorías
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Agregar cada categoría al array
            $categorias[] = $row;
        }
    }

    // Devolver el array de categorías como respuesta JSON
    echo json_encode($categorias);
    exit(); // Terminar la ejecución del script después de enviar la respuesta JSON
}

echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">';

// Verificar si se ha enviado el formulario para eliminar la categoría
if (isset($_POST['eliminar_categoria'])) {
    $categoria_id = $_POST['categoria_id'];

    // Realizar la consulta para eliminar la categoría de la base de datos
    $query = "DELETE FROM categoria WHERE id = $categoria_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Mostrar mensaje de éxito si la categoría se eliminó correctamente
        echo '<script src="../../admin/assets/js/iziToast.min.js"></script>';
        echo '<link rel="stylesheet" href="../../admin/assets/css/iziToast.min.css">';
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
        echo '<script src="../../admin/assets/js/iziToast.min.js"></script>';
        echo '<link rel="stylesheet" href="../../admin/assets/css/iziToast.min.css">';
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


?>
