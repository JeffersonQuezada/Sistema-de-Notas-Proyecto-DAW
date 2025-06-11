<?php
require_once __DIR__ . '/../../includes/conexion.php';
class UsuarioModel {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function obtenerTodos() {
        $stmt = $this->pdo->query("SELECT * FROM usuarios ORDER BY nombre");
        return $stmt->fetchAll();
    }
    
    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function crear($datos) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO usuarios (nombre, correo, contrasena, rol) 
                VALUES (?, ?, ?, ?)
            ");
            
            $contrasenaHash = password_hash($datos['contrasena'], PASSWORD_DEFAULT);
            
            return $stmt->execute([
                $datos['nombre'],
                $datos['correo'],
                $contrasenaHash,
                $datos['rol']
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['error'] = 'El correo electrónico ya está registrado';
            }
            return false;
        }
    }
    
    public function actualizar($id, $datos) {
        $sql = "UPDATE usuarios SET nombre = ?, correo = ?, rol = ?";
        $params = [$datos['nombre'], $datos['correo'], $datos['rol']];
        
        if (!empty($datos['contrasena'])) {
            $sql .= ", contrasena = ?";
            $params[] = password_hash($datos['contrasena'], PASSWORD_DEFAULT);
        }
        
        $sql .= " WHERE id_usuario = ?";
        $params[] = $id;
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function eliminar($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['error'] = 'No se puede eliminar el usuario porque tiene registros asociados';
            }
            return false;
        }
    }
}