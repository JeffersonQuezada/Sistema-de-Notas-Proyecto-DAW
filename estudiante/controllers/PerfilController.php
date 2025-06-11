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
        include __DIR__.'/../views/perfil.php';
    }
    
    public function actualizarPerfil($nombre, $correo) {
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: ../login.php");
            exit();
        }
        
        $id_usuario = $_SESSION['id_usuario'];
        $resultado = $this->usuarioModel->actualizarPerfil($id_usuario, $nombre, $correo);
        
        if ($resultado) {
            $_SESSION['nombre'] = $nombre;
            $_SESSION['correo'] = $correo; // Opcional
            header("Location: ../index.php?accion=perfil&success=1");
        } else {
            header("Location: ../index.php?accion=perfil&error=1");
        }
        exit();
    }
}
?>