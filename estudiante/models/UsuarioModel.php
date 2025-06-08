<?php
require_once '../../includes/conexion.php';

class UsuarioModel {
    private $pdo;
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    public function obtenerPerfil($id_usuario) {
        $sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetch();
    }
    public function actualizarPerfil($id_usuario, $nombre, $correo) {
        $sql = "UPDATE usuarios SET nombre = ?, correo = ? WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre, $correo, $id_usuario]);
    }
}
?>