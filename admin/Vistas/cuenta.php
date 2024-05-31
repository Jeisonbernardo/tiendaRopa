
<?php
include 'Vistas/template/navbar.php';
?>
          <!-- Page title-->
          <div class="border-bottom pt-5 mt-2 mb-5">
              <h1 class="mt-2 mt-md-4 mb-3 pt-5">TÚ PERFIL</h1>
              <div class="d-flex flex-wrap flex-md-nowrap justify-content-between">
                <p class="text-muted">Aquí puedes modificar tus datos</p>
              </div>
          </div>

          <div class="card box-shadow-sm">
              <div class="card-header">
                  <h5 style="margin-bottom: 0px;">MODIFICAR DATOS</h5>
              </div>

              <div class="card-body">
                  <form id="updateForm" method="POST" enctype="multipart/form-data">
                      <div class="form-group">
                          <label for="nombre">Nombre:</label>
                          <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombreUsuario; ?>" >
                      </div>

                      <div class="form-group">
                          <label for="apellido">Apellido:</label>
                          <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $apellidoUsuario; ?>" >
                      </div>

                      <div class="form-group">
                          <label for="correo">Correo electrónico:</label>
                          <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $emailUsuario; ?>" >
                      </div>

                      <div class="form-group">
                          <label for="register-password" class="form-label">Nueva contraseña</label>
                          <div class="cs-password-toggle input-group-overlay">
                              <input type="password" class="form-control appended-form-control" id="register-password" name="password" placeholder="Ingresa tú contraseña">
                              <div class="input-group-append-overlay">
                                  <label class="btn cs-password-toggle-btn input-group-text">
                                      <input type="checkbox" class="custom-control-input">
                                      <i class="cxi-eye cs-password-toggle-indicator"></i>
                                      <span class="sr-only">Mostrar contraseña</span>
                                  </label>
                              </div>
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="register-confirm-password" class="form-label">Confirmar nueva contraseña</label>
                          <div class="cs-password-toggle input-group-overlay">
                              <input type="password" class="form-control appended-form-control" id="register-confirm-password" name="confirm_password" placeholder="Confirmar tú nueva contraseña">
                              <div class="input-group-append-overlay">
                                  <label class="btn cs-password-toggle-btn input-group-text">
                                      <input type="checkbox" class="custom-control-input">
                                      <i class="cxi-eye cs-password-toggle-indicator"></i>
                                      <span class="sr-only">Mostrar contraseña</span>
                                  </label>
                              </div>
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="foto">Foto de perfil:</label>
                      </div>

                      <!-- Cuadro para mostrar la imagen de perfil -->
                      <div class="form-group">
                          <label for="profile_picture_preview">Vista previa de la imagen de perfil:</label>
                          <input type="file" class="form-control-file" id="foto" name="foto">
                          <br>
                          <div id="profile_picture_preview" style="max-width: 200px; max-height: 200px;">
                              <?php if ($fotoPerfil) : ?>
                                  <img src="<?php echo $fotoPerfil; ?>" alt="Imagen de perfil">
                              <?php else : ?>
                                  <p>No se ha seleccionado ninguna imagen de perfil.</p>
                              <?php endif; ?> 
                          </div>
                      </div>            
                      <button type="submit" class="btn btn-primary">Actualizar datos</button>
                  </form>
              </div>
          </div>

        </div>
      </section>
    </main>

    <!-- Back to top button-->
    <a class="btn-scroll-top" href="#top" data-scroll data-fixed-element>
      <span class="btn-scroll-top-tooltip text-muted font-size-sm mr-2"></span>
      <i class="btn-scroll-top-icon cxi-angle-up"></i>
    </a>

    <!-- Vendor scripts: js libraries and plugins-->
    <script>
    document.getElementById("register-password").addEventListener("input", function() {
        const confirmPasswordField = document.getElementById("register-confirm-password");
        confirmPasswordField.required = this.value.length > 0;
    });
    </script>

    <script>
        // Código JavaScript para mostrar la imagen de perfil en la vista previa
        // Este código asume que el elemento "profile_picture_preview" existe en el DOM.

        // Verificar si hay una imagen de perfil seleccionada y mostrarla en la vista previa
        var fotoPerfilInput = document.getElementById('foto');
        var profilePicturePreview = document.getElementById('profile_picture_preview');

        fotoPerfilInput.addEventListener('change', function(event) {
            var file = event.target.files[0];
            var reader = new FileReader();

            reader.onload = function() {
                var image = new Image();
                image.src = reader.result;
                image.alt = 'Imagen de perfil';
                image.style.maxWidth = '100%';
                image.style.maxHeight = '100%';
                profilePicturePreview.innerHTML = '';
                profilePicturePreview.appendChild(image);
            };

            if (file) {
                reader.readAsDataURL(file);
            } else {
                profilePicturePreview.innerHTML = '<p>No se ha seleccionado ninguna imagen de perfil.</p>';
            }
        });
    </script>

    <script src="assets/vendor/jquery/dist/jquery.slim.min.js"></script>
    <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/simplebar/dist/simplebar.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

    <!-- Main theme script-->
    <script src="assets/js/theme.min.js"></script>
  </body>
</html>