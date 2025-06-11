<?php
require_once '../models/EstudianteModel.php';
require_once '../models/GrupoModel.php';

class EstudianteController {
    private $estudianteModel;
    private $grupoModel;

    public function __construct() {
        $this->estudianteModel = new EstudianteModel();
        $this->grupoModel = new GrupoModel();
    }

    public function asignarEstudianteAGrupo($id_estudiante, $id_grupo) {
        try {
            // Verificar que el usuario que hace la asignación tiene permisos
            if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'docente') {
                throw new Exception("Acceso no autorizado");
            }

            // Verificar que el grupo pertenece al docente
            $grupo = $this->grupoModel->obtenerGrupoPorId($id_grupo);
            if (!$grupo || $grupo['id_docente'] != $_SESSION['id_usuario']) {
                throw new Exception("No tienes permiso para asignar estudiantes a este grupo");
            }

            return $this->estudianteModel->asignarEstudianteAGrupo($id_estudiante, $id_grupo);
        } catch (Exception $e) {
            error_log("Error en asignarEstudianteAGrupo: " . $e->getMessage());
            return false;
        }
    }

    public function listarEstudiantesPorGrupo($id_grupo) {
        try {
            // Verificar permisos (docente dueño del grupo o estudiante del grupo)
            if (isset($_SESSION['rol'])) {
                if ($_SESSION['rol'] === 'docente') {
                    $grupo = $this->grupoModel->obtenerGrupoPorId($id_grupo);
                    if (!$grupo || $grupo['id_docente'] != $_SESSION['id_usuario']) {
                        throw new Exception("No tienes permiso para ver este grupo");
                    }
                } elseif ($_SESSION['rol'] === 'estudiante') {
                    $enGrupo = $this->estudianteModel->verificarEstudianteEnGrupo($_SESSION['id_usuario'], $id_grupo);
                    if (!$enGrupo) {
                        throw new Exception("No tienes permiso para ver este grupo");
                    }
                } else {
                    throw new Exception("Acceso no autorizado");
                }
            } else {
                throw new Exception("Acceso no autorizado");
            }

            return $this->estudianteModel->listarEstudiantesPorGrupo($id_grupo);
        } catch (Exception $e) {
            error_log("Error en listarEstudiantesPorGrupo: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
?>