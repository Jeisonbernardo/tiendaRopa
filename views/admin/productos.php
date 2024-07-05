<?php include '../template/navbar_admin.php'; ?>
<?php include 'ver_productos.php'; ?>


<!-- Page title-->
<div class="container">
<div class="border-bottom pt-1 mt-2 mb-5">
    <h1 class="mt-2 mt-md-4 mb-3 pt-5">PRODUCTOS</h1>
    <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
        <p class="text-muted">Este módulo lista todos los productos de la tienda</p>
        <p class="font-size-sm font-weight-medium pl-md-4"><a class="text-nowrap" href="./agregar_productos.php" rel="noopener">Registrar nuevo producto<i class="cxi-angle-right font-size-base align-middle ml-1"></i></a></p>
    </div>
</div>

<div id="confirmationDialog" class="modal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeConfirmationDialog()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este producto?
            </div>
            <div class="modal-footer">
                <button id="cancelButton" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="deleteButton" type="button" class="btn btn-danger">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<div class="card box-shadow-sm">
    

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="tableproductos">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Imagen</th>
                        <th>Categoría</th>
                        <th>Subcategoría</th>
                        <th>Marca</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="ProductosTableBody">
                    <?php
                    // Consultar todos los productos con la información necesaria
                    // Consultar todas las subcategorías con el nombre de la categoría a la que pertenecen
                    include '../../config/conexion.php';
                    $query = "SELECT p.id, p.nombre AS producto, COALESCE(c.nombre, 'Sin definir') AS categoria, s.nombre AS subcategoria, m.nombre AS marca, p.imagenes FROM producto p 
                    LEFT JOIN subcategoria s ON p.subcategoria_id = s.id 
                    LEFT JOIN marca m ON p.marca_id = m.id 
                    LEFT JOIN categoria c ON s.categoria_id = c.id";

                    // Variable para contar el número de productos
                    $contadorProductos = 0;

                    // Verificar si se ha enviado el formulario de búsqueda
                    if (isset($_POST['buscar_producto'])) {
                        $nombre = $_POST['nombre'];
                        $nombre = mysqli_real_escape_string($conn, $nombre);
                        $query .= " WHERE p.nombre LIKE '%$nombre%'";
                    }

                    $result = mysqli_query($conn, $query);

                    // Verificar si hay productos
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Incrementar el contador de productos
                            $contadorProductos++;
                            echo '<tr>';
                            echo '<th scope="row">' . $contadorProductos . '</th>';
                            echo '<td>' . $row['producto'] . '</td>';

                            $imagen_url = str_replace(' ', '_', $row['producto']);
                            $imagen_path = '../../admin/assets/img/productos_img/' . $imagen_url . '/' . $imagen_url . '_' . '1' . '.png';

                            // Verificar si la imagen existe en el directorio
                            if (file_exists($imagen_path)) {
                                echo '<td><img src="' . $imagen_path . '" width="50" height="50" alt="' . $row['producto'] . '"></td>';
                            } else {
                                // Si la imagen no existe, mostrar la imagen predeterminada
                                echo '<td><img src="../../admin/assets/img/productos_img/default.png" width="50" height="50" alt="' . $row['producto'] . '"></td>';
                            }

                            echo '<td>' . $row['categoria'] . '</td>';
                            echo '<td>' . $row['subcategoria'] . '</td>';
                            echo '<td>' . $row['marca'] . '</td>';
                            echo '<td>';

                            echo '<button class="btn btn-outline-secondary btn-sm editar-producto" data-id="' . $row['id'] . '">Editar</button>';
                            echo '<form method="post" style="display: inline-block;">';
                            echo '<input type="hidden" name="producto_id" value="' . $row['id'] . '">';

                            // Mostrar la ventana emergente personalizada al hacer clic en el botón "Eliminar"
                            echo '<button type="button" class="btn btn-outline-danger btn-sm" onclick="showConfirmationDialog(' . $row['id'] . ')">Eliminar</button>';

                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7">No se encontraron productos.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div> <br><br><br>


<script src="../../admin/assets/js/productos.js"></script>
<?php include '../template/footer_admin.php'; ?>