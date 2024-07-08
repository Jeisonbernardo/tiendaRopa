      <!-- HTML + navbar -->
      <?php include 'navbar.php';
      // Incluir el archivo de conexión
      include 'conexion.php';
      ?>
      <!-- end navbar -->
     


 
      

      <!-- Breadcrumb -->
      <nav class="bg-secondary mb-3" aria-label="breadcrumb">
        <div class="container">
          <ol class="breadcrumb breadcrumb-alt mb-0">
            <li class="breadcrumb-item">
              <a href="index.html"><i class="cxi-home"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Carrito</li>
          </ol>
        </div>
      </nav>

      <!-- Page content -->
      <section class="container pt-3 pt-md-4 pb-3 pb-sm-4 pb-lg-5 mb-4">
        <div class="row">

          <!-- Checkout content -->
          <div class="col-lg-8 pr-lg-6">
            <div class="d-flex align-items-center justify-content-between pb-2 mb-4">
              <h1 class="mb-0">Carrito</h1>
              <a href="catalogo.php"><strong>Seguir comprando</strong></a>
            </div>

            <?php
            // Verificar si el usuario tiene la sesión iniciada
            if (isset($_SESSION["user_token"])) {
              $user_token = $_SESSION["user_token"];
            } else {
              echo '<div class="alert alert-info mb-4" role="alert">
          <div class="media align-items-center">
            <i class="cxi-profile lead mr-3"></i>
            <div class="media-body">
              ¿Ya tienes una cuenta?&nbsp;&nbsp;<a href="#modal-signin" class="alert-link" data-toggle="modal" data-view="#modal-signin-view">Inicie Sesión</a>&nbsp;&nbsp;para una experiencia de pago más rápida.
            </div>
          </div>
        </div>';
            }
            ?>

            <hr class="border-top-0 border-bottom pt-2 mb-4">

            <!-- Order review -->
            <h2 class="h4 mb-4">1. Revisión del pedido</h2>
<div class="bg-secondary rounded mb-5">
  <?php
  if (isset($_POST['cart'])) {
    $storedCart = json_decode($_POST['cart'], true);

    // Manejar eliminación de productos
    if (isset($_POST['removeIndex'])) {
      $removeIndex = $_POST['removeIndex'];
      unset($storedCart[$removeIndex]);
      $storedCart = array_values($storedCart); // Reindexar array
    }

    // Manejar actualización de cantidades
    if (isset($_POST['updateIndex']) && isset($_POST['newQuantity'])) {
      $updateIndex = $_POST['updateIndex'];
      $newQuantity = $_POST['newQuantity'];
      $storedCart[$updateIndex]['quantity'] = $newQuantity;
    }

    $index = 0; // Inicializar el contador
    $total = 0; // Inicializar el total
    
    foreach ($storedCart as $producto) {
      $nombre = $producto['nombre'];
      $precio = $producto['precio'];
      $imagen = $producto['imagen'];
      $marca = $producto['marca'];
      $categoria = $producto['categoria'];
      $stock = $producto['stock'];
      $cantidad = isset($producto['quantity']) ? $producto['quantity'] : 1; // Obtener cantidad o establecer 1 por defecto
      $total += $precio * $cantidad; // Acumular el precio total
  ?>
      <div class="media px-2 px-sm-4 py-4 border-bottom cart-item" data-product-index="<?php echo $index; ?>">
        <div id="empty-cart-image" style="display: none;">
          <img src="./assets/img/ecommerce/carrito-de-compras.png" alt="Empty Cart">
        </div>
        <a href="detalle-producto.php?id=<?php echo $producto['id']; ?>" style="min-width: 80px;">
          <img src="<?php echo $imagen; ?>" width="80" alt="Product thumb">
        </a>
        <div class="media-body w-100 pl-3">
          <div class="d-sm-flex">
            <div class="pr-sm-3 w-100" style="max-width: 16rem;">
              <h3 class="font-size-sm mb-3">
                <a href="detalle-producto.php?id=<?php echo $producto['id']; ?>" class="nav-link font-weight-bold"><?php echo $nombre; ?></a>
              </h3>
              <ul class="list-unstyled font-size-xs mt-n2 mb-2">
                <li class="mb-0"><span class="text-muted">Categoria:</span><?php echo $categoria; ?></li>
                <li class="mb-0"><span class="text-muted">Marca:</span><?php echo $marca; ?></li>
              </ul>
            </div>
            <div class="d-flex pr-sm-3">
              <form method="post" action="">
                <input type="hidden" name="cart" value='<?php echo json_encode($storedCart); ?>'>
                <input type="hidden" name="updateIndex" value="<?php echo $index; ?>">
                <input type="number" name="newQuantity" class="form-control form-control-sm bg-light mr-3 cart-quantity-input" style="width: 4.5rem;" value="<?php echo $cantidad; ?>" required min="1" max="<?php echo $stock; ?>" data-price="<?php echo $precio; ?>" data-cart-item="<?php echo 'cart-item-' . $index; ?>">
                <button type="submit" class="btn btn-outline-secondary btn-sm">Actualizar</button>
              </form>
              <div class="text-nowrap pt-2"><strong class="text-danger">S/<?php echo number_format($precio * $cantidad, 2); ?></strong> <s class="font-size-xs text-muted">$<?php echo number_format(($precio / 4.0), 2); ?></s></div>
            </div>
            <div class="d-flex align-items-center flex-sm-column text-sm-center ml-sm-auto pt-3 pt-sm-0">
              <form method="post" action="">
                <input type="hidden" name="cart" value='<?php echo json_encode($storedCart); ?>'>
                <input type="hidden" name="removeIndex" value="<?php echo $index; ?>">
                <button class="btn btn-outline-primary btn-sm mr-2 mr-sm-0 delete-product" type="submit">
                  Eliminar
                </button>
              </form>
              <?php
              if (isset($_SESSION["user_token"])) {
                $user_token = $_SESSION["user_token"];
              ?>
                <!--<button class="btn btn-link btn-sm text-decoration-none pt-0 pt-sm-2 px-0 pb-0 mt-0 mt-sm-1">
                  Añadir a Favoritos
                  <i class="cxi-heart ml-1"></i>
                </button>-->
              <?php
              }
              ?>
            </div>
          </div>
        </div>
      </div>
  <?php
      $index++; // Incrementar el contador en cada iteración
    }
  ?>
  <div class="px-3 px-sm-4 py-4 text-right">
    <span class="text-muted">Total:<strong class="text-dark font-size-lg ml-2">S/<?php echo number_format($total, 2); ?></strong></span>
  </div>
  <?php
  }
  ?>
