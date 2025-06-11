<?php
require_once __DIR__.'/../models/DashboardModel.php';

class DashboardController {
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = new DashboardModel();
    }

    public function obtenerProgresoIndividual($id_estudiante) {
        try {
            return $this->dashboardModel->obtenerProgresoIndividual($id_estudiante);
        } catch (Exception $e) {
            error_log("Error en DashboardController: " . $e->getMessage());
            return ['error' => 'Error al obtener el progreso individual'];
        }
    }

    public function obtenerProgresoGrupal($id_grupo) {
        try {
            return $this->dashboardModel->obtenerProgresoGrupal($id_grupo);
        } catch (Exception $e) {
            error_log("Error en DashboardController: " . $e->getMessage());
            return ['error' => 'Error al obtener el progreso grupal'];
        }
    }

    public function identificarEstudiantesEnRiesgo($id_curso = null) {
        try {
            return $this->dashboardModel->identificarEstudiantesEnRiesgo($id_curso);
        } catch (Exception $e) {
            error_log("Error en DashboardController: " . $e->getMessage());
            return ['error' => 'Error al identificar estudiantes en riesgo'];
        }
    }

    public function obtenerEstadisticasCumplimiento($id_curso = null) {
        try {
            return $this->dashboardModel->obtenerEstadisticasCumplimiento($id_curso);
        } catch (Exception $e) {
            error_log("Error en DashboardController: " . $e->getMessage());
            return ['error' => 'Error al obtener estadísticas de cumplimiento'];
        }
    }

    public function mostrarDashboard() {
        try {
            $id_estudiante = $_GET['id_estudiante'] ?? null;
            $id_grupo = $_GET['id_grupo'] ?? null;
            $id_curso = $_GET['id_curso'] ?? null;

            if ($id_estudiante) {
                $progresoIndividual = $this->obtenerProgresoIndividual($id_estudiante);
                include '../views/dashboard_individual.php';
            } elseif ($id_grupo) {
                $progresoGrupal = $this->obtenerProgresoGrupal($id_grupo);
                include '../views/dashboard_grupal.php';
            } else {
                $estudiantesEnRiesgo = $this->identificarEstudiantesEnRiesgo($id_curso);
                $estadisticasCumplimiento = $this->obtenerEstadisticasCumplimiento($id_curso);
                include __DIR__ .'/../views/dashboard_principal.php';
            }
        } catch (Exception $e) {
            error_log("Error en mostrarDashboard: " . $e->getMessage());
            header("Location: ../views/error.php?msg=" . urlencode("Error al cargar el dashboard"));
            exit();
        }
    }
}
?>