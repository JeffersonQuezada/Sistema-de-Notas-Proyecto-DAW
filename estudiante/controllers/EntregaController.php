<?php
require_once __DIR__ . '/../models/EntregaModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

class EntregaController {
    private $entregaModel;
    
    public function __construct() {
        $this->entregaModel = new EntregaModel();
    }
    
    public function entregar($id_actividad) {
        if (!isset($_SESSION['id_usuario']) || empty($_FILES['archivo'])) {
            header("Location: ../index.php?accion=ver_actividad&id=$id_actividad&error=1");
            exit();
        }
        
        $id_estudiante = $_SESSION['id_usuario'];
        $archivo = $_FILES['archivo'];
        
        // Validar archivo
        $extensionesPermitidas = ['pdf', 'doc', 'docx', 'zip', 'rar'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, $extensionesPermitidas)) {
            header("Location: ../index.php?accion=ver_actividad&id=$id_actividad&error=2");
            exit();
        }
        
        // Mover archivo
        $nombreArchivo = uniqid() . '_' . basename($archivo['name']);
        $ruta = '../../uploads/' . $nombreArchivo;
        
        if (move_uploaded_file($archivo['tmp_name'], $ruta)) {
            $this->entregaModel->entregarActividad($id_actividad, $id_estudiante, $nombreArchivo, null);
            header("Location: ../index.php?accion=mis_entregas&success=1");
        } else {
            header("Location: ../index.php?accion=ver_actividad&id=$id_actividad&error=3");
        }
        exit();
    }
    
    public function misEntregas() {
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: ../login.php");
            exit();
        }
        
        $id_estudiante = $_SESSION['id_usuario'];
        $entregas = $this->entregaModel->obtenerEntregasPorEstudiante($id_estudiante);
        include __DIR__ . '/../views/mis_entregas.php';
    }

    public function guardarEntrega() {
        $id_estudiante = $_SESSION['id_usuario'];
        $id_actividad = $_POST['id_actividad'];
        $archivo = $_FILES['archivo']['name'];
        // ...subida de archivo...
        $comentario = $_POST['comentario'] ?? '';
        $this->entregaModel->guardarEntrega($id_actividad, $id_estudiante, $archivo, $comentario);
        header("Location: index.php?accion=mis_entregas&success=1");
        exit();
    }
}
?>