<?php
require_once '../models/ActividadModel.php';

class ActividadController {
    private $actividadModel;

    public function __construct() {
        $this->actividadModel = new ActividadModel();
    }

    public function crearActividad($titulo, $descripcion, $fecha_entrega, $id_grupo) {
        return $this->actividadModel->crearActividad($titulo, $descripcion, $fecha_entrega, $id_grupo);
    }

    public function editarActividad($id, $titulo, $descripcion, $fecha_entrega, $id_grupo) {
        return $this->actividadModel->editarActividad($id, $titulo, $descripcion, $fecha_entrega, $id_grupo);
    }

    public function eliminarActividad($id) {
        return $this->actividadModel->eliminarActividad($id);
    }

    public function listarActividades() {
        return $this->actividadModel->listarActividades();
    }
}
?>