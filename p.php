
<div id="paypal-button-container">

</div>

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
            value: <?php echo number_format($total, 2) + 10;?>
          }
        }]

      });

    },
    //funcion cuando se realiza el pago
    onApprove: function(data,actions){
      actions.order.capture().then(function(detalles){
        console.log(detalles);
        alert("gracias por tu compra")
      

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
</body>
</html>