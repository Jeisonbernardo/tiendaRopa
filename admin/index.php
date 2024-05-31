<?php

include 'Vistas/template/header.php';

// Verificar si NO se ha iniciado sesión y NO hay un token almacenado
if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}
?>
          <!-- Page title-->
          <div class="border-bottom pt-5 mt-2 mb-5">
            <h1 class="mt-2 mt-md-4 mb-3 pt-5">AGREGAR</h1>
            <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
              <p class="text-muted">Agregar index</p>

            </div>
          </div>

          <div class="card box-shadow-sm">
                <div class="card-header">
                    <h5 style="margin-bottom: 0px;">AGREGAR</h5>
                </div>
 
                <div class="card-body">
                </div>

                <div class="card-footer">
                  <button class="btn btn-primary mr-3">Guardar cambios</button>
                </div>
          </div>    

        
        </div>
      </section>
    </main>

    <!-- Back to top button-->
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