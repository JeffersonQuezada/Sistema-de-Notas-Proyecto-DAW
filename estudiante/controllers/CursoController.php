<?php
require_once __DIR__ . '/../models/CursoModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

class CursoController {
    private $cursoModel;
    public function __construct() {
        $this->cursoModel = new CursoModel();
    }
    public function mostrarCursosDisponibles() {
        $id_estudiante = $_SESSION['id_usuario'];
        $cursos = $this->cursoModel->listarCursosConEstado($id_estudiante);
        include __DIR__ . '/../views/cursos_listado.php';
    }
    public function inscribir($id_curso) {
        $id_estudiante = $_SESSION['id_usuario'];
        $this->cursoModel->inscribirEstudiante($id_estudiante, $id_curso);
        header("Location: ../index.php?accion=cursos&success=1");
        exit();
    }
    public function desinscribir($id_curso) {
        $id_estudiante = $_SESSION['id_usuario'];
        $this->cursoModel->desinscribirEstudiante($id_estudiante, $id_curso);
        header("Location: ../index.php?accion=cursos&success=2");
        exit();
    }
    public function mostrarCursosInscritos() {
        $id_estudiante = $_SESSION['id_usuario'];
        $cursos = $this->cursoModel->listarCursosInscritos($id_estudiante);
        include '../views/mis_cursos.php';
    }
}
?>