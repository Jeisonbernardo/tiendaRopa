<?php

include '../conexion.php';

?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>ADMIN</title>
    
    <!-- SEO Meta Tags-->
    <meta name="description" content="Createx - Multipurpose Bootstrap Template and UI Kit">
    <meta name="keywords" content="bootstrap, business, creative agency, construction, services, e-commerce, shopping cart, mobile app showcase, multipurpose, shop, ui kit, marketing, seo, landing, html5, css3, javascript, gallery, slider, touch, creative">
    <meta name="author" content="Createx Studio">

    <!-- Viewport-->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon and Touch Icons-->
    <link rel="icon" type="image/png" href="assets/img/icon.png">
    <meta name="msapplication-TileColor" content="#766df4">
    <meta name="theme-color" content="#ffffff">

    <!-- Vendor Styles-->
    <link rel="stylesheet" media="screen" href="assets/vendor/simplebar/dist/simplebar.min.css"/>

    <!-- Main Theme Styles + Bootstrap-->
    <link rel="stylesheet" media="screen" href="assets/css/theme.min.css">
  </head>


  <!-- Body-->
  <body>

  <body>

    <main class="container-fluid pb-3 pb-sm-4" style="width: 100%;
      height: 100%;">
        <div class="row" style="width: 100%;
        height: 100%;">
            <div class="mx-auto" style="display: flex;
            align-items: center;">
                <div class="modal-body px-md-5 px-4">
                    <h3 class="modal-title mt-4 mb-0 text-center mb-5">Iniciar sesión</h3>
                    <?php
                    if (isset($error_message)) {
                        echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
                    }
                    ?>
                    <p class="font-size-sm text-muted text-center">Inicia sesión en tu cuenta usando el correo electrónico y la contraseña proporcionados durante el registro.</p>
                    <form class="needs-validation" novalidate="" method="POST">
                    <div class="form-group">
                        <label for="signin-email">Correo electrónico</label>
                        <input type="email" class="form-control" id="signin-email" name="signin-email" placeholder="Tu dirección de correo electrónico" required="">
                    </div>
                      <div class="form-group">
                        <label for="signin-password" class="form-label">Contraseña</label>
                        <div class="cs-password-toggle input-group-overlay">
                          <input type="password" class="form-control appended-form-control" id="signin-password" name="signin-password" placeholder="Tu contraseña" required="">
                          <div class="input-group-append-overlay">
                            <label class="btn cs-password-toggle-btn input-group-text">
                              <input type="checkbox" class="custom-control-input">
                              <i class="cxi-eye cs-password-toggle-indicator"></i>
                              <span class="sr-only">Mostrar contraseña</span>
                            </label>
                          </div>
                        </div>
                      </div>
                      <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
                    </form>
                  </div>
            </div>
        </div>
    </main>
    
    <a class="btn-scroll-top" href="#top" data-scroll data-fixed-element>
        <span class="btn-scroll-top-tooltip text-muted font-size-sm mr-2">Top</span>
        <i class="btn-scroll-top-icon cxi-angle-up"></i>
        </a>
    
        <!-- Vendor scripts: js libraries and plugins-->
        <script src="assets/vendor/jquery/dist/jquery.slim.min.js"></script>
        <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="assets/vendor/simplebar/dist/simplebar.min.js"></script>
        <script src="assets/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>
    
        <!-- Main theme script-->
        <script src="assets/js/theme.min.js"></script>
  </body>
    
    <a class="btn-scroll-top" href="#top" data-scroll data-fixed-element>
        <span class="btn-scroll-top-tooltip text-muted font-size-sm mr-2">Top</span>
        <i class="btn-scroll-top-icon cxi-angle-up"></i>
        </a>
    
        <!-- Vendor scripts: js libraries and plugins-->
        <script src="assets/vendor/jquery/dist/jquery.slim.min.js"></script>
        <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="assets/vendor/simplebar/dist/simplebar.min.js"></script>
        <script src="assets/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>
    
        <!-- Main theme script-->
        <script src="assets/js/theme.min.js"></script>
  </body>
</html>