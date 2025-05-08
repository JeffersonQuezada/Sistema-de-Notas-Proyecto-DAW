<?php
session_start();

// Incluir conexión a la base de datos
require_once 'conexion.php'; // Asegúrate que esta ruta es correcta

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

// Función para verificar roles
function verificarRol($rolRequerido) {
    global $pdo; // Hacer $pdo disponible en esta función
    
    if ($_SESSION['rol'] != $rolRequerido) {
        header("Location: ../login.php");
        exit();
    }
}

// Hacer $pdo disponible globalmente en las páginas que incluyan este archivo
global $pdo;
?>