<?php
require_once '../models/ActividadModel.php';

class ActividadController {
    private $actividadModel;

    public function __construct() {
        $this->actividadModel = new ActividadModel();
    }

    public function crearActividad($nombre, $descripcion, $fecha_limite, $id_curso, $tipo = 'Tarea') {
        // Validar que todos los campos requeridos estén presentes
        if (empty($nombre) || empty($descripcion) || empty($fecha_limite) || empty($id_curso)) {
            return false;
        }

        return $this->actividadModel->crearActividad($nombre, $descripcion, $fecha_limite, $id_curso, $tipo);
    }

    public function editarActividad($id_actividad, $nombre, $descripcion, $fecha_limite, $tipo) {
        return $this->actividadModel->editarActividad($id_actividad, $nombre, $descripcion, $fecha_limite, $tipo);
    }

    public function eliminarActividad($id_actividad) {
        return $this->actividadModel->eliminarActividad($id_actividad);
    }

    public function listarActividadesPorCurso($id_curso) {
        return $this->actividadModel->listarActividadesPorCurso($id_curso);
    }
}
?>