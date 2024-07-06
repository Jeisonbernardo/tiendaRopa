<?php

include 'navbar.php';
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contacto'])) {
    // Obtener y limpiar los datos ingresados por el usuario
    $nombre = trim($_POST['name']);
    $apellido = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $mensaje = trim($_POST['mensaje']);


  
    // Verificar que las contraseñas coincidan y tengan al menos 8 caracteres
    
  
  
  
        // Realizar la inserción en la tabla cliente
        $sqlInsert = "INSERT INTO contacto (nombre, apellido, email, telefono,mensaje) VALUES ('$nombre', '$apellido', '$email','$telefono', '$mensaje')";
  
        if ($conn->query($sqlInsert) === TRUE) {
          $success = "¡Éxito! se envió su mensaje.";
        } else {
          $error = "Error en el envío. Inténtalo de nuevo.";
        }

      }
    
  
?>
   <div class="" id="modal-signin" tabindex="" style="margin: 50px 0px">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
<div class="" id="">
            <div class="modal-header border-0 pb-0 px-md-5 px-4 d-block position-relative">
              <h3 class="modal-title mt-4 mb-0 text-center">Contáctanos</h3>
        
        
              </button>
            </div>
            <div class="modal-body px-md-5 px-4">
              <p class="font-size-sm text-muted text-center">¡Contáctanos si tiene alguna consulta!</p>
              <form class="needs-validation" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" novalidate>
                <div class="form-group">
                  <label for="signup-name">Nombres</label>
                  <input type="text" class="form-control" id="signup-name" name="name" placeholder="Tu nombre" required>
                </div>
                <div class="form-group">
                  <label for="register-lastname">Apellido</label>
                  <input type="text" class="form-control" id="register-lastname" name="lastname" placeholder="Tu apellido" required>
                </div>
                <div class="form-group">
                  <label for="signup-email">Correo electrónico</label>
                  <input type="email" class="form-control" id="signup-email" name="email" placeholder="Tu dirección de correo electrónico" required>
                </div>

                <div class="form-group">
                  <label for="register-lastname">Teléfono</label>
                  <input type="text" class="form-control" id="register-telefono" name="telefono" placeholder="Tu teléfono" required>
                </div>
                
                <div class="form-group">
                  <label for="signup-email">Mensaje</label>
                  <textarea class="form-control"  name="mensaje"></textarea>
                </div>
              

                <button class="btn btn-primary btn-block " style="margin: 40px 0"  type="submit" name="contacto">Enviar </button>
             
              </form>
            </div>
          </div>
</div>
</div>
</div>


<?php include 'footer.php'; ?>