</div>


            <!-- Adresses -->
            <h2 class="h4 mb-4">2. Dirección de envío y facturación</h2>
            <div class="row pb-3">
              <div class="col-sm-6 form-group">
                <label for="ch-fn">Nombres</label>
                <input type="text" class="form-control form-control-lg" id="ch-fn" placeholder="Tu nombre" required>
              </div>
              <div class="col-sm-6 form-group">
                <label for="ch-ln">Apellidos</label>
                <input type="text" class="form-control form-control-lg" id="ch-ln" placeholder="Tus apellidos">
              </div>
              <div class="col-sm-6 form-group">
                <label for="ch-email">Correo Electrónico</label>
                <input type="email" class="form-control form-control-lg" id="ch-email" placeholder="Tu dirección de correo electrónico">
              </div>
              <div class="col-sm-6 form-group">
                <label for="ch-phone">Teléfono</label>
                <input type="text" class="form-control form-control-lg" id="ch-phone" placeholder="Tu número de teléfono">
              </div>
              <div class="col-sm-6 form-group">
                <label for="ch-country">País</label>
                <select id="ch-country" class="custom-select custom-select-lg">
                  <option value="" disabled selected>Elige tu País</option>
                  <option value="Perú">Perú</option>
                </select>
              </div>
              <div class="col-sm-6 form-group">
                <label for="ch-city">Ciudad</label>
                <select id="ch-city" class="custom-select custom-select-lg">
                  <option value="" disabled selected>Elige tu ciudad</option>
                  <option value="Lima">Lima</option>
                </select>
              </div>
              <div class="col-sm-6 form-group">
                <label for="ch-address">Dirección</label>
                <input type="text" class="form-control form-control-lg" id="ch-address" placeholder="Tú dirección.">
              </div>
              <div class="col-sm-6 form-group">
                <label for="ch-zip">Código Postal</label>
                <input type="text" class="form-control form-control-lg" id="ch-zip" placeholder="Código Postal" >
              </div>
              <div class="col-12 form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="same-details" checked>
                  <label for="same-details" class="custom-control-label">Los detalles de facturación y envío son los mismos</label>
                </div>
              </div>
            </div>

            <hr class="mb-4 pb-2">

            <!-- Shipping -->
            <h2 class="h4 mb-4">3. Método de Envío</h2>
            <div class="custom-control custom-radio mb-3">
              <input type="radio" class="custom-control-input" id="courier" name="shipping" checked>
              <label for="courier" class="custom-control-label d-flex align-items-center">
                <span>
                  <strong class="d-block">Envío por courier a tu dirección</strong>
                  <span class="text-muted font-size-sm">Fecha estimada: 9 de noviembre</span>
                </span>
                <span class="ml-auto">S/10.00</span>
              </label>
            </div>
            <div class="custom-control custom-radio mb-3">
              <input type="radio" class="custom-control-input" id="store-pickup" name="shipping">
              <label for="store-pickup" class="custom-control-label d-flex align-items-center">
                <span>
                  <strong class="d-block">Recoger en la tienda</strong>
                  <span class="text-muted font-size-sm">Recoger el 8 de noviembre a partir de las 12:00pm</span>
                </span>
                <span class="ml-auto">Gratis</span>
              </label>
            </div>
            <!--<div class="custom-control custom-radio mb-3">
              <input type="radio" class="custom-control-input" id="ups" name="shipping">
              <label for="ups" class="custom-control-label d-flex align-items-center">
                <span>
                  <strong class="d-block">Envío por UPS Ground</strong>
                  <span class="text-muted font-size-sm">Hasta una semana</span>
                </span>
                <span class="ml-auto">S/10.00</span>
              </label>
            </div>
            <div class="custom-control custom-radio mb-3">
              <input type="radio" class="custom-control-input" id="locker-pickup" name="shipping">
              <label for="locker-pickup" class="custom-control-label d-flex align-items-center">
                <span>
                  <strong class="d-block">Recoger en Createx Locker</strong>
                  <span class="text-muted font-size-sm">Recoger el 8 de noviembre a partir de las 12:00pm</span>
                </span>
                <span class="ml-auto">$8.50</span>
              </label>
            </div>
            <div class="custom-control custom-radio mb-3">
              <input type="radio" class="custom-control-input" id="global-export" name="shipping">
              <label for="global-export" class="custom-control-label d-flex align-items-center">
                <span>
                  <strong class="d-block">Exportación Global Createx</strong>
                  <span class="text-muted font-size-sm">3-4 días</span>
                </span>
                <span class="ml-auto">$15.00</span>
              </label>
            </div>-->

            <hr class="border-top-0 border-bottom pt-4 mb-4">

            <!-- Payment -->
            <!--<h2 class="h4 pt-2 mb-4">4. Método de Pago</h2>
            <div class="row pb-4">
              <div class="col-lg-7">-->

                <!-- Payment accordion -->
                <!-- <div class="accordion-alt" id="payment-methods"> -->

                  <!-- Card: Credit card -->
                  <!--<div class="card mb-3 px-4 py-3 border rounded box-shadow-sm">
                    <div class="card-header py-2">
                      <div class="accordion-heading custom-control custom-radio" data-toggle="collapse" data-target="#cc-card">
                        <input type="radio" class="custom-control-input" id="cc" name="payment" checked>
                        <label for="cc" class="custom-control-label d-flex align-items-center">
                          <strong class="d-block mr-3">Tarjeta de crédito</strong>
                          <img src="assets/img/cards.png" width="108" alt="Credit cards">
                        </label>
                      </div>
                    </div>
                    <div class="collapse show" id="cc-card" data-parent="#payment-methods">

                      <div class="accordion-body pt-0 pb-3">
                        <div class="mb-3">
                          <label for="cc-number" class="form-label-lg">Número de Tarjeta</label>
                          <input type="text" id="cc-number" class="form-control form-control-lg" data-format="card" placeholder="0000 0000 0000 0000">
                        </div>
                        <div class="d-flex">
                          <div class="mb-3 me-3">
                            <label for="cc-exp-date" class="form-label-lg">Fecha de Vencimiento</label>
                            <input type="text" id="cc-exp-date" class="form-control form-control-lg" data-format="date" placeholder="mm/yy">
                          </div>
                          <div class="mb-3">
                            <label for="cc-cvc" class="form-label-lg">CVV</label>
                            <input type="text" id="cc-cvc" class="form-control form-control-lg" data-format="cvc" placeholder="000">
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>-->

                  <!-- Card: PayPal -->
                  <!-- <div class="card mb-3 px-4 py-3 border rounded box-shadow-sm">
                    <div class="card-header py-2">
                      <div class="accordion-heading custom-control custom-radio" data-toggle="collapse" data-target="#paypal-card">
                        <input type="radio" class="custom-control-input" id="paypal" name="payment">
                        <label for="paypal" class="custom-control-label d-flex align-items-center">
                          <strong class="d-block mr-3">PayPal</strong>
                          <img src="assets/img/paypal-badge.png" width="48" alt="PayPal">
                        </label>
                      </div>
                    </div>
                    <div class="collapse" id="paypal-card" data-parent="#payment-methods">
                      <div class="card-body pt-3 pb-0">
                        <a href="#" class="d-inline-block mb-2" style="max-width: 300px;">
                          <img src="assets/img/ecommerce/checkout/paypal.png" alt="PayPal">
                        </a>
                      </div>
                    </div>
                  </div>-->

                  <!-- Card: Cash -->
                  <!-- <div class="card mb-3 px-4 py-3 border rounded box-shadow-sm">
                    <div class="card-header py-2">
                      <div class="accordion-heading custom-control custom-radio" data-toggle="collapse" data-target="#cash-card">
                        <input type="radio" class="custom-control-input" id="cash" name="payment">
                        <label for="cash" class="custom-control-label">
                          <strong class="d-block mr-3">Al contado</strong>
                        </label>
                      </div>
                    </div>
                    <div class="collapse" id="cash-card" data-parent="#payment-methods">
                      <div class="card-body pt-3 pb-0">
                        <p class="mb-2 text-muted">Ha seleccionado pagar en efectivo en el momento de la entrega.</p>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>

            <hr class="mb-4 pb-2">-->

            <!-- Additional info -->
            <h2 class="h4 mb-4">4. Información Adicional (Opcional)</h2>
            <div class="form-group">
              <label for="ch-notes">Agregar notas</label>
              <textarea id="ch-notes" class="form-control form-control-lg" rows="5" placeholder="Notes para su pedido, por ejemplo, nota especial para la entrega"></textarea>
            </div>
          </div>

          <!-- Order totals (sticky sidebar) -->
          <aside class="col-lg-4" style="margin-top: -120px;">
            <div class="position-sticky top-0">
              <div class="mb-4" style="padding-top: 120px;">
                <!--<label for="promo-code" class="form-label-lg">Aplicar un cupón de promoción</label>
                <div class="input-group input-group-lg">
                  <input type="text" id="promo-code" class="form-control" placeholder="Introduce el código del cupón">
                  <button type="button" class="btn btn-primary px-4">&nbsp;&nbsp;Aplicar&nbsp;&nbsp;</button>
                </div>-->
              </div>
              <div class="bg-secondary rounded mb-4">
                <div class="border-bottom p-4">
                  <h2 class="h4 mb-0">Detalles de compra:</h2>
                </div>
                <ul class="list-unstyled border-bottom mb-0 p-4">
                  <li class="d-flex justify-content-between mb-2">
                    <strong><span class="fw-bold">Total:</span></strong>
                    <span class="fw-bold">S/<?php echo number_format($total, 2); ?></span>
                  </li>
                  <li class="d-flex justify-content-between mb-2">
                    <span>Costos adicional:</span>
                    <span id="total">S/10.00</span>
                  </li>
                  <li class="d-flex justify-content-between mb-2">
                    <span>Descuento:</span>
                    <span>—</span>
                  </li>
                </ul>
                <div class="d-flex justify-content-between p-4">
                  <span class="h5 mb-0">Monto a pagar:</span>
                  

                  <span class="h5 mb-0">S/<?php echo number_format($total, 2);?></span>
                  <?php $total_usd = (number_format($total, 2)) * 0.26; ?>
                </div>
              </div>
              <?php
              if (isset($_SESSION["user_token"])) {
                $user_token = $_SESSION["user_token"];
              ?>
                <button type="button" id="btn_pagar"  class="btn btn-primary btn-lg w-100">Confirmar pago</button><br><br>
        
              </div>
              <?php
              }
              ?>
              <div id="paypal-button-container">

              </div>
              
          </aside>

        </div>
      </section>

      </main>


      <!-- Footer -->
      <?php include 'footer.php'; ?>

      

