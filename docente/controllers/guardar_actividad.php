<?php
require_once __DIR__ . '/ActividadController.php';
require_once __DIR__ . '/../models/CursoModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: ../views/login.php");
        exit();
    }

    $id_docente = $_SESSION['id_usuario'];
    // Aquí el formulario envía 'titulo', pero el modelo espera 'nombre'
    $nombre = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $fecha_limite = $_POST['fecha_entrega'];
    $id_curso = $_POST['id_curso'];
    $tipo = $_POST['tipo'];

    $cursoModel = new CursoModel();
    if (!$cursoModel->verificarDocenteCurso($id_docente, $id_curso)) {
        header("Location: ../index.php?accion=nueva_actividad&error=3&msg=" . urlencode("No tienes acceso a este curso"));
        exit();
    }

    $actividadModel = new ActividadModel();
    $resultado = $actividadModel->crearActividad($nombre, $descripcion, $fecha_limite, $id_curso, $tipo);

    if ($resultado) {
        header("Location: ../index.php?accion=actividades&success=1&msg=" . urlencode("Actividad creada exitosamente"));
        exit();
    } else {
        header("Location: ../index.php?accion=nueva_actividad&error=1&msg=" . urlencode("Error al crear la actividad"));
        exit();
    }
} else {
    header("Location: ../index.php?accion=nueva_actividad");
    exit();
}
?>