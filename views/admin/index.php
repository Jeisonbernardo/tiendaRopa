<?php include '../../config/config.php';
include BASE_PATH . 'config/conexion.php'; ?>
<?php include '../template/navbar_admin.php'; ?>
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

            <div class="container mt-5 mx-auto">
                <h1 class="text-center bienvenida">Bienvenido <?php echo $nombreUsuario;?></h1>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-user"> Clientes  </i></h5>
                                <p class="card-text"><?php echo $totalClientes; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-box "> Productos  </i></h5>
                                <p class="card-text"><?php echo $totalProductos; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-users"> Total usuarios</i></h5>
                                <p class="card-text"><?php echo $totalUsuarios; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <br><br>

                <h1 class="display-12 text-center text-black font-weight-normal">Ventas</h1>
                <select id="timeFrame">
                    <option value="day">Diario</option>
                    <option value="week">Semanal</option>
                    <option value="month">Mensual</option>
                    <option value="year">Anual</option>
                </select>
                <canvas id="salesChart" width="400" height="200"></canvas>

                <br><br>

                <h1 class="display-12 text-center text-black font-weight-normal">Clientes más frecuentes</h1>
                <canvas id="customerChart" width="400" height="200"></canvas>
            </div> <br><br><br>
        

    <script>
        var clientes = <?php echo json_encode($clientes); ?>;
        var total_ventas = <?php echo json_encode($total_ventas); ?>;

        var ctx = document.getElementById('customerChart').getContext('2d');
        var customerChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: clientes,
                datasets: [{
                    label: 'Total Ventas (Soles)',
                    data: total_ventas,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Soles'
                        },
                        ticks: {
                            callback: function(value, index, values) {
                                return 'S/ ' + value;
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Clientes'
                        }
                    }
                }
            }
        });
    </script>


    <script>
        var dates = <?php echo json_encode($dates); ?>;
        var total_sales_day = <?php echo json_encode($total_sales_day); ?>;
        var weeks = <?php echo json_encode($weeks); ?>;
        var total_sales_week = <?php echo json_encode($total_sales_week); ?>;
        var months = <?php echo json_encode($months); ?>;
        var total_sales_month = <?php echo json_encode($total_sales_month); ?>;
        var years = <?php echo json_encode($years); ?>;
        var total_sales_year = <?php echo json_encode($total_sales_year); ?>;

        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Ventas totales',
                    data: total_sales_day,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Soles'
                        },
                        ticks: {
                            callback: function(value, index, values) {
                                return 'S/ ' + value;
                            }
                        }
                    },
                    
                }
            }
        });
        


        document.getElementById('timeFrame').addEventListener('change', function() {
            var timeFrame = this.value;
            var newData = {};
            if (timeFrame === 'day') {
                newData.labels = dates;
                newData.data = total_sales_day;
            } else if (timeFrame === 'week') {
                newData.labels = weeks;
                newData.data = total_sales_week;
            } else if (timeFrame === 'month') {
                newData.labels = months;
                newData.data = total_sales_month;
            } else if (timeFrame === 'year') {
                newData.labels = years;
                newData.data = total_sales_year;
            }
            salesChart.data.labels = newData.labels;
            salesChart.data.datasets[0].data = newData.data;
            salesChart.update();
        });
        
    </script>

<?php include '../template/footer_admin.php'; ?>
