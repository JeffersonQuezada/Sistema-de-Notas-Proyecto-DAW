<?php
// Configuración de rutas
define('ROOT_PATH', dirname(__DIR__));
define('INCLUDES_PATH', ROOT_PATH . '/includes');

// Configuración de la aplicación
define('BASE_URL', 'http://localhost/Sistema-Gestion-de-Notas/');
define('SITE_NAME', 'Sistema de Gestión de Notas');

// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gestion_notas');

// Configuración de seguridad
define('DEBUG_MODE', true);

// Configuración de sesión DEBE ESTAR ANTES DE session_start()
/*session_set_cookie_params([
    'lifetime' => 86400, // 1 día
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
    'secure' => false, // Cambiar a true en producción con HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);*/

// Zona horaria
date_default_timezone_set('America/Lima');

// Configuración de errores
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}