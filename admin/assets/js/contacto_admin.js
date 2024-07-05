$(document).ready(function() {
    $('#tablecontacto').DataTable({
        "language": {
        "decimal": "",
        "emptyTable": "No hay datos disponibles en la tabla",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
        "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
        "infoFiltered": "(filtrado de _MAX_ entradas totales)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "No se encontraron coincidencias",
        "paginate": {
            "first": "Primero",
            "last": "Último",
            "next": "Siguiente",
            "previous": "Anterior"
        },
        "aria": {
            "sortAscending": ": activar para ordenar de manera ascendente",
            "sortDescending": ": activar para ordenar de manera descendente"
        }
    },

         
        dom: 'Bfrtlip',
        responsive: true,

        buttons: [
            {
                extend: 'copy',
                text: '<i class="fas fa-copy"></i> Copiar',
                className: 'btn btn-outline-secondary'
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-outline-success'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-outline-danger'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimir',
                className: 'btn btn-outline-primary'
            }
        ]
    });
});

function showConfirmationDialog(contactoid) {
    var confirmationDialog = document.getElementById("confirmationDialog");
    confirmationDialog.style.display = "block";

    var deleteButton = document.getElementById("deleteButton");
    deleteButton.onclick = function() {
        eliminarproducto(contactoid);
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

function eliminarproducto(contactoid) {
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.style.display = "none";

    var input = document.createElement("input");
    input.setAttribute("type", "hidden");
    input.setAttribute("name", "id_contacto");
    input.setAttribute("value", contactoid);

    var inputEliminar = document.createElement("input");
    inputEliminar.setAttribute("type", "hidden");
    inputEliminar.setAttribute("name", "eliminar_contacto");
    inputEliminar.setAttribute("value", "eliminar");

    form.appendChild(input);
    form.appendChild(inputEliminar);
    document.body.appendChild(form);
    form.submit();
}




