<?php
require_once 'ActividadController.php';
require_once '../models/CursoModel.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que el usuario esté logueado
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: ../views/login.php");
        exit();
    }

    $id_docente = $_SESSION['id_usuario'];
    $id_actividad = isset($_POST['id_actividad']) ? $_POST['id_actividad'] : null;

    if (!$id_actividad) {
        header("Location: ../views/actividades_listado.php?error=1&msg=" . urlencode("ID de actividad no válido"));
        exit();
    }

    // Aquí deberías verificar que la actividad pertenece a un curso del docente
    // Para esto necesitarías un método en ActividadModel que verifique la propiedad
    
    $controller = new ActividadController();
    $resultado = $controller->eliminarActividad($id_actividad);

    if ($resultado) {
        header("Location: ../views/actividades_listado.php?success=1&msg=" . urlencode("Actividad eliminada exitosamente"));
        exit();
    } else {
        header("Location: ../views/actividades_listado.php?error=1&msg=" . urlencode("Error al eliminar la actividad"));
        exit();
    }
} else {
    header("Location: ../views/actividades_listado.php");
    exit();
}
?>