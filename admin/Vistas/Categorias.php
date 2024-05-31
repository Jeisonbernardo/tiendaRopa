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
    <div class="card-header">
        <h5 style="margin-bottom: 0px;">Filtro de categorias</h5>
    </div>

    <div class="card-body">
        <form class="form-inline pt-2" id="searchForm">
            <input class="form-control mb-3 mr-sm-4" type="text" id="inline-form-input-name" placeholder="Buscar por nombre">
            <div class="input-group-append">
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
                        <th>Imagen</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="categoriasTableBody"> <!-- Agrega el atributo id para identificar el tbody -->
                    <?php
                    // Consultar todas las categorías desde la base de datos
                    include 'conexion.php'; // Asegúrate de incluir el archivo de conexión
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
    function showConfirmationDialog(categoriaId) {
        var confirmationDialog = document.getElementById("confirmationDialog");
        confirmationDialog.style.display = "block";

        var deleteButton = document.getElementById("deleteButton");
        deleteButton.onclick = function() {
            eliminarCategoria(categoriaId);
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

    function eliminarCategoria(categoriaId) {
        var form = document.createElement("form");
        form.setAttribute("method", "post");
        form.style.display = "none";

        var input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", "categoria_id");
        input.setAttribute("value", categoriaId);

        var inputEliminar = document.createElement("input");
        inputEliminar.setAttribute("type", "hidden");
        inputEliminar.setAttribute("name", "eliminar_categoria");
        inputEliminar.setAttribute("value", "eliminar");

        form.appendChild(input);
        form.appendChild(inputEliminar);
        document.body.appendChild(form);
        form.submit();
    }
</script>

<script>
    // Función para realizar la búsqueda por AJAX y actualizar el tbody
    function searchCategorias() {
        var searchTerm = document.getElementById("inline-form-input-name").value;

        $.ajax({
            url: "ver_categorias.php",
            type: "POST",
            data: {
                buscar_categoria: true,
                nombre: searchTerm
            },
            dataType: "json",
            success: function(data) {
                var tbody = document.getElementById("categoriasTableBody");
                tbody.innerHTML = "";

                if (data.length > 0) {
                    // Inicializar un contador para mostrar el número de categoría en orden ascendente
                    var contadorCategorias = 1;

                    for (var i = 0; i < data.length; i++) {
                        var categoria = data[i];
                        var row = '<tr>' +
                            '<th scope="row">' + contadorCategorias + '</th>' +
                            '<td>' + categoria.nombre + '</td>' +
                            '<td><img src="' + categoria.imagen + '" alt="Imagen de categoria" width="50"></td>' +
                            '<td>' +
                            '<button class="btn btn-outline-secondary btn-sm editar-categoria" data-id="' + categoria.id + '">Editar</button>' +
                            '<form method="post" style="display: inline-block;">' +
                            '<input type="hidden" name="categoria_id" value="' + categoria.id + '">' +
                            '<button type="button" class="btn btn-outline-danger btn-sm" onclick="showConfirmationDialog(' + categoria.id + ')">Eliminar</button>' +
                            '</form>' +
                            '</td>' +
                            '</tr>';

                        tbody.innerHTML += row;

                        // Incrementar el contador de categorías
                        contadorCategorias++;
                    }
                } else {
                    tbody.innerHTML = '<tr><td colspan="4">No se encontraron categorías.</td></tr>';
                }
            }
        });
    }

    // Evento para realizar la búsqueda al escribir en el campo de búsqueda
    document.getElementById("inline-form-input-name").addEventListener("input", function() {
        searchCategorias();
    });

    // Capturar el evento de clic en los botones "Editar"
    document.addEventListener("click", function(event) {
        if (event.target.classList.contains("editar-categoria")) {
            // Obtener el ID de la categoría desde el atributo "data-id"
            var categoriaId = event.target.getAttribute("data-id");

            // Redirigir al usuario a la página de edición correspondiente
            window.location.href = "editar_categorias.php?id=" + categoriaId;
        }
    });
</script>

<!-- Main theme script-->
<script src="assets/js/theme.min.js"></script>
</body>

</html>