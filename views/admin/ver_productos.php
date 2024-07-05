<?php include '../../config/config.php';
include BASE_PATH . 'config/conexion.php'; ?>

<?php

if (isset($_POST['buscar_producto'])) {
    $nombre = $_POST['nombre'];

    // Escapar el valor del nombre para evitar problemas de seguridad
    $nombre = mysqli_real_escape_string($conn, $nombre);

    // Realizar la consulta para buscar productos que coincidan con el nombre
    $query = "SELECT p.id, p.nombre AS producto, COALESCE(c.nombre, 'Sin definir') AS categoria, s.nombre AS subcategoria, m.nombre AS marca, p.imagenes FROM producto p 
              LEFT JOIN subcategoria s ON p.subcategoria_id = s.id 
              LEFT JOIN marca m ON p.marca_id = m.id 
              LEFT JOIN categoria c ON s.categoria_id = c.id 
              WHERE p.nombre LIKE '%$nombre%'";

    $result = mysqli_query($conn, $query);

    // Crear un array para almacenar los productos encontrados
    $productos = array();

    // Verificar si hay productos
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Agregar cada producto al array
            $productos[] = $row;
        }
    }

    // Devolver el array de productos como respuesta JSON
    echo json_encode($productos);
    exit(); // Terminar la ejecución del script después de enviar la respuesta JSON
}

// El resto del código de "ver_Subcategorias.php" para mostrar las categorías en la página:


echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">';

// Verificar si se ha enviado el formulario para eliminar la categoría
if (isset($_POST['eliminar_producto'])) {
    $producto_id = $_POST['producto_id'];

    // Realizar la consulta para eliminar el producto de la base de datos
    $query = "DELETE FROM producto WHERE id = $producto_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Mostrar mensaje de éxito si el producto se eliminó correctamente
        echo '<script src="../../admin/assets/js/iziToast.min.js"></script>';
        echo '<link rel="stylesheet" href="../../admin/assets/css/iziToast.min.css">';
        echo '<script>
                window.onload = function() {
                    iziToast.success({
                        title: "Éxito",
                        message: "Producto eliminado correctamente.",
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
                        message: "Hubo un problema al eliminar el producto.",
                        position: "topRight"
                    });
                };
            </script>';
    }
    // Verificar si se seleccionaron tallas
    if (empty($_POST['tallas_ropa']) && empty($_POST['tallas_calzado'])) {
        $error = "Debes seleccionar al menos una talla.";
        $insertarproducto = false; // No insertar el producto en la base de datos
      } else {
        $insertarproducto = true; // Insertar el producto en la base de datos
  
        $tallasSeleccionadas = array(); // Crear un array para las tallas seleccionadas
  
        if (isset($_POST['tallas_ropa']) && is_array($_POST['tallas_ropa'])) {
          $tallasRopa = $_POST['tallas_ropa'];
  
          // Verificar si se eligió ropa y la talla "ropa"
          if (in_array('ropa', $tallasRopa)) {
            $key = array_search('ropa', $tallasRopa);
            unset($tallasRopa[$key]);
            $tallasSeleccionadas[] = 'ropa';
          }
  
          $tallasSeleccionadas = array_merge($tallasSeleccionadas, $tallasRopa);
        }
  
        if (isset($_POST['tallas_calzado']) && !empty($_POST['tallas_calzado'])) {
          $tallasCalzado = $_POST['tallas_calzado'];
          $tallasCalzadoArray = explode(',', $tallasCalzado);
          $tallasSeleccionadas[] = 'calzado';
          $tallasSeleccionadas = array_merge($tallasSeleccionadas, $tallasCalzadoArray);
  
          // Eliminar cualquier duplicado de "calzado"
          $tallasSeleccionadas = array_unique($tallasSeleccionadas);
        }
  
        // Concatenar las tallas seleccionadas en un solo texto, separado por comas
        $tallasSeleccionadas = implode(', ', $tallasSeleccionadas);
      }
}
?>