<?php
require_once '../models/EstudianteModel.php';

class EstudianteController {
    private $estudianteModel;

    public function __construct() {
        $this->estudianteModel = new EstudianteModel();
    }

    public function asignarEstudianteAGrupo($id_estudiante, $id_grupo) {
        return $this->estudianteModel->asignarEstudianteAGrupo($id_estudiante, $id_grupo);
    }

    public function listarEstudiantesPorGrupo($id_grupo) {
        return $this->estudianteModel->listarEstudiantesPorGrupo($id_grupo);
    }
}
?>