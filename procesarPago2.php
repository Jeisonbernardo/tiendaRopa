<?php

// Asegúrate de que esta ruta sea correcta
require_once __DIR__ . '/vendor/autoload.php';

// Incluye y registra el autoloader de Composer
use Culqi\Culqi;

// Configurar tu API Key y autenticación
$SECRET_KEY = "sk_test_5d22b6a17af1196d";
$culqi = new Culqi(array('api_key' => $SECRET_KEY));

try {
    $charge = $culqi->Charges->create(
        array(
            "amount" => 1000,
            "currency_code" => "PEN",
            "description" => "Venta de prueba",
            "email" => $_POST["email"],
            "source_id" => $_POST["token"]
        )
    );

    echo "exitoso";
    // Respuesta
    // print_r($charge);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
