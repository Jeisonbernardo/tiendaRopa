<?php include '../template/navbar_admin.php'; ?>
<?php include 'ver_contacto.php'; ?>

<div class="container"> 
<div class="border-bottom pt-1 mt-2 mb-5">
    <h1 class="mt-2 mt-md-4 mb-3 pt-5">CONTACTOS</h1>
    <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
        <p class="text-muted">Este módulo lista todos los contactosde la tienda</p>
        <p class="font-size-sm font-weight-medium pl-md-4"><a class="text-nowrap" href="#" rel="noopener">Contactos<i class="cxi-angle-right font-size-base align-middle ml-1"></i></a></p>
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
                ¿Estás seguro de que deseas eliminar este contacto?
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
            <table  class="table table-bordered" id="tablecontacto">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>apellido</th>
                        <th>email</th>
                        <th>telefono</th>
                        <th>mensaje</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody >
                    <?php
                    // Consultar todos los productos con la información necesaria
                    // Consultar todas las subcategorías con el nombre de la categoría a la que pertenecen
                    $query = "SELECT * FROM contacto";

                    // Variable para contar el número de productos
                    $contadorProductos = 0;

                    // Verificar si se ha enviado el formulario de búsqueda
                    if (isset($_POST['buscar_contacto'])) {
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
                            echo '<td>' . $row['nombre'] . '</td>';
                      

                            echo '<td>' . $row['apellido'] . '</td>';
                            echo '<td>' . $row['email'] . '</td>';
                            echo '<td>' . $row['telefono'] . '</td>';
                            echo '<td>' . $row['mensaje'] . '</td>';
                            echo '<td>';

                            //echo '<button class="btn btn-outline-secondary btn-sm editar-producto" data-id="' . $row['id_contacto'] . '">Editar</button>';
                            //echo '<form method="post" style="display: inline-block;">';
                            //echo '<input type="hidden" name="producto_id" value="' . $row['id_contacto'] . '">';

                            // Mostrar la ventana emergente personalizada al hacer clic en el botón "Eliminar"
                            echo '<button type="button" class="btn btn-outline-danger btn-sm" onclick="showConfirmationDialog(' . $row['id_contacto'] . ')">Eliminar</button>';

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

<script src="../../admin/assets/js/contacto_admin.js"></script>
<?php include '../template/footer_admin.php'; ?>
