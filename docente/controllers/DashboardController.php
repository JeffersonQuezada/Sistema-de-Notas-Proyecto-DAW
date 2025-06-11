<?php
require_once __DIR__.'/../models/DashboardModel.php';

class DashboardController {
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = new DashboardModel();
    }

    public function obtenerProgresoIndividual($id_estudiante) {
        return $this->dashboardModel->obtenerProgresoIndividual($id_estudiante);
    }

    public function obtenerProgresoGrupal($id_grupo) {
        return $this->dashboardModel->obtenerProgresoGrupal($id_grupo);
    }

    public function identificarEstudiantesEnRiesgo() {
        return $this->dashboardModel->identificarEstudiantesEnRiesgo();
    }

    public function obtenerEstadisticasCumplimiento() {
        return $this->dashboardModel->obtenerEstadisticasCumplimiento();
    }

    public function mostrarDashboard() {
        $id_estudiante = $_GET['id_estudiante'] ?? null;
        $id_grupo = $_GET['id_grupo'] ?? null;

        if ($id_estudiante) {
            $progresoIndividual = $this->obtenerProgresoIndividual($id_estudiante);
            include '../views/dashboard_individual.php';
        } elseif ($id_grupo) {
            $progresoGrupal = $this->obtenerProgresoGrupal($id_grupo);
            include '../views/dashboard_grupal.php';
        } else {
            $estudiantesEnRiesgo = $this->identificarEstudiantesEnRiesgo();
            $estadisticasCumplimiento = $this->obtenerEstadisticasCumplimiento();
            include __DIR__ .'/../views/dashboard_principal.php';
        }
    }
}
?>