<?php
function sanitizarInput($data) {
    return htmlspecialchars(trim($data));
}

function mostrarError($mensaje) {
    echo '<div class="alert alert-danger">' . $mensaje . '</div>';
}

function mostrarExito($mensaje) {
    echo '<div class="alert alert-success">' . $mensaje . '</div>';
}

// Otras funciones que necesites...

function setNotification($type, $title, $message) {
    $_SESSION['notification'] = [
        'type' => $type,
        'title' => $title,
        'message' => $message
    ];
}

function redirectWithNotification($url, $type, $title, $message) {
    setNotification($type, $title, $message);
    header("Location: $url");
    exit();
}

function base_url($path = '') {
    return '/' . ltrim($path, '/');
}

function url($path = '') {
    // Asegúrate de que BASE_URL está definida
    if (!defined('BASE_URL')) {
        define('BASE_URL', '/');
    }
    
    // Elimina barras iniciales y finales extra
    $path = trim($path, '/');
    
    // Construye la URL completa
    if (empty($path)) {
        return rtrim(BASE_URL, '/');
    } else {
        return rtrim(BASE_URL, '/') . '/' . $path;
    }
}

function activeMenu($current, $expected) {
    // Normaliza las rutas para comparación
    $current = basename($current);
    $expected = basename($expected);
    
    // Compara si el script actual contiene la ruta esperada
    return strpos($current, $expected) !== false ? 'active' : '';
}
?>