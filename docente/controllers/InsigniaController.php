<?php
require_once __DIR__ . '/../models/InsigniaModel.php';
require_once __DIR__ . '/../views/insignias_listado.php';

class InsigniaController {
    private $model;
    public function __construct() {
        $this->model = new InsigniaModel();
    }

    public function listarInsignias() {
        $insignias = $this->model->obtenerTodas();
        include __DIR__ . '/../views/insignias_listado.php';
    }

    public function asignar($id_usuario, $id_insignia) {
        $this->model->asignarInsignia($id_usuario, $id_insignia);
        header('Location: index.php?accion=insignias');
        exit;
    }
}