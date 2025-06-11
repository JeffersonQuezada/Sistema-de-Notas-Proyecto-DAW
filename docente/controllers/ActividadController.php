<?php
require_once __DIR__ . '/../models/ActividadModel.php';
require_once __DIR__ . '/../models/CursoModel.php';

class ActividadController {
    private $actividadModel;
    private $cursoModel;

    public function __construct() {
        $this->actividadModel = new ActividadModel();
        $this->cursoModel = new CursoModel();
    }

    public function crearActividad($nombre, $descripcion, $fecha_limite, $id_curso, $tipo = 'Tarea') {
        try {
            // Validar campos requeridos
            if (empty($nombre) || empty($descripcion) || empty($fecha_limite) || empty($id_curso)) {
                throw new Exception("Todos los campos son obligatorios");
            }

            // Validar tipo de actividad
            $tiposPermitidos = ['Tarea', 'Examen', 'Proyecto'];
            if (!in_array($tipo, $tiposPermitidos)) {
                throw new Exception("Tipo de actividad no válido");
            }

            // Verificar que el curso existe y pertenece al docente
            if ($_SESSION['rol'] === 'docente') {
                $curso = $this->cursoModel->obtenerCursoPorId($id_curso);
                if (!$curso || $curso['id_docente'] != $_SESSION['id_usuario']) {
                    throw new Exception("No tienes permisos para crear actividades en este curso");
                }
            }

            return $this->actividadModel->crearActividad($nombre, $descripcion, $fecha_limite, $id_curso, $tipo);
            
        } catch (Exception $e) {
            error_log("Error en crearActividad: " . $e->getMessage());
            return false;
        }
    }

    public function editarActividad($id_actividad, $nombre, $descripcion, $fecha_limite, $tipo) {
        try {
            // Obtener actividad para verificar permisos
            $actividad = $this->actividadModel->obtenerActividadPorId($id_actividad);
            if (!$actividad) {
                throw new Exception("Actividad no encontrada");
            }

            // Verificar permisos (docente del curso)
            if ($_SESSION['rol'] === 'docente') {
                $curso = $this->cursoModel->obtenerCursoPorId($actividad['id_curso']);
                if (!$curso || $curso['id_docente'] != $_SESSION['id_usuario']) {
                    throw new Exception("No tienes permisos para editar esta actividad");
                }
            }

            return $this->actividadModel->editarActividad($id_actividad, $nombre, $descripcion, $fecha_limite, $tipo);
            
        } catch (Exception $e) {
            error_log("Error en editarActividad: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarActividad($id_actividad) {
        try {
            // Obtener actividad para verificar permisos
            $actividad = $this->actividadModel->obtenerActividadPorId($id_actividad);
            if (!$actividad) {
                throw new Exception("Actividad no encontrada");
            }

            // Verificar permisos (docente del curso)
            if ($_SESSION['rol'] === 'docente') {
                $curso = $this->cursoModel->obtenerCursoPorId($actividad['id_curso']);
                if (!$curso || $curso['id_docente'] != $_SESSION['id_usuario']) {
                    throw new Exception("No tienes permisos para eliminar esta actividad");
                }
            }

            return $this->actividadModel->eliminarActividad($id_actividad);
            
        } catch (Exception $e) {
            error_log("Error en eliminarActividad: " . $e->getMessage());
            return false;
        }
    }

    public function listarActividadesPorCurso($id_curso) {
        try {
            // Verificar permisos para ver el curso
            if ($_SESSION['rol'] === 'docente') {
                $curso = $this->cursoModel->obtenerCursoPorId($id_curso);
                if (!$curso || $curso['id_docente'] != $_SESSION['id_usuario']) {
                    throw new Exception("No tienes permisos para ver actividades de este curso");
                }
            } elseif ($_SESSION['rol'] === 'estudiante') {
                $inscrito = $this->cursoModel->verificarInscripcion($id_curso, $_SESSION['id_usuario']);
                if (!$inscrito) {
                    throw new Exception("No estás inscrito en este curso");
                }
            }

            return $this->actividadModel->listarActividadesPorCurso($id_curso);
            
        } catch (Exception $e) {
            error_log("Error en listarActividadesPorCurso: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
?>