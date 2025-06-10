<?php
require_once __DIR__ . '/../models/EntregaModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

class EntregaController {
    private $entregaModel;
    public function __construct() {
        $this->entregaModel = new EntregaModel();
    }
    public function entregar($id_actividad) {
        $id_estudiante = $_SESSION['id_usuario'];
        $archivo = $_FILES['archivo']['name'];
        $ruta = '../../uploads/' . basename($archivo);
        move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta);
        $this->entregaModel->entregarActividad($id_estudiante, $id_actividad, $archivo);
        header("Location: ../index.php?accion=mis_entregas&success=1");
        exit();
    }
    public function misEntregas() {
        $id_estudiante = $_SESSION['id_usuario'];
        $entregas = $this->entregaModel->obtenerEntregasPorEstudiante($id_estudiante);
        include '../views/mis_entregas.php';
    }
}
?>