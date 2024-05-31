<?php
// Incluir el archivo de conexión
include 'conexion.php';

// Verificar si se ha recibido el parámetro categoria_id
if (isset($_GET['categoria_id'])) {
  $categoriaId = $_GET['categoria_id'];

  // Consultar las subcategorías correspondientes a la categoría seleccionada
  $querysubcategorias = "SELECT id, nombre FROM subcategoria WHERE categoria_id = ?";
  $stmtsubcategorias = mysqli_prepare($conn, $querysubcategorias);
  mysqli_stmt_bind_param($stmtsubcategorias, 'i', $categoriaId);
  mysqli_stmt_execute($stmtsubcategorias);
  $resultsubcategorias = mysqli_stmt_get_result($stmtsubcategorias);

  $subcategorias = array();
  while ($rowsubcategoria = mysqli_fetch_assoc($resultsubcategorias)) {
    $subcategorias[] = array(
      'id' => $rowsubcategoria['id'],
      'nombre' => $rowsubcategoria['nombre']
    );
  }

  // Devolver los datos JSON de las subcategorías
  header('Content-Type: application/json');
  echo json_encode($subcategorias);
  exit;
}
?>