<?php
require_once __DIR__ . '/../models/ReporteModel.php';
require_once __DIR__ . '/../models/CursoModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

class DashboardController {
    public function index() {
        $reporteModel = new ReporteModel();
        $cursoModel = new CursoModel();
        $estadisticas = $reporteModel->estadisticasGenerales();
        $ultimos_cursos = $cursoModel->listarCursos(); // Puedes limitar a los últimos 5 en el modelo
        include __DIR__ . '/../views/dashboard.php';
    }
}
?>