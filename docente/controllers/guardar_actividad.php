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
    
    // Obtener datos del formulario
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $fecha_entrega = $_POST['fecha_entrega'];
    $id_curso = isset($_POST['id_curso']) ? $_POST['id_curso'] : null;
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'Tarea';

    // Validaciones
    if (empty($titulo) || empty($descripcion) || empty($fecha_entrega) || empty($id_curso)) {
        header("Location: ../views/nueva_actividad.php?error=2&msg=" . urlencode("Todos los campos son obligatorios"));
        exit();
    }

    // Verificar que el docente tenga acceso al curso
    $cursoModel = new CursoModel();
    if (!$cursoModel->verificarDocenteCurso($id_docente, $id_curso)) {
        header("Location: ../views/nueva_actividad.php?error=3&msg=" . urlencode("No tienes acceso a este curso"));
        exit();
    }

    // Crear la actividad
    $controller = new ActividadController();
    $resultado = $controller->crearActividad($titulo, $descripcion, $fecha_entrega, $id_curso, $tipo);

    if ($resultado) {
        header("Location: ../views/actividades_listado.php?success=1&msg=" . urlencode("Actividad creada exitosamente"));
        exit();
    } else {
        header("Location: ../views/nueva_actividad.php?error=1&msg=" . urlencode("Error al crear la actividad"));
        exit();
    }
} else {
    header("Location: ../views/nueva_actividad.php");
    exit();
}
?>