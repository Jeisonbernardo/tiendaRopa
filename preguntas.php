<?php
include 'navbar.php';
include 'conexion.php';
?>

<div class="" id="modal-signin" tabindex="" style="margin: 50px 0px">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
            <div class="modal-header border-0 pb-0 px-md-5 px-4 d-block position-relative">
                <h3 class="modal-title mt-4 mb-0 text-center">Preguntas Frecuentes</h3>
            </div>
            <div class="modal-body px-md-5 px-4">
                <p class="font-size-sm text-muted text-center">¡Aquí está las respuestas a sus preguntas frecuentes!</p><br>
                <div class="form-group">
                    <label for="signup-name" class="cursor-pointer" onclick="showAnswer(this)">¿A DÓNDE ENTREGAMOS?</label>
                    <div class="cont-respuesta" style="display: none;"><br>
                        <h6>Respuesta:</h6>
                        <p>Hacemos entregas a personas mayores de 18 años y que cuenten con un domicilio en Perú. Ten en cuenta que algunas áreas pueden quedar excluidas de entregas. Puedes cambiar el domicilio de entrega y/o agregar uno nuevo en la página de pago, antes de finalizar tu compra.</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="signup-name" class="cursor-pointer" onclick="showAnswer(this)">¿QUIÉN ME ENTREGARÁ MI PRODUCTO?</label>
                    <div class="cont-respuesta" style="display: none;"><br>
                        <h6>Respuesta:</h6>
                        <p>TuPuntodeModa trabaja en colaboración con Shalom y/o socios en cada región de nuestro país. También trabajamos con OlvaCourier</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="signup-name" class="cursor-pointer" onclick="showAnswer(this)">¿PUEDO CANCELAR O MODIFICAR MI PEDIDO?</label>
                    <div class="cont-respuesta" style="display: none;"><br>
                        <h6>Respuesta:</h6>
                        <p>Comenzamos a procesar tu pedido rápidamente, lo que significa que no podemos hacer ningún cambio una vez que este se confirma. Esto incluye cambiar la dirección de envío o la opción de entrega. No te preocupes una vez que recibas tu pedido puedes realizar la devolución sin costo alguno y realizar una nueva compra.</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="signup-name" class="cursor-pointer" onclick="showAnswer(this)">¿QUE MEDIDAS DE PAGO SE ACEPTAN?</label>
                    <div class="cont-respuesta" style="display: none;"><br>
                        <h6>Respuesta:</h6>
                        <p>Aceptamos únicamente tarjetas de crédito y débito de Perú. El pago es procesado a través de la plataforma de Mercadopago S.A.</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="signup-name" class="cursor-pointer" onclick="showAnswer(this)">¿CUÁNDO RECIBIRÉ LA CONFIRMACIÓN DE MI COMPRA?</label>
                    <div class="cont-respuesta" style="display: none;"><br>
                        <h6>Respuesta:</h6>
                        <p>En el transcurso del día, mediante correo electrónico. Ten en cuenta que la aprobación del pago puede demorar hasta 24 horas. En el caso que tu pago sea rechazado, recibirás un e-mail con la cancelación.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showAnswer(label) {
        // Obtener la respuesta correspondiente a la pregunta clicada
        var answer = label.nextElementSibling;

        // Ocultar todas las respuestas
        var answers = document.querySelectorAll(".cont-respuesta");
        answers.forEach(function(ans) {
            if (ans !== answer) {
                ans.style.display = "none";
            }
        });

        // Alternar la visibilidad de la respuesta correspondiente
        if (answer.style.display === "block") {
            answer.style.display = "none";
        } else {
            answer.style.display = "block";
        }
    }
</script>

<?php include 'footer.php'; ?>

