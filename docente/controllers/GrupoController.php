<?php
include_once '../models/GrupoModel.php';

class GrupoController {
    private $grupoModel;

    public function __construct() {
        $this->grupoModel = new GrupoModel();
    }

    public function crearGrupo($nombre, $id_docente) {
        return $this->grupoModel->crearGrupo($nombre, $id_docente);
    }

    public function editarGrupo($id_grupo, $nombre, $id_docente) {
        return $this->grupoModel->editarGrupo($id_grupo, $nombre, $id_docente);
    }

    public function eliminarGrupo($id_grupo, $id_docente) {
        return $this->grupoModel->eliminarGrupo($id_grupo, $id_docente);
    }

    public function asignarEstudianteGrupo($id_usuario, $id_grupo) {
        return $this->grupoModel->asignarEstudianteGrupo($id_usuario, $id_grupo);
    }

    public function obtenerEstudiantesPorGrupo($id_grupo) {
        return $this->grupoModel->obtenerEstudiantesPorGrupo($id_grupo);
    }
}
?>