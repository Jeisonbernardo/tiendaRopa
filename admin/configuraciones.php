<?php
// Incluir el archivo de conexión
include 'conexion.php';

// Iniciar la sesión
session_start();

// Rutas de destino para cada archivo
$destinationIcon = "../assets/img/favicon.png";
$destinationLogo = "../assets/img/logo.png";
$destinationLogoNav = "../assets/img/nav.png";
$destinationBanners = "../assets/img/ecommerce/home/hero-slider/";

// Obtener las rutas de los archivos desde la base de datos
$icon = "../assets/img/favicon.png";
$logo = "../assets/img/logo.png";
$logonav = "../assets/img/nav.png";
$banner1 = "../assets/img/ecommerce/home/hero-slider/banner1.jpg";
$banner2 = "../assets/img/ecommerce/home/hero-slider/banner2.jpg";
$banner3 = "../assets/img/ecommerce/home/hero-slider/banner3.jpg";
$banner4 = "../assets/img/ecommerce/home/hero-slider/banner4.jpg";

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $facebook = $_POST["facebook"];
    $instagram = $_POST["instagram"];
    $whatsapp = $_POST["whatsapp"];
    $numero = $_POST["numero"];
    $direccion = $_POST["dirección"];
    $gmap = $_POST["googlemaps"];

    // Procesar el archivo "icon"
    if ($_FILES["icon"]["tmp_name"] !== "") {
        if (move_uploaded_file($_FILES["icon"]["tmp_name"], $destinationIcon)) {
            $icon = $destinationIcon;
        } else {
            echo "Error al subir el archivo 'icon'.";
        }
    }

    // Procesar el archivo "logo"
    if ($_FILES["logo"]["tmp_name"] !== "") {
        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $destinationLogo)) {
            $logo = $destinationLogo;
        } else {
            echo "Error al subir el archivo 'logo'.";
        }
    }

    // Procesar el archivo "logonav"
    if ($_FILES["logonav"]["tmp_name"] !== "") {
        if (move_uploaded_file($_FILES["logonav"]["tmp_name"], $destinationLogoNav)) {
            $logonav = $destinationLogoNav;
        } else {
            echo "Error al subir el archivo 'logonav'.";
        }
    }

    // Procesar los archivos de los banners
    function processBanner($bannerNumber, $destinationPath, $fieldName)
    {
        global $conn;

        // Ruta donde se guardarán los archivos subidos
        $upload_directory = "uploads/";

        // Verificar si se ha seleccionado un nuevo archivo en el formulario
        if ($_FILES[$fieldName]['error'] === 0) {
            // Verificar si el archivo es una imagen JPG
            $archivoExtension = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
            if ($archivoExtension === 'jpg') {
                // Construir el nombre del archivo utilizando el número de banner ("01.jpg", "02.jpg", etc.)
                $nombreNuevoArchivo = $destinationPath . str_pad($bannerNumber, 2, "0", STR_PAD_LEFT) . ".jpg";

                // Mover el archivo subido a la carpeta de destino
                if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $nombreNuevoArchivo)) {
                    // Consulta SQL preparada para actualizar el nombre del archivo en la base de datos
                    $sql = "UPDATE tiendaconfig SET $fieldName=? WHERE id=1";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('s', $nombreNuevoArchivo);
                    if ($stmt->execute()) {
                        // No es necesario eliminar el archivo anterior, ya que estamos utilizando el mismo nombre
                    } else {
                        echo "Error al actualizar el nombre del archivo en la base de datos: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "Error al subir el archivo '$fieldName'.";
                }
            } else {
                echo "El archivo debe ser de formato JPG.";
            }
        }
    }

    processBanner("banner1", $destinationBanners, "banner1");
    processBanner("banner2", $destinationBanners, "banner2");
    processBanner("banner3", $destinationBanners, "banner3");
    processBanner("banner4", $destinationBanners, "banner4");

    // Aquí va el código para guardar los demás datos en la tabla "tiendaconfig"
    $sql = "UPDATE tiendaconfig SET nombre='$nombre', facebook='$facebook', instagram='$instagram', whatsapp='$whatsapp', numero='$numero', email='$email', dirección='$direccion', googlemaps='$gmap' WHERE id=1";
    if ($conn->query($sql) === TRUE) {
        // Redirigir a la página actual después de guardar los datos
        header('Location: ' . $_SERVER['PHP_SELF']);
    } else {
        // Si ocurre un error, puedes manejarlo de acuerdo a tus necesidades
        echo "Error al guardar los datos: " . $conn->error;
    }
}

// Consultar los datos de la tabla "tiendaconfig"
$sql = "SELECT * FROM tiendaconfig";
$result = $conn->query($sql);

// Inicializar variables para almacenar los datos de la tabla
$nombre = "";
$email = "";
$facebook = "";
$instagram = "";
$whatsapp = "";
$numero = "";
$direccion = "";
$gmap = "";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombre = $row['nombre'];
    $email = $row['email'];
    $facebook = $row['facebook'];
    $instagram = $row['instagram'];
    $whatsapp = $row['whatsapp'];
    $numero = $row['numero'];
    $direccion = $row['dirección'];
    $gmap = $row['googlemaps'];
    $success = "Datos cargados exitosamente";
} else {
    // Mostrar mensaje de error si no se pudieron actualizar los datos
    $error = "Hubo un error mostrar los datos, inténtelo más tarde.";
}

include 'vistas/configuracionTienda.php';
?>