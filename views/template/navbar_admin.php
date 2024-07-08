    <?php include '../../config/conexion.php'; ?>

    <?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Obtener la URL actual
    $current_url = $_SERVER['REQUEST_URI'];

    // Verificar si NO se ha iniciado sesión y NO hay un token almacenado
    if (!isset($_SESSION['token'])) {
    header('Location: loginconf.php');
    exit;
    }

    // Verificar si se ha enviado el parámetro para cerrar la sesión
    if (isset($_GET['logout'])) {
    // Eliminar el token almacenado
    unset($_SESSION['token']);

    // Destruir la sesión
    session_destroy();

    // Redireccionar a login.php
    header('Location: loginconf.php');
    exit;
    }

    ?>




    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ADMIN</title>
        <link rel="icon" type="image/png" href="../../admin/assets/img/icon.png">
        <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        
        <!-- Incluir Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>  
        <!-- fontawesone-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!-- datatable-->
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />


        <!-- navbar_admin.css-->
        <link rel="stylesheet" href="../../admin/assets/css/navbar_admin.css">

    </head>

    <body>
        <div class="wrapper">
            <aside id="sidebar">
                <div class="d-flex">
                    <button class="toggle-btn" type="button">
                        <i class="lni lni-grid-alt"></i>
                    </button>
                    <div class="sidebar-logo">
                        <a href="#">TuPuntodeModa</a>
                    </div>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="./index.php" class="sidebar-link">
                        <i class="lni lni-home"></i>
                            <span>Inicio</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="./cuenta.php" class="sidebar-link">
                            <i class="lni lni-user"></i>
                            <span>Cuenta</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                            data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                            <i class="lni lni-tag"></i>
                            <span>Categorias</span>
                        </a>
                        <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="./agregar_categorias.php" class="sidebar-link">Añadir categorias</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="./categorias.php" class="sidebar-link">Ver categorias</a>
                            </li>
                        </ul>
                    </li>


                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                            data-bs-target="#auth1" aria-expanded="false" aria-controls="auth">
                            <i class="lni lni-layers"></i>
                            <span>Subcategorias</span>
                        </a>
                        <ul id="auth1" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="./agregar_subcategorias.php" class="sidebar-link">Añadir subcategorias</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="./subcategoria.php" class="sidebar-link">Ver subcategorias</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                            data-bs-target="#auth2" aria-expanded="false" aria-controls="auth">
                            <i class="lni lni-mailchimp"></i>
                            <span>Marcas</span>
                        </a>
                        <ul id="auth2" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="./agregar_marcas.php" class="sidebar-link">Añadir marcas</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="./marcas.php" class="sidebar-link">Ver marcas</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                            data-bs-target="#auth3" aria-expanded="false" aria-controls="auth">
                            <i class="lni lni-t-shirt"></i>
                            <span>Productos</span>
                        </a>
                        <ul id="auth3" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="./agregar_productos.php" class="sidebar-link">Añadir productos</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="./productos.php" class="sidebar-link">Ver productos</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item">
                        <a href="./contacto.php" class="sidebar-link">
                            <i class="lni lni-popup"></i>
                            <span>Contactos</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="./configuracionTienda.php" class="sidebar-link">
                            <i class="lni lni-cog"></i>
                            <span>Configurar tienda</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="./register.php" class="sidebar-link">
                            <i class="lni lni-customer"></i>
                            <span>Agregar Integrante</span>
                        </a>
                    </li>
                </ul>
                <div class="sidebar-footer">
                    <a href="?logout=true" class="sidebar-link">
                        <i class="lni lni-exit"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </aside>
            
            <div class="main">
                <nav class="navbar navbar-expand  px-4 py-3">
                    
                    <div class="navbar-collapse collapse">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                    <?php
                                        // Obtener el nombre del usuario registrado desde la base de datos y almacenarlo en una variable
                                        $query = "SELECT nombre, foto FROM admin WHERE token='" . $_SESSION['token'] . "'";
                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                        $nombreUsuario = $row['nombre'];
                                        $fotoPerfil = $row['foto'];
                                        ?>
                                        <!-- Imagen y nombre del usuario -->
                                        <img src="<?php echo $fotoPerfil; ?>" alt="Foto de perfil" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 8px;">
                                    <?php echo $nombreUsuario; ?>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end rounded">
                                    <a class="dropdown-item <?php echo strpos($current_url, 'cuenta.php') !== false ? 'active' : ''; ?>" href="./cuenta.php">Perfil</a>
                                    <a class="dropdown-item" href="../../index.php">Ir a tienda</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="?logout=true">Cerrar sesión</a>


                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                
            
                
                