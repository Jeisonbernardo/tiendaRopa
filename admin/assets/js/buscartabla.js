// Función para realizar la búsqueda por AJAX y actualizar el tbody
function buscarCategoria() {
    let input = document.getElementById('inline-form-input-name').value.toLowerCase();
    let tableBody = document.getElementById('categoriasTableBody');
    let rows = tableBody.getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        let cells = rows[i].getElementsByTagName('td');
        let nombre = cells[0].textContent.toLowerCase();
        if (nombre.indexOf(input) > -1) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}

function buscarsubCategoria() {
    let input = document.getElementById('inline-form-input-name').value.toLowerCase();
    let tableBody = document.getElementById('SubcategoriasTableBody');
    let rows = tableBody.getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        let cells = rows[i].getElementsByTagName('td');
        let nombre = cells[0].textContent.toLowerCase();
        if (nombre.indexOf(input) > -1) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}

function buscarmarcas() {
    let input = document.getElementById('inline-form-input-name').value.toLowerCase();
    let tableBody = document.getElementById('marcasTableBody');
    let rows = tableBody.getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        let cells = rows[i].getElementsByTagName('td');
        let nombre = cells[0].textContent.toLowerCase();
        if (nombre.indexOf(input) > -1) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}

function buscarproductos() {
    let input = document.getElementById('inline-form-input-name').value.toLowerCase();
    let tableBody = document.getElementById('ProductosTableBody');
    let rows = tableBody.getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        let cells = rows[i].getElementsByTagName('td');
        let nombre = cells[0].textContent.toLowerCase();
        if (nombre.indexOf(input) > -1) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}