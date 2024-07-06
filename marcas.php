<?php include  'navbar.php' ;?>
<div class="container mt-5">
        <h1 class="text-center">Nuestras Marcas</h1>
        <p class="text-center">Conoce las marcas de nuestro negocio</p>
        
        <div class="row">
            <?php
            // Consulta para obtener las marcas
            $sql = "SELECT nombre, imagen FROM marca";
            $result = $conn->query($sql);
            

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $rutamarcas = str_replace('../../admin/assets/img/marca_img/', './admin/assets/img/marca_img/', $row['imagen']);
                    echo '
                    <div class="col-md-4 mb-4">
                        <div class="card mb-4 shadow-sm card fixed-card">
                            <img src="' . $rutamarcas . '" class="card-img-top" alt="' . $row['nombre'] . '">
                            <div class="card-body">
                                <h5 class="card-title text-center">' . $row['nombre'] . '</h5>
                                
                                
                            </div>
                        </div>
                    </div>
                    ';
                }
            }
            ?>
        </div>
    </div>

    <style>
        .fixed-card {
            height: 100%;
        }
        
    </style>

<?php include  'footer.php' ;?>

