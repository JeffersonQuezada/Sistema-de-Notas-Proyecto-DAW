<?php

require_once __DIR__ . '/../models/ActividadModel.php';
require_once __DIR__ . '/../models/CursoModel.php';
require_once __DIR__ . '/../models/EntregaModel.php';

if (session_status() === PHP_SESSION_NONE) session_start();

class ActividadController {
    private $actividadModel;
    private $cursoModel;
    private $entregaModel;
    
    public function __construct() {
        $this->actividadModel = new ActividadModel();
        $this->cursoModel = new CursoModel();
        $this->entregaModel = new EntregaModel();
    }
    
    public function listarPorCurso($id_curso) {
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: ../login.php");
            exit();
        }
        
        $actividades = $this->actividadModel->listarActividadesPorCurso($id_curso);
        $curso = $this->cursoModel->obtenerCursoPorId($id_curso);
        include __DIR__ . '/../views/actividades_listado.php';
    }
    
    public function ver($id_actividad) {
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: ../login.php");
            exit();
        }
        
        $actividad = $this->actividadModel->obtenerActividadPorId($id_actividad);
        $id_estudiante = $_SESSION['id_usuario'];
        $entrega = $this->entregaModel->obtenerEntregaPorActividadYEstudiante($id_estudiante, $id_actividad);
        
        include __DIR__ . '/../views/ver_actividad.php';
    }
}
?>