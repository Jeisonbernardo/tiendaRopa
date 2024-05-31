
<?php
include 'Vistas/template/navbar.php';
?>
<body>
  <main class="container-fluid pb-3 pb-sm-4">
    <!-- Main content-->
    <section class="cs-offcanvas-enabled row pb-3 pb-md-4">
      <div class="col-xl-9">
        <header class="navbar navbar-expand navbar-light fixed-top navbar-box-shadow bg-light px-3 px-lg-4" data-scroll-header data-fixed-element>
          <a class="navbar-brand d-lg-none" href="./index.php">
            <img src="../assets/img/nav.png" alt="Logo" width="130">
          </a>
          <button class="navbar-toggler d-block d-lg-none mr-3 ml-auto" type="button" data-toggle="offcanvas" data-target="componentsNav">
            <span class="navbar-toggler-icon"></span>
          </button>
          <ul class="navbar-nav ml-auto d-none d-lg-flex align-items-center">
            <!-- Menú desplegable -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
              <!-- Menú desplegable con las opciones -->
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item <?php echo strpos($current_url, 'cuenta.php') !== false ? 'active' : ''; ?>" href="./cuenta.php">Perfil</a>
                <a class="dropdown-item" href="../index.php">Ir a tienda</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="?logout=true">Cerrar sesión</a>
              </div>
            </li>
          </ul>
        </header>
      </div>
    </section>
  </main>
</body>
</html>
