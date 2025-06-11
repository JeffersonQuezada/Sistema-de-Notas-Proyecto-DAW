<?php

require_once __DIR__ . '/../models/CursoModel.php';
require_once __DIR__ . '/../models/EntregaModel.php';
require_once __DIR__ . '/../models/NotaModel.php';
require_once __DIR__ . '/../models/InsigniaModel.php';
require_once __DIR__ . '/../models/MisionModel.php';

class DashboardController {
    private $cursoModel;
    private $entregaModel;
    private $notaModel;
    private $insigniaModel;
    private $misionModel;
    
    public function __construct() {
        $this->cursoModel = new CursoModel();
        $this->entregaModel = new EntregaModel();
        $this->notaModel = new NotaModel();
        $this->insigniaModel = new InsigniaModel();
        $this->misionModel = new MisionModel();
    }
    
    public function mostrarDashboard() {
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: ../login.php");
            exit();
        }
        
        $id_estudiante = $_SESSION['id_usuario'];
        
        // Obtener datos del dashboard
        $cursos = $this->cursoModel->listarCursosInscritos($id_estudiante);
        $entregas = $this->entregaModel->obtenerEntregasPorEstudiante($id_estudiante);
        $promedio = $this->entregaModel->obtenerPromedioGeneral($id_estudiante);
        $insignias = $this->insigniaModel->obtenerInsigniasPorEstudiante($id_estudiante);
        $misiones = $this->misionModel->obtenerMisionesActivasPorEstudiante($id_estudiante);
        
        // Verificar nuevas insignias
        $this->insigniaModel->verificarYOtorgarInsignias($id_estudiante);
        
        include __DIR__ . '/../views/dashboard_alumnos.php';
    }
    
    public function perfil() {
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: ../login.php");
            exit();
        }

        $id_estudiante = $_SESSION['id_usuario'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';

            if ($this->actualizarPerfil($id_estudiante, $nombre, $email)) {
                $_SESSION['nombre'] = $nombre;
                header('Location: index.php?accion=perfil&success=1');
                exit;
            } else {
                $error = "Error al actualizar el perfil";
            }
        }

        include __DIR__ . '/../views/perfil.php';
    }
    
    private function actualizarPerfil($id_estudiante, $nombre, $email) {
        try {
            global $pdo;
            $sql = "UPDATE usuarios SET nombre = ?, email = ? WHERE id_usuario = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$nombre, $email, $id_estudiante]);
        } catch (Exception $e) {
            return false;
        }
    }
}
?>