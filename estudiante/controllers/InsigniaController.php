<?php
require_once '../models/InsigniaModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

class InsigniaController {
    private $insigniaModel;
    public function __construct() {
        $this->insigniaModel = new InsigniaModel();
    }
    public function mostrarInsignias() {
        $id_estudiante = $_SESSION['id_usuario'];
        $insignias = $this->insigniaModel->listarInsigniasPorEstudiante($id_estudiante);
        include '../views/insignias.php';
    }
}
?>