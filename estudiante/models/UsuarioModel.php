<?php
require_once __DIR__ . '/../../includes/conexion.php';

class UsuarioModel {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function obtenerPerfil($id_usuario) {
        $sql = "SELECT id_usuario, nombre, correo, rol, fecha_registro 
                FROM usuarios 
                WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function actualizarPerfil($id_usuario, $nombre, $correo) {
        // Verificar si el correo ya existe para otro usuario
        $sql = "SELECT id_usuario FROM usuarios WHERE correo = ? AND id_usuario != ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$correo, $id_usuario]);
        
        if ($stmt->fetch()) {
            return false; // Correo ya en uso
        }
        
        $sql = "UPDATE usuarios SET nombre = ?, correo = ? WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre, $correo, $id_usuario]);
    }
    
    public function verificarCredenciales($correo, $contrasena) {
        $sql = "SELECT id_usuario, nombre, rol, contrasena 
                FROM usuarios 
                WHERE correo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            unset($usuario['contrasena']);
            return $usuario;
        }
        return false;
    }
    
    public function obtenerUsuarioPorId($id_usuario) {
        $sql = "SELECT id_usuario, nombre, correo, rol 
                FROM usuarios 
                WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>