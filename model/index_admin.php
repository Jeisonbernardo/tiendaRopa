<?php include '../../config/config.php';
include BASE_PATH . 'config/conexion.php'; ?>

<?php 
// Verificar si NO se ha iniciado sesión y NO hay un token almacenado
if (!isset($_SESSION['token'])) {
  header('Location: loginconf.php');
  exit;
}

// Consultas para contar clientes, productos y usuarios
$sqlClientes = "SELECT COUNT(*) as totalClientes FROM cliente";
$resultClientes = $conn->query($sqlClientes);
$totalClientes = ($resultClientes->num_rows > 0) ? $resultClientes->fetch_assoc()['totalClientes'] : 0;

$sqlProductos = "SELECT COUNT(*) as totalProductos FROM producto";
$resultProductos = $conn->query($sqlProductos);
$totalProductos = ($resultProductos->num_rows > 0) ? $resultProductos->fetch_assoc()['totalProductos'] : 0;

$sqlUsuarios = "SELECT COUNT(*) as totalUsuarios FROM (SELECT id FROM cliente UNION ALL SELECT id FROM admin) as usuarios";
$resultUsuarios = $conn->query($sqlUsuarios);
$totalUsuarios = ($resultUsuarios->num_rows > 0) ? $resultUsuarios->fetch_assoc()['totalUsuarios'] : 0;

// Consulta SQL para obtener las ventas totales
$sql = "SELECT SUM(total) AS ventas_totales FROM ventas";
$resultado = $conn->query($sql);
if ($resultado) {
  $row = $resultado->fetch_assoc();
  $ventas_totales = $row['ventas_totales'];
  
} 

// Consulta para obtener las ventas y unidades vendidas por categoría
$sql = "SELECT cat.nombre AS categoria,
              SUM(v.cantidad) AS total_unidades,
              SUM(v.total) AS total_ventas
        FROM ventas v
        INNER JOIN producto p ON v.producto_id = p.id
        INNER JOIN categoria cat ON p.categoria_id = cat.id
        GROUP BY p.categoria_id";

$resultado = $conn->query($sql);
$datosCategorias = array();

while ($row = $resultado->fetch_assoc()) {
    $categoria = $row['categoria'];
    $totalUnidades = (int) $row['total_unidades'];
    $totalVentas = (float) $row['total_ventas'];

    $datosCategorias[] = array(
        'categoria' => $categoria,
        'total_unidades' => $totalUnidades,
        'total_ventas' => $totalVentas
    );
}

// Consulta para obtener las ventas por marca de producto
$sql = "SELECT m.nombre AS marca,
              SUM(v.cantidad) AS total_ventas
        FROM ventas v
        INNER JOIN producto p ON v.producto_id = p.id
        INNER JOIN marca m ON p.marca_id = m.id
        GROUP BY p.marca_id";

$resultado = $conn->query($sql);
$datosVentasMarca = array();
while ($row = $resultado->fetch_assoc()) {
    $marca = $row['marca'];
    $totalVentas = (int) $row['total_ventas'];
    $datosVentasMarca[] = array(
        'marca' => $marca,
        'total_ventas' => $totalVentas
    );
}

// Ventas por día
$sql_day = "SELECT DATE(fecha_venta) as date, SUM(total) as total_sales FROM ventas GROUP BY DATE(fecha_venta)";
$result_day = $conn->query($sql_day);

$dates = [];
$total_sales_day = [];

if ($result_day->num_rows > 0) {
    while($row = $result_day->fetch_assoc()) {
        $dates[] = $row['date'];
        $total_sales_day[] = $row['total_sales'];
    }
}

// Ventas por semana
$sql_week = "SELECT YEARWEEK(fecha_venta, 1) as week, SUM(total) as total_sales FROM ventas GROUP BY YEARWEEK(fecha_venta, 1)";
$result_week = $conn->query($sql_week);

$weeks = [];
$total_sales_week = [];

if ($result_week->num_rows > 0) {
    while($row = $result_week->fetch_assoc()) {
        $weeks[] = $row['week'];
        $total_sales_week[] = $row['total_sales'];
    }
}

// Ventas por mes
$sql_month = "SELECT DATE_FORMAT(fecha_venta, '%Y-%m') as month, SUM(total) as total_sales FROM ventas GROUP BY DATE_FORMAT(fecha_venta, '%Y-%m')";
$result_month = $conn->query($sql_month);

$months = [];
$total_sales_month = [];

if ($result_month->num_rows > 0) {
    while($row = $result_month->fetch_assoc()) {
        $months[] = $row['month'];
        $total_sales_month[] = $row['total_sales'];
    }
}

// Ventas por año
$sql_year = "SELECT YEAR(fecha_venta) as year, SUM(total) as total_sales FROM ventas GROUP BY YEAR(fecha_venta)";
$result_year = $conn->query($sql_year);

$years = [];
$total_sales_year = [];

if ($result_year->num_rows > 0) {
    while($row = $result_year->fetch_assoc()) {
        $years[] = $row['year'];
        $total_sales_year[] = $row['total_sales'];
    }
}

// Obtener ventas por cliente
$sql = "SELECT cliente.nombre AS cliente, SUM(ventas.total) AS total_ventas 
        FROM ventas 
        INNER JOIN cliente ON ventas.cliente_id = cliente.id 
        GROUP BY ventas.cliente_id 
        ORDER BY total_ventas DESC";
$result = $conn->query($sql);

$clientes = [];
$total_ventas = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row['cliente'];
        $total_ventas[] = $row['total_ventas'];
    }
}

?>