<?php
// Definir las rutas base
define('BASE_URL', 'http://localhost/tiendaropa/');
define('BASE_PATH', realpath(dirname(__FILE__)) . '/');

// Rutas específicas
define('ADMIN_PATH', BASE_PATH . 'admin/');
define('ADMIN_URL', BASE_URL . 'admin/');

define('CONTROLLERS_PATH', ADMIN_PATH . 'controladores/');
define('VIEWS_PATH', ADMIN_PATH . 'Vistas/');
define('TEMPLATE_PATH', VIEWS_PATH . 'template/');

define('CONEXION_PATH', ADMIN_PATH . 'conexion.php');
?>
