<?php
require_once __DIR__ . '/../models/InsigniaModel.php';

class InsigniaController {
    private $insigniaModel;
    
    public function __construct() {
        $this->insigniaModel = new InsigniaModel();
    }
    
    public function mostrarInsignias() {
        $id_estudiante = $_SESSION['id_usuario'];
        $insignias = $this->insigniaModel->obtenerInsigniasPorEstudiante($id_estudiante);
        $insignias_disponibles = $this->insigniaModel->obtenerInsigniasDisponibles();

        include __DIR__ . '/../views/insignias.php';
    }
}

?>