<script>
    const courierInput = document.getElementById('courier');
    const storePickupInput = document.getElementById('store-pickup');
    const totalElement = document.getElementById('total');
    function actualizarTotal() {
      if (courierInput.checked) {
        totalElement.textContent = 'S/10.00';
      } else if (storePickupInput.checked) {
        totalElement.textContent = 'S/0.00';
      }
    }

    actualizarTotal();
    courierInput.addEventListener('change', actualizarTotal);
    storePickupInput.addEventListener('change', actualizarTotal);
    
</script>
  
  
<!---------------------------------------------- Pago con paypal  --------------------------------------------->
<!-- 1) llamar el script y poner el clientID -->    
<script src="https://www.paypal.com/sdk/js?client-id=ASaepcm2oDxNMwSjwVchXDPb_a3EUmtoHhQ9mz76YD5K8-26nkGdumPGPf4EYOTXcl2ID_CVr2C3CrDk&currency=USD"></script>
<script>
  //inicializar paypal  
  paypal.Buttons({
    //estilos a los botones
    style:{
      color:'blue',
      shape:'pill',
      label:'pay'
    },

    //parametro para unidades a agregar
    createOrder:function(data, actions){
      return actions.order.create({
        purchase_units:[{
          amount:{
            //añadir pago 
            value: <?php echo number_format($total_usd, 2);?>
          }
        }]

      });

    },
    //funcion cuando se realiza el pago
    onApprove: function(data,actions){
      actions.order.capture().then(function(detalles){
        console.log(detalles);
        alert("gracias por tu compra");
        window.location.href="gracias.php"

      });
    },

    //funcion cuando cancele pago
    onCancel: function(data) {
      alert("pago cancelado");
      console.log(data);
    }

  }).render('#paypal-button-container'); //id del div del boton
