<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

class PerfilController {
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }
    
    public function verPerfil() {
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: ../login.php");
            exit();
        }
        
        $id_usuario = $_SESSION['id_usuario'];
        $perfil = $this->usuarioModel->obtenerPerfil($id_usuario);
        include __DIR__ . '/../views/perfil.php';
    }

    public function mostrarFormularioCambioContrasena() {
        include __DIR__ . '/../views/cambiar_contrasena.php';
    }

    public function cambiarContrasena($actual, $nueva) {
        $id_usuario = $_SESSION['id_usuario'];
        // Verifica la contraseña actual
        if ($this->usuarioModel->verificarContrasena($id_usuario, $actual)) {
            $this->usuarioModel->actualizarContrasena($id_usuario, $nueva);
            header("Location: ../index.php?accion=perfil&success=1");
        } else {
            header("Location: ../index.php?accion=cambiar_contrasena&error=1");
        }
        exit();
    }
}
?>