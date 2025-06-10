<?php
require_once __DIR__ . '/../../includes/conexion.php';

class UsuarioModel {
    private $pdo;
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    public function listarUsuarios($rol = null) {
        $sql = "SELECT * FROM usuarios";
        $params = [];
        if ($rol) {
            $sql .= " WHERE rol = ?";
            $params[] = $rol;
        }
        $sql .= " ORDER BY fecha_registro DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    public function crearUsuario($nombre, $correo, $contrasena, $rol) {
        $sql = "INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $hashed = password_hash($contrasena, PASSWORD_DEFAULT);
        return $stmt->execute([$nombre, $correo, $hashed, $rol]);
    }
    public function obtenerUsuarioPorId($id_usuario) {
        $sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetch();
    }
    public function actualizarUsuario($id_usuario, $nombre, $correo, $rol) {
        $sql = "UPDATE usuarios SET nombre = ?, correo = ?, rol = ? WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre, $correo, $rol, $id_usuario]);
    }
    public function eliminarUsuario($id_usuario) {
        $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_usuario]);
    }
    public function existeCorreo($correo) {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE correo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$correo]);
        return $stmt->fetchColumn() > 0;
    }
}
?>