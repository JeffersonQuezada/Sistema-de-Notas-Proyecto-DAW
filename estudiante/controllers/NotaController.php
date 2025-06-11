<?php
require_once __DIR__ . '/../models/NotaModel.php';
require_once __DIR__ . '/../models/InsigniaModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

class NotaController {
    private $notaModel;
    
    public function __construct() {
        $this->notaModel = new NotaModel();
    }
    
    public function misNotas() {
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: ../login.php");
            exit();
        }
        
        $id_estudiante = $_SESSION['id_usuario'];
        $notas = $this->notaModel->obtenerNotasPorEstudiante($id_estudiante);
        $promedios = $this->notaModel->calcularPromediosPorCurso($id_estudiante);
        $promedio_general = $this->notaModel->calcularPromedioGeneral($id_estudiante);
        
        include __DIR__ . '/../views/mis_notas.php';
    }
}
?>