</script>
<!---------------------------------------------- Fin Pago con paypal  --------------------------------------------->




      <script>




    
/*
  Culqi.options({
      style: {
        logo: 'https://culqi.com/LogoCulqi.png',
        bannerColor: '', // hexadecimal
        buttonBackground: '', // hexadecimal
        menuColor: '', // hexadecimal
        linksColor: '', // hexadecimal
        buttonText: '', // texto que tomará el botón
        buttonTextColor: '', // hexadecimal
        priceColor: '' // hexadecimal
      }
  });

  */
  const btn_pagar = document.getElementById('btn_pagar');

btn_pagar.addEventListener('click', function (e) {
    // Abre el formulario con la configuración en Culqi.settings y CulqiOptions
    Culqi.publicKey = 'pk_test_0180d49bf88f53a1';
    Culqi.settings({
    title: 'Culqi Store',
    currency: 'PEN',  // Este parámetro es requerido para realizar pagos yape
    amount: <?php echo number_format($total, 2) + 25;?>,  // Este parámetro es requerido para realizar pagos yape
   // order: 'ord_live_0CjjdWhFpEAZlxlz', // Este parámetro es requerido para realizar pagos con pagoEfectivo, billeteras y Cuotéalo
   // xculqirsaid: 'Inserta aquí el id de tu llave pública RSA',
    //rsapublickey: 'Inserta aquí tu llave pública RSA',
  });

  Culqi.options({
    lang: "auto",
    installments: false, // Habilitar o deshabilitar el campo de cuotas
    paymentMethods: {
      tarjeta: true,
      yape: true,
      bancaMovil: true,
      agente: true,
      billetera: true,
      cuotealo: true,
    },
    style: {
          logo: "https://static.culqi.com/v2/v2/static/img/logo.png",
    }
  });


    Culqi.open();
    e.preventDefault();
})

function culqi() {
    if (Culqi.token) {  // ¡Objeto Token creado exitosamente!
      const token = Culqi.token.id;
      const email = Culqi.token.email;
      console.log('Se ha creado un Token: ', token);
      //En esta linea de codigo debemos enviar el "Culqi.token.id"
      //hacia tu servidor con Ajax

      $.ajax({
            url: "procesarPago2.php",
            type: "POST",
            data: {
                token: token,
                email: email
            }
        }).done(function(resp){
            alert(resp);
        })
      }
  };










  </script> 