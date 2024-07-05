<?php include '../template/navbar_admin.php'; ?>
<?php include 'ver_categorias.php'; ?>

<link rel="stylesheet" href="../../admin/assets/css/categorias.css">

<div class="container">
<div class="border-bottom pt-1 mt-2 mb-5">
    <h1 class="mt-2 mt-md-4 mb-3 pt-5">CATEGORÍAS</h1>
    <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
        <p class="text-muted">Este módulo lista todas las categorías de la tienda</p>
        <p class="font-size-sm font-weight-medium pl-md-4"><a class="text-nowrap" href="./agregar_categorias.php" rel="noopener">Registrar nueva categoría<i class="cxi-angle-right font-size-base align-middle ml-1"></i></a></p>
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
                ¿Estás seguro de que deseas eliminar esta categoría?
            </div>
            <div class="modal-footer">
                <button id="cancelButton" type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeConfirmationDialog()">Cancelar</button>
                <button id="deleteButton" type="button" class="btn btn-danger" onclick="performDelete()">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<div class="card box-shadow-sm">
    

    <div class="card-body">
        <div class="table-responsive ">
            <table class="table table-bordered" id="myTable" class="display">
                <thead>
                    <tr> 
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Imagen</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="categoriasTableBody"> <!-- Agrega el atributo id para identificar el tbody -->
                    <?php
                    // Consultar todas las categorías desde la base de datos
                    include '../../config/conexion.php'; // Asegúrate de incluir el archivo de conexión
                    $query = "SELECT id, nombre, imagen FROM categoria";
                    $result = mysqli_query($conn, $query);

                    // Variable para contar el número de categorías
                    $contadorCategorias = 0;

                    // Verificar si hay categorías
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Incrementar el contador de categorías
                            $contadorCategorias++;
                            echo '<tr>';
                            echo '<th scope="row">' . $contadorCategorias . '</th>'; // Mostrar el número de categoría
                            echo '<td>' . $row['nombre'] . '</td>';
                            // Mostrar la imagen de la categoria en la tabla
                            echo '<td><img src="' . $row['imagen'] . '" width="50" height="50" alt="' . $row['nombre'] . '"></td>';
                            echo '<td>';


                            echo '<button class="btn btn-outline-secondary btn-sm editar-categoria" data-id="' . $row['id'] . '">Editar</button>';
                            echo '<form method="post" style="display: inline-block;">';
                            echo '<input type="hidden" name="categoria_id" value="' . $row['id'] . '">';

                            // Mostrar la ventana emergente personalizada al hacer clic en el botón "Eliminar"
                            echo '<button type="button" class="btn btn-outline-danger btn-sm" onclick="showConfirmationDialog(' . $row['id'] . ')">Eliminar</button>';

                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        // Si no hay categorías, mostrar un mensaje
                        echo '<tr><td colspan="4">No se encontraron categorías.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div> <br><br><br>


<script src="../../admin/assets/js/categorias.js"></script>
<?php include '../template/footer_admin.php'; ?>