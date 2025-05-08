
<?php
// Definir la URL base del proyecto
define('BASE_URL', '/');  // Ajusta esto según tu entorno de desarrollo

// Configuraciones de base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Reemplaza con tu usuario
define('DB_PASS', ''); // Reemplaza con tu contraseña
define('DB_NAME', 'sistema_notas');

// Zona horaria
date_default_timezone_set('America/Lima'); // Ajusta según tu región

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
// Configuración de rutas base
define('ROOT_PATH', dirname(__DIR__)); // Raíz del proyecto
define('INCLUDES_PATH', ROOT_PATH . '/includes');

// Configuración de la base de datos y otras constantes
?>