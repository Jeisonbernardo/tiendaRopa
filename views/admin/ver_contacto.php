<?php include '../../config/config.php';
include BASE_PATH . 'config/conexion.php'; ?>

<?php
if (isset($_POST['buscar_contacto'])) {
    $nombre = $_POST['nombre'];

    // Escapar el valor del nombre para evitar problemas de seguridad
    $nombre = mysqli_real_escape_string($conn, $nombre);

    // Realizar la consulta para buscar contactos que coincidan con el nombre
    $query = "SELECT * FROM contacto";

    $result = mysqli_query($conn, $query);

    // Crear un array para almacenar los contactos encontrados
    $contactos = array();

    // Verificar si hay contactos
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $contactos[] = $row;
        }
    }

    // Devolver el array de contactos  como respuesta JSON
    echo json_encode($contactos);
    exit(); // Terminar la ejecución del script después de enviar la respuesta JSON
}


echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">';

// Verificar si se ha enviado el formulario para eliminar la categoría
if (isset($_POST['eliminar_contacto'])) {
    $contacto_id = $_POST['id_contacto'];

    // Realizar la consulta para eliminar el producto de la base de datos
    $query = "DELETE FROM contacto WHERE id_contacto = $contacto_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Mostrar mensaje de éxito si el producto se eliminó correctamente
        echo '<script src="../../admin/assets/js/iziToast.min.js"></script>';
        echo '<link rel="stylesheet" href="../../admin/assets/css/iziToast.min.css">';
        echo '<script>
                window.onload = function() {
                    iziToast.success({
                        title: "Éxito",
                        message: "Contacto eliminado correctamente.",
                        position: "topRight"
                    });
                };
            </script>';
    } else {
        // Mostrar mensaje de error si hubo un problema al eliminar el producto
        echo '<script src="../../admin/assets/js/iziToast.min.js"></script>';
        echo '<link rel="stylesheet" href="../../admin/assets/css/iziToast.min.css">';
        echo '<script>
                window.onload = function() {
                    iziToast.error({
                        title: "Error",
                        message: "Hubo un problema al eliminar el contactoo.",
                        position: "topRight"
                    });
                };
            </script>';
    }

}
?>