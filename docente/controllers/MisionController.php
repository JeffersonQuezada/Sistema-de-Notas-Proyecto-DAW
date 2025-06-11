<?php
require_once '../models/MisionModel.php';

class MisionController {
    private $misionModel;

    public function __construct() {
        $this->misionModel = new MisionModel();
    }

    public function crearMision($titulo, $descripcion, $recompensa, $fecha_fin = null) {
        try {
            if (empty($titulo) || empty($descripcion) || empty($recompensa)) {
                throw new Exception("Todos los campos son requeridos");
            }
            return $this->misionModel->crearMision($titulo, $descripcion, $recompensa, $fecha_fin);
        } catch (Exception $e) {
            error_log("Error en crearMision: " . $e->getMessage());
            return false;
        }
    }

    public function aceptarMision($id_mision, $id_estudiante) {
        try {
            // Verificar que el estudiante existe
            if (!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] != $id_estudiante) {
                throw new Exception("Acceso no autorizado");
            }
            return $this->misionModel->aceptarMision($id_mision, $id_estudiante);
        } catch (Exception $e) {
            error_log("Error en aceptarMision: " . $e->getMessage());
            return false;
        }
    }

    public function finalizarMision($id_mision, $id_estudiante) {
        try {
            // Verificar que el estudiante existe y es quien dice ser
            if (!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] != $id_estudiante) {
                throw new Exception("Acceso no autorizado");
            }
            return $this->misionModel->finalizarMision($id_mision, $id_estudiante);
        } catch (Exception $e) {
            error_log("Error en finalizarMision: " . $e->getMessage());
            return false;
        }
    }

    public function listarMisiones($id_grupo = null) {
        try {
            return $this->misionModel->listarMisiones($id_grupo);
        } catch (Exception $e) {
            error_log("Error en listarMisiones: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
?>