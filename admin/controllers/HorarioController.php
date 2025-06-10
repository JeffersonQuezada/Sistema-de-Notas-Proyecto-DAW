<?php
require_once __DIR__ . '/../models/HorarioModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

class HorarioController {
    private $horarioModel;
    public function __construct() {
        $this->horarioModel = new HorarioModel();
    }

    // Listar horarios de un curso
    public function listar() {
        $id_curso = $_GET['id_curso'] ?? null;
        if (!$id_curso) {
            header("Location: ../index.php?accion=cursos&error=Curso no especificado");
            exit();
        }
        $horarios = $this->horarioModel->listarHorariosPorCurso($id_curso);
        include __DIR__ . '/../views/cursos_horarios.php';
    }

    // Crear un nuevo horario
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_curso = $_POST['id_curso'];
            $dia = $_POST['dia'];
            $hora_inicio = $_POST['hora_inicio'];
            $hora_fin = $_POST['hora_fin'];
            $this->horarioModel->crearHorario($id_curso, $dia, $hora_inicio, $hora_fin);
            header("Location: ../index.php?accion=horarios&id_curso=$id_curso&success=1");
            exit();
        }
        // Si no es POST, redirige al listado
        header("Location: ../index.php?accion=cursos");
        exit();
    }

    // Eliminar un horario
    public function eliminar() {
        $id_horario = $_GET['id_horario'] ?? null;
        $id_curso = $_GET['id_curso'] ?? null;
        if ($id_horario && $id_curso) {
            $this->horarioModel->eliminarHorario($id_horario);
            header("Location: ../index.php?accion=horarios&id_curso=$id_curso&success=2");
            exit();
        }
        header("Location: ../index.php?accion=cursos&error=Datos incompletos");
        exit();
    }
}
?>