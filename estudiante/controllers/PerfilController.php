<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

class PerfilController {
    private $usuarioModel;
    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }
    public function verPerfil() {
        $id_usuario = $_SESSION['id_usuario'];
        $perfil = $this->usuarioModel->obtenerPerfil($id_usuario);
        include '../views/perfil.php';
    }
    public function actualizarPerfil($nombre, $correo) {
        $id_usuario = $_SESSION['id_usuario'];
        $this->usuarioModel->actualizarPerfil($id_usuario, $nombre, $correo);
        header("Location: ../index.php?accion=perfil&success=1");
        exit();
    }
}
?>