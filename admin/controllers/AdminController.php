<?php
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../views/DashboardView.php';

class AdminController {
    private $model;
    
    public function __construct() {
        $this->model = new AdminModel();
    }
    
    public function mostrarDashboard() {
        $model = new AdminModel();
        $estadisticas = $model->obtenerEstadisticasDashboard();

        // Obtener todos los cursos para el filtro
        $cursos = $model->obtenerTodosLosCursos();

        // Filtro por curso
        $cursoFiltrado = $_GET['curso'] ?? '';
        $estadisticas['mejoresAlumnos'] = $model->mejoresAlumnosPorCurso(5, $cursoFiltrado);

        $estadisticas['docentesCumplimiento'] = $model->docentesCumplimientoTareas();
        $view = new DashboardView();
        $view->mostrar($estadisticas, $cursos);
    }
}