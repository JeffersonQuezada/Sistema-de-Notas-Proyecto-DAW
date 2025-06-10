<?php
require_once __DIR__ . '/../models/EntregaModel.php';
require_once __DIR__ . '/../models/CursoModel.php';
require_once __DIR__ . '/../models/InsigniaModel.php';
require_once __DIR__ . '/../models/MisionModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

class DashboardController {
    public function mostrarDashboard() {
        $id_estudiante = $_SESSION['id_usuario'];
        $entregaModel = new EntregaModel();
        $cursoModel = new CursoModel();
        $insigniaModel = new InsigniaModel();
        $misionModel = new MisionModel();

        $entregas = $entregaModel->obtenerEntregasPorEstudiante($id_estudiante);
        $cursos = $cursoModel->listarCursosInscritos($id_estudiante);
        $promedio = $entregaModel->obtenerPromedioGeneral($id_estudiante);
        $insignias = $insigniaModel->listarInsigniasPorEstudiante($id_estudiante);
        $misiones = $misionModel->listarMisionesDisponibles($id_estudiante);

        include __DIR__ . '/../views/dashboard_alumnos.php';

    }
}
?>