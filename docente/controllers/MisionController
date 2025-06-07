<?php
require_once '../models/MisionModel.php';

class MisionController {
    private $misionModel;

    public function __construct() {
        $this->misionModel = new MisionModel();
    }

    public function crearMision($titulo, $descripcion, $recompensa, $fecha_limite) {
        return $this->misionModel->crearMision($titulo, $descripcion, $recompensa, $fecha_limite);
    }

    public function aceptarMision($id_mision, $id_estudiante) {
        return $this->misionModel->aceptarMision($id_mision, $id_estudiante);
    }

    public function finalizarMision($id_mision, $id_estudiante) {
        return $this->misionModel->finalizarMision($id_mision, $id_estudiante);
    }

    public function listarMisiones() {
        return $this->misionModel->listarMisiones();
    }
}
?>