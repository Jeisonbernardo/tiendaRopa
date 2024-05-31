<?php
include 'Vistas/template/navbar.php'; // Incluir el archivo de conexión a la base de datos

?>

<style>
    /* Estilos para el contenedor de la ventana emergente */
    #confirmationDialog {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1050;
        display: none;
        width: 100%;
        height: 100%;
        overflow: hidden;
        outline: 0;
    }

    /* Estilos para el botón "Eliminar" */
    #deleteButton {
        margin-left: 10px;
    }
</style>

<!-- Page title-->
<div class="border-bottom pt-5 mt-2 mb-5">
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
    <div class="card-header">
        <h5 style="margin-bottom: 0px;">Filtro de subcategorias</h5>
    </div>

    <div class="card-body">
        <form class="form-inline pt-2" id="searchForm">
            <input class="form-control mb-3 mr-sm-4" type="text" id="inline-form-input-name" placeholder="Buscar por nombre">
            <span class="input-group-text" style="background-color: transparent; border: none; padding: 0;">
                <i class="cxi-search font-size-sm" style="
                            padding-left: 10px;
                            padding-bottom: 15px;
                        "></i>
            </span>
    </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered">
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
                // Consultar todas las subcategorías con el nombre de la categoría a la que pertenecen
                include 'conexion.php';
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

<div class="card-footer">
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item">
                <a href="#" class="page-link">
                    <<<< /a>
            </li>
            <li class="page-item d-none d-sm-block">
                <a href="#" class="page-link">1</a>
            </li>
            <li class="page-item">
                <a href="#" class="page-link">>>></a>
            </li>
        </ul>
    </nav>
</div>
</div>

</div>
</section>
</main>

<!-- Back to top button-->
<a class="btn-scroll-top" href="#top" data-scroll data-fixed-element>
    <span class="btn-scroll-top-tooltip text-muted font-size-sm mr-2">Top</span>
    <i class="btn-scroll-top-icon cxi-angle-up"></i>
</a>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/simplebar/dist/simplebar.min.js"></script>
<script src="assets/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

<script>
    function showConfirmationDialog(subcategoriaid) {
        var confirmationDialog = document.getElementById("confirmationDialog");
        confirmationDialog.style.display = "block";

        var deleteButton = document.getElementById("deleteButton");
        deleteButton.onclick = function() {
            eliminarsubcategoria(subcategoriaid);
        };

        var cancelButton = document.getElementById("cancelButton");
        cancelButton.onclick = function() {
            confirmationDialog.style.display = "none";
        };
    }

    function closeConfirmationDialog() {
        var confirmationDialog = document.getElementById("confirmationDialog");
        confirmationDialog.style.display = "none";
    }

    function eliminarsubcategoria(subcategoriaid) {
        var form = document.createElement("form");
        form.setAttribute("method", "post");
        form.style.display = "none";

        var input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", "subcategoria_id");
        input.setAttribute("value", subcategoriaid);

        var inputEliminar = document.createElement("input");
        inputEliminar.setAttribute("type", "hidden");
        inputEliminar.setAttribute("name", "eliminar_subcategoria");
        inputEliminar.setAttribute("value", "eliminar");

        form.appendChild(input);
        form.appendChild(inputEliminar);
        document.body.appendChild(form);
        form.submit();
    }
</script>

<script>
    function searchsubcategorias() {
        var searchTerm = document.getElementById("inline-form-input-name").value;

        $.ajax({
            url: "ver_subcategorias.php",
            type: "POST",
            data: {
                buscar_subcategoria: true,
                nombre: searchTerm
            },
            dataType: "json",
            success: function(data) {
                var tbody = document.getElementById("SubcategoriasTableBody");
                tbody.innerHTML = "";

                if (data.length > 0) {

                    var contadorsubcategorias = 1;

                    for (var i = 0; i < data.length; i++) {
                        var subcategoria = data[i];
                        var row = '<tr>' +
                            '<th scope="row">' + contadorsubcategorias + '</th>' +
                            '<td>' + subcategoria.subcategoria + '</td>' +
                            '<td>' + (subcategoria.categoria ? subcategoria.categoria : 'Sin definir') + '</td>' +
                            '<td>' +
                            '<button class="btn btn-outline-secondary btn-sm editar-subcategoria" data-id="' + subcategoria.id + '">Editar</button>' +
                            '<form method="post" style="display: inline-block;">' +
                            '<input type="hidden" name="subcategoria_id" value="' + subcategoria.id + '">' +
                            '<button type="button" class="btn btn-outline-danger btn-sm" onclick="showConfirmationDialog(' + subcategoria.id + ')">Eliminar</button>' +
                            '</form>' +
                            '</td>' +
                            '</tr>';

                        tbody.innerHTML += row;

                        // Incrementar el contador de categorías
                        contadorsubcategorias++;
                    }
                } else {
                    tbody.innerHTML = '<tr><td colspan="4">No se encontraron subcategorías.</td></tr>';
                }
            },
            error: function() {
                // Manejar errores en caso de que ocurra alguno durante la solicitud AJAX
                var tbody = document.getElementById("SubcategoriasTableBody");
                tbody.innerHTML = '<tr><td colspan="4">Error al cargar los datos.</td></tr>';
            }
        });
    }

    // Evento para realizar la búsqueda al escribir en el campo de búsqueda
    document.getElementById("inline-form-input-name").addEventListener("input", function() {
        searchsubcategorias();
    });

    // Capturar el evento de clic en los botones "Editar"
    document.addEventListener("click", function(event) {
        if (event.target.classList.contains("editar-subcategoria")) {
            // Obtener el ID de la categoría desde el atributo "data-id"
            var subcategoriaid = event.target.getAttribute("data-id");

            // Redirigir al usuario a la página de edición correspondiente
            window.location.href = "editar_subcategorias.php?id=" + subcategoriaid;
        }
    });
</script>

<!-- Main theme script-->
<script src="assets/js/theme.min.js"></script>

</body>

</html>