<?php include '../template/navbar_admin.php'; ?>
<?php include 'cuentaconf.php'; ?>

<div class="container ">
<div class="container mt-5 mx-auto">
    <!-- Page title-->
    <div class="border-bottom text-center">
        <h1 class="mt-2 mt-md-4 mb-3 pt-3">TÚ PERFIL</h1>
        <p class="text-muted">Aquí puedes modificar tus datos</p>
    </div>

    <div class="card box-shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 style="margin-bottom: 0px;">MODIFICAR DATOS</h5>
        </div>

        <div class="card-body">
            <form id="updateForm" method="POST" enctype="multipart/form-data" onsubmit="return validatePasswords(event)">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombreUsuario; ?>" placeholder="Ingresa tu nombre">
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $apellidoUsuario; ?>" placeholder="Ingresa tu apellido">
                </div>

                <div class="form-group">
                    <label for="correo">Correo electrónico:</label>
                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $emailUsuario; ?>" placeholder="Ingresa tu correo electrónico">
                </div>

                <div class="form-group">
                    <label for="register-password" class="form-label">Nueva contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="register-password" name="password" placeholder="Ingresa tu contraseña">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('register-password')">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="register-confirm-password" class="form-label">Confirmar nueva contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="register-confirm-password" name="confirm_password" placeholder="Confirma tu nueva contraseña">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('register-confirm-password')">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="foto">Foto de perfil:</label>
                    <input type="file" class="form-control-file" id="foto" name="foto">
                </div>

                <div class="form-group">
                    <label for="profile_picture_preview">Vista previa de la imagen de perfil:</label>
                    <div id="profile_picture_preview" class="border p-2" style="max-width: 200px; max-height: 200px;">
                        <?php if ($fotoPerfil) : ?>
                            <img src="<?php echo $fotoPerfil; ?>" alt="Imagen de perfil" class="img-fluid">
                        <?php else : ?>
                            <p>No se ha seleccionado ninguna imagen de perfil.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar datos</button>
            </form>
        </div>
    </div>
</div> <br><br><br>


<!-- Modal -->
<div class="modal fade" id="passwordMismatchModal" tabindex="-1" role="dialog" aria-labelledby="passwordMismatchModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordMismatchModalLabel">Upps</h5>
                
            </div>
            <div class="modal-body">
                Las contraseñas no coinciden. Por favor, verifica e intenta nuevamente.
            </div>
        </div>
    </div>
</div>
</div>

<script src="../../admin/assets/js/cuenta_admin.js"> </script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<?php include '../template/footer_admin.php'; ?>