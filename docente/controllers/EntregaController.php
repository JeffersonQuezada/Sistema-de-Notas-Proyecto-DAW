<?php
require_once '../models/EntregaModel.php';

class EntregaController {
    private $entregaModel;

    public function __construct() {
        $this->entregaModel = new EntregaModel();
    }

    public function listarEntregasPorActividad($id_actividad) {
        return $this->entregaModel->listarEntregasPorActividad($id_actividad);
    }

    public function calificarEntrega($id_entrega, $calificacion, $comentario) {
        return $this->entregaModel->calificarEntrega($id_entrega, $calificacion, $comentario);
    }
}
?>