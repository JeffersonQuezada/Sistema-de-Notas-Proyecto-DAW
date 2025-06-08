<?php
require_once '../models/ActividadModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

class ActividadController {
    private $actividadModel;
    public function __construct() {
        $this->actividadModel = new ActividadModel();
    }
    public function listarPorCurso($id_curso) {
        $actividades = $this->actividadModel->listarActividadesPorCurso($id_curso);
        include '../views/actividades_listado.php';
    }
    public function ver($id_actividad) {
        $actividad = $this->actividadModel->obtenerActividadPorId($id_actividad);
        include '../views/ver_actividad.php';
    }
}
?>