<?php
require_once __DIR__ . '/ActividadController.php';
require_once __DIR__ . '/../models/CursoModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que el usuario esté logueado
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: ../views/login.php");
        exit();
    }

    $id_docente = $_SESSION['id_usuario'];
    $id_actividad = isset($_POST['id_actividad']) ? $_POST['id_actividad'] : null;

    if (!$id_actividad) {
        header("Location: ../index.php?accion=actividades&error=1&msg=" . urlencode("ID de actividad no válido"));
        exit();
    }

    $controller = new ActividadController();
    $resultado = $controller->eliminarActividad($id_actividad);

    if ($resultado) {
        header("Location: ../index.php?accion=actividades&success=1&msg=" . urlencode("Actividad eliminada exitosamente"));
        exit();
    } else {
        header("Location: ../index.php?accion=actividades&error=1&msg=" . urlencode("Error al eliminar la actividad"));
        exit();
    }
} else {
    header("Location: ../index.php?accion=actividades");
    exit();
}
?>