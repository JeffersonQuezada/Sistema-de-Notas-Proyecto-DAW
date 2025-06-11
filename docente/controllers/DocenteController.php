<?php
require_once __DIR__ . '/../models/DocenteModel.php';
require_once __DIR__ . '/../models/CursoModel.php';

class DocenteController {
    private $docenteModel;
    private $cursoModel;

    public function __construct() {
        $this->docenteModel = new DocenteModel();
        $this->cursoModel = new CursoModel();
    }

    // Gestión de Grupos
    public function crearGrupo($nombre, $id_docente) {
        try {
            if (empty($nombre)) {
                throw new Exception("El nombre del grupo es requerido");
            }
            return $this->docenteModel->crearGrupo($nombre, $id_docente);
        } catch (Exception $e) {
            error_log("Error en crearGrupo: " . $e->getMessage());
            return false;
        }
    }

    public function editarGrupo($id_grupo, $nombre, $id_docente) {
        try {
            if (empty($nombre)) {
                throw new Exception("El nombre del grupo es requerido");
            }
            return $this->docenteModel->editarGrupo($id_grupo, $nombre, $id_docente);
        } catch (Exception $e) {
            error_log("Error en editarGrupo: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarGrupo($id_grupo, $id_docente) {
        try {
            return $this->docenteModel->eliminarGrupo($id_grupo, $id_docente);
        } catch (Exception $e) {
            error_log("Error en eliminarGrupo: " . $e->getMessage());
            return false;
        }
    }

    // Gestión de Actividades
    public function crearActividad($id_curso, $nombre, $descripcion, $fecha_limite, $tipo) {
        try {
            // Verificar que el docente es dueño del curso
            if (!$this->cursoModel->verificarDocenteCurso($_SESSION['id_usuario'], $id_curso)) {
                throw new Exception("No tienes permiso para crear actividades en este curso");
            }

            $tiposPermitidos = ['Tarea', 'Examen', 'Proyecto'];
            if (!in_array($tipo, $tiposPermitidos)) {
                throw new Exception("Tipo de actividad no válido");
            }

            return $this->docenteModel->crearActividad($id_curso, $nombre, $descripcion, $fecha_limite, $tipo);
        } catch (Exception $e) {
            error_log("Error en crearActividad: " . $e->getMessage());
            return false;
        }
    }

    public function editarActividad($id_actividad, $nombre, $descripcion, $fecha_limite, $tipo) {
        try {
            // Verificar que la actividad pertenece a un curso del docente
            $actividad = $this->docenteModel->obtenerActividadPorId($id_actividad);
            if (!$actividad || !$this->cursoModel->verificarDocenteCurso($_SESSION['id_usuario'], $actividad['id_curso'])) {
                throw new Exception("No tienes permiso para editar esta actividad");
            }

            return $this->docenteModel->editarActividad($id_actividad, $nombre, $descripcion, $fecha_limite, $tipo);
        } catch (Exception $e) {
            error_log("Error en editarActividad: " . $e->getMessage());
            return false;
        }
    }

    // Calificaciones
    public function calificarEntrega($id_estudiante, $id_curso, $id_actividad, $nota, $observaciones) {
        try {
            // Verificar que el docente es dueño del curso
            if (!$this->cursoModel->verificarDocenteCurso($_SESSION['id_usuario'], $id_curso)) {
                throw new Exception("No tienes permiso para calificar en este curso");
            }

            // Validar rango de nota
            if ($nota < 0 || $nota > 100) {
                throw new Exception("La nota debe estar entre 0 y 100");
            }

            return $this->docenteModel->calificarEntrega($id_estudiante, $id_curso, $id_actividad, $nota, $observaciones);
        } catch (Exception $e) {
            error_log("Error en calificarEntrega: " . $e->getMessage());
            return false;
        }
    }

    // Dashboard
    public function obtenerEstadisticasCurso($id_curso) {
        try {
            // Verificar que el docente es dueño del curso
            if (!$this->cursoModel->verificarDocenteCurso($_SESSION['id_usuario'], $id_curso)) {
                throw new Exception("No tienes permiso para ver estadísticas de este curso");
            }

            return $this->docenteModel->obtenerEstadisticasCurso($id_curso);
        } catch (Exception $e) {
            error_log("Error en obtenerEstadisticasCurso: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    public function obtenerEstudiantesEnRiesgo($id_curso) {
        try {
            // Verificar que el docente es dueño del curso
            if (!$this->cursoModel->verificarDocenteCurso($_SESSION['id_usuario'], $id_curso)) {
                throw new Exception("No tienes permiso para ver estudiantes de este curso");
            }

            return $this->docenteModel->obtenerEstudiantesEnRiesgo($id_curso);
        } catch (Exception $e) {
            error_log("Error en obtenerEstudiantesEnRiesgo: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    // Misiones
    public function crearMision($titulo, $descripcion, $recompensa, $id_grupo = null) {
        try {
            if (empty($titulo)) {
                throw new Exception("El título de la misión es requerido");
            }

            return $this->docenteModel->crearMision($titulo, $descripcion, $recompensa, $id_grupo);
        } catch (Exception $e) {
            error_log("Error en crearMision: " . $e->getMessage());
            return false;
        }
    }
}
?>