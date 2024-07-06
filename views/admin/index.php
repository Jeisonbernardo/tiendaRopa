<?php include '../template/navbar_admin.php'; ?>
<?php include '../../model/index_admin.php'; ?>


<div class="container mt-5">
    <h1 class="text-center text-secondary">Bienvenido <?php echo $nombreUsuario;?></h1>
    <div class="row mt-4">

      <div class="col-sm-3">
        <div class="card bg-primary text-white">
          <div class="card-body text-center">
            <i class="fas fa-user fa-3x"></i>
            <h5 class="card-title">Clientes </h5>
            <p class="card-text"><?php echo $totalClientes; ?></p>
          </div>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="card bg-warning">
          <div class="card-body text-center">
          <i class="fas fa-box fa-3x "></i>
            <h5 class="card-title">Productos</h5>
            <p class="card-text"><?php echo $totalProductos; ?></p>
          </div>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="card bg-secondary text-white">
          <div class="card-body text-center">
          <i class="fas fa-users fa-3x"></i>
            <h5 class="card-title">Total Usuarios</h5>
            <p class="card-text"><?php echo $totalUsuarios; ?></p>
          </div>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="card bg-secondary text-white">
            <div class="card-body text-center">
                <i class="fas fa-money-bill-wave fa-3x"></i> <!-- Icono de dinero o billete -->
                <h5 class="card-title">Ingresos</h5>
                <p class="card-text">S/<?php echo  number_format($ventas_totales, 2)?></p>
            </div>
        </div>
      </div>

    </div>

    <div class="row mt-5" >
      <div class="col-sm-6" id="container">
      </div>
      <div class="col-sm-6" id="container1">
      </div>
    </div>

    <div class="col mt-5">
      <div class="text-center mb-4">
          <h1 class="display-12 text-black font-weight-normal">Ventas</h1>
          <select id="timeFrame" class="form-select mt-3">
              <option value="day">Diario</option>
              <option value="week">Semanal</option>
              <option value="month">Mensual</option>
              <option value="year">Anual</option>
          </select>
      </div>

      <div class="text-center">
          <canvas id="salesChart" width="400" height="200"></canvas>
      </div>

      <hr class="mt-5 mb-5">

      <div class="text-center mb-4">
          <h1 class="display-12 text-black font-weight-normal">Clientes más frecuentes</h1>
      </div>

      <div class="text-center">
          <canvas id="customerChart" width="400" height="200"></canvas>
      </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var datosCategorias = <?php echo json_encode($datosCategorias); ?>;
        Highcharts.chart('container', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Distribución de Ventas por Categoría de Productos'
            },
            tooltip: {
                pointFormat: '<b>{point.percentage:.1f}%</b> de ventas<br>Total unidades: {point.total_unidades}'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    }
                }
            },
            series: [{
                name: 'Porcentaje de Ventas',
                colorByPoint: true,
                data: datosCategorias.map(function(item) {
                    return {
                        name: item.categoria,
                        y: item.total_ventas,
                        total_unidades: item.total_unidades
                    };
                })
            }]
        });
    });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
        var datosVentasMarca = <?php echo json_encode($datosVentasMarca); ?>;
        
        Highcharts.chart('container1', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Cantidad de Ventas por Marca'
            },
            xAxis: {
                type: 'category',
                title: {
                    text: 'Marcas'
                }
            },
            yAxis: {
                title: {
                    text: 'Cantidad de Ventas'
                }
            },
            series: [{
                name: 'Ventas',
                data: datosVentasMarca.map(function(item) {
                    return {
                        name: item.marca,
                        y: item.total_ventas
                    };
                })
            }]
        });
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
                    backgroundColor: 'rgba(75, 192, 194, 0.2)',
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


<?php include '../template/footer_admin.php'; ?>