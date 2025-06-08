<?php
require_once __DIR__ . '/../../models/DashboardModel.php';

class DocenteController {
    private $docenteModel;

    public function __construct() {
        $this->docenteModel = new DocenteModel();
    }

    // Gestión de Grupos
    public function crearGrupo($nombre, $id_docente) {
        return $this->docenteModel->crearGrupo($nombre, $id_docente);
    }

    public function editarGrupo($id_grupo, $nombre, $id_docente) {
        return $this->docenteModel->editarGrupo($id_grupo, $nombre, $id_docente);
    }

    public function eliminarGrupo($id_grupo, $id_docente) {
        return $this->docenteModel->eliminarGrupo($id_grupo, $id_docente);
    }

    // Gestión de Actividades
    public function crearActividad($id_curso, $nombre, $descripcion, $fecha_limite, $tipo) {
        return $this->docenteModel->crearActividad($id_curso, $nombre, $descripcion, $fecha_limite, $tipo);
    }

    public function editarActividad($id_actividad, $nombre, $descripcion, $fecha_limite, $tipo) {
        return $this->docenteModel->editarActividad($id_actividad, $nombre, $descripcion, $fecha_limite, $tipo);
    }

    // Calificaciones
    public function calificarEntrega($id_estudiante, $id_curso, $id_actividad, $nota, $observaciones) {
        return $this->docenteModel->calificarEntrega($id_estudiante, $id_curso, $id_actividad, $nota, $observaciones);
    }

    // Dashboard
    public function obtenerEstadisticasCurso($id_curso) {
        return $this->docenteModel->obtenerEstadisticasCurso($id_curso);
    }

    public function obtenerEstudiantesEnRiesgo($id_curso) {
        return $this->docenteModel->obtenerEstudiantesEnRiesgo($id_curso);
    }

    // Misiones
    public function crearMision($titulo, $descripcion, $recompensa, $id_grupo = null) {
        return $this->docenteModel->crearMision($titulo, $descripcion, $recompensa, $id_grupo);
    }
}
?>