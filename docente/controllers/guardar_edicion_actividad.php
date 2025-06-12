<?php

require_once __DIR__ . '/../models/ActividadModel.php';
require_once __DIR__ . '/../models/CursoModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../login.php");
    exit();
}

$id_docente = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_actividad = $_POST['id_actividad'];
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $fecha_limite = $_POST['fecha_limite'];
    $id_curso = $_POST['id_curso'];
    $tipo = $_POST['tipo'];

    $cursoModel = new CursoModel();
    // Verifica que el curso pertenezca al docente
    if (!$cursoModel->verificarDocenteCurso($id_docente, $id_curso)) {
        header("Location: ../views/actividades_listado.php?error=1&msg=No tienes permiso para modificar este curso");
        exit();
    }

    $actividadModel = new ActividadModel();
    $resultado = $actividadModel->editarActividad($id_actividad, $nombre, $descripcion, $fecha_limite, $tipo);

    if ($resultado) {
        header("Location: ../index.php?accion=actividades&success=1&msg=Actividad modificada correctamente");
    } else {
        header("Location: ../index.php?accion=editar_actividad&id=$id_actividad&error=1&msg=No se pudo modificar la actividad");
    }
    exit();
} else {
    header("Location: ../views/actividades_listado.php");
    exit();
}
?>