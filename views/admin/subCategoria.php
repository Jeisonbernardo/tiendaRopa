<?php include '../template/navbar_admin.php'; ?>
<?php include 'ver_subcategorias.php'; ?>

<?php

?>

<div class="container">
    <div class="border-bottom pt-1 mt-2 mb-5">
        <h1 class="mt-2 mt-md-4 mb-3 pt-5">SUBCATEGORÍAS</h1>
        <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
            <p class="text-muted">Este módulo lista todas las subcategorías de la tienda</p>
            <p class="font-size-sm font-weight-medium pl-md-4"><a class="text-nowrap" href="./agregar_subcategorias.php" rel="noopener">Registrar nueva subcategoría<i class="cxi-angle-right font-size-base align-middle ml-1"></i></a></p>
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
                    ¿Estás seguro de que deseas eliminar esta subcategoría?
                </div>
                <div class="modal-footer">
                    <button id="cancelButton" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button id="deleteButton" type="button" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card box-shadow-sm">
        </form>
        <div class="table-responsive">
            <table class="table table-bordered" id="tablesubcategorias">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="SubcategoriasTableBody">
                    <?php
                    include '../../config/conexion.php';
                    $query = "SELECT s.id, s.nombre AS subcategoria, COALESCE(c.nombre, 'Sin definir') AS categoria FROM subcategoria s LEFT JOIN categoria c ON s.categoria_id = c.id";

                    // Variable para contar el número de categorías
                    $contadorsubcategorias = 0;

                    // Verificar si se ha enviado el formulario de búsqueda
                    if (isset($_POST['buscar_subcategoria'])) {
                        $nombre = $_POST['nombre'];
                        $nombre = mysqli_real_escape_string($conn, $nombre);
                        $query .= " WHERE s.nombre LIKE '%$nombre%'";
                    }

                    $result = mysqli_query($conn, $query);

                    // Verificar si hay subcategorías
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Incrementar el contador de categorías
                            $contadorsubcategorias++;
                            echo '<tr>';
                            echo '<th scope="row">' . $contadorsubcategorias . '</th>';
                            echo '<td>' . $row['subcategoria'] . '</td>';
                            echo '<td>' . $row['categoria'] . '</td>';
                            echo '<td>';

                            echo '<button class="btn btn-outline-secondary btn-sm editar-subcategoria" data-id="' . $row['id'] . '">Editar</button>';
                            echo '<form method="post" style="display: inline-block;">';
                            echo '<input type="hidden" name="subcategoria_id" value="' . $row['id'] . '">';

                            // Mostrar la ventana emergente personalizada al hacer clic en el botón "Eliminar"
                            echo '<button type="button" class="btn btn-outline-danger btn-sm" onclick="showConfirmationDialog(' . $row['id'] . ')">Eliminar</button>';

                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4">No se encontraron subcategorías.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div> <br><br><br>

<script src="../../admin/assets/js/subcategorias.js"></script>
<?php include '../template/footer_admin.php'; ?>