<?php
require_once '../models/GrupoModel.php';

class GrupoController {
    private $grupoModel;

    public function __construct() {
        $this->grupoModel = new GrupoModel();
    }

    public function crearGrupo($nombre, $id_usuario) {
        try {
            if (empty($nombre)) {
                throw new Exception("El nombre del grupo es requerido");
            }
            return $this->grupoModel->crearGrupo($nombre, $id_usuario);
        } catch (Exception $e) {
            error_log("Error en crearGrupo: " . $e->getMessage());
            return false;
        }
    }

    public function editarGrupo($id_grupo, $nombre, $id_usuario) {
        try {
            if (empty($nombre)) {
                throw new Exception("El nombre del grupo es requerido");
            }
            return $this->grupoModel->editarGrupo($id_grupo, $nombre, $id_usuario);
        } catch (Exception $e) {
            error_log("Error en editarGrupo: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarGrupo($id_grupo, $id_usuario) {
        try {
            return $this->grupoModel->eliminarGrupo($id_grupo, $id_usuario);
        } catch (Exception $e) {
            error_log("Error en eliminarGrupo: " . $e->getMessage());
            return false;
        }
    }

    public function asignarEstudianteGrupo($id_estudiante, $id_grupo) {
        try {
            // Verificar que el grupo pertenece al docente
            $grupo = $this->grupoModel->obtenerGrupoPorId($id_grupo);
            if (!$grupo || $grupo['id_usuario'] != $_SESSION['id_usuario']) {
                throw new Exception("No tienes permiso para asignar estudiantes a este grupo");
            }

            return $this->grupoModel->asignarEstudianteGrupo($id_estudiante, $id_grupo);
        } catch (Exception $e) {
            error_log("Error en asignarEstudianteGrupo: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerEstudiantesPorGrupo($id_grupo) {
        try {
            // Verificar que el grupo pertenece al docente
            $grupo = $this->grupoModel->obtenerGrupoPorId($id_grupo);
            if (!$grupo || $grupo['id_usuario'] != $_SESSION['id_usuario']) {
                throw new Exception("No tienes permiso para ver este grupo");
            }

            return $this->grupoModel->obtenerEstudiantesPorGrupo($id_grupo);
        } catch (Exception $e) {
            error_log("Error en obtenerEstudiantesPorGrupo: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
?>