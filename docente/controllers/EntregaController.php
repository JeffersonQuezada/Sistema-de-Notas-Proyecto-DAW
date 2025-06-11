<?php
require_once '../models/EntregaModel.php';
require_once '../models/CursoModel.php';

class EntregaController {
    private $entregaModel;
    private $cursoModel;

    public function __construct() {
        $this->entregaModel = new EntregaModel();
        $this->cursoModel = new CursoModel();
    }

    public function listarEntregasPorActividad($id_actividad) {
        try {
            // Verificar que la actividad pertenece a un curso del docente (si es docente)
            if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'docente') {
                $actividad = $this->entregaModel->obtenerActividadPorId($id_actividad);
                if (!$actividad || !$this->cursoModel->verificarDocenteCurso($_SESSION['id_usuario'], $actividad['id_curso'])) {
                    throw new Exception("No tienes permiso para ver estas entregas");
                }
            }

            return $this->entregaModel->listarEntregasPorActividad($id_actividad);
        } catch (Exception $e) {
            error_log("Error en listarEntregasPorActividad: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    public function calificarEntrega($id_entrega, $calificacion, $comentario) {
        try {
            // Verificar permisos (docente del curso)
            if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'docente') {
                throw new Exception("Acceso no autorizado");
            }

            $entrega = $this->entregaModel->obtenerEntregaPorId($id_entrega);
            if (!$entrega) {
                throw new Exception("Entrega no encontrada");
            }

            $actividad = $this->entregaModel->obtenerActividadPorId($entrega['id_actividad']);
            if (!$actividad || !$this->cursoModel->verificarDocenteCurso($_SESSION['id_usuario'], $actividad['id_curso'])) {
                throw new Exception("No tienes permiso para calificar esta entrega");
            }

            return $this->entregaModel->calificarEntrega($id_entrega, $calificacion, $comentario);
        } catch (Exception $e) {
            error_log("Error en calificarEntrega: " . $e->getMessage());
            return false;
        }
    }
}
?>