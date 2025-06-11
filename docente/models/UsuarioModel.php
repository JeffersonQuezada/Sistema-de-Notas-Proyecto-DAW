<?php
require_once __DIR__ . '/../../includes/conexion.php';

class UsuarioModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function crearUsuario($nombre, $email, $contrasena, $rol) {
        try {
            $sql = "INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nombre, $email, $contrasena, $rol]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear usuario: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerUsuarioPorId($id_usuario) {
        try {
            $sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_usuario]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuario: " . $e->getMessage());
            return false;
        }
    }

    public function verificarEmailExiste($email, $id_usuario = null) {
        try {
            $sql = "SELECT COUNT(*) FROM usuarios WHERE correo = ?";
            $params = [$email];
            
            if ($id_usuario) {
                $sql .= " AND id_usuario != ?";
                $params[] = $id_usuario;
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar email: " . $e->getMessage());
            return true;
        }
    }

    public function actualizarUsuario($id_usuario, $datos) {
        try {
            $campos = [];
            $valores = [];
            
            foreach ($datos as $campo => $valor) {
                $campos[] = "$campo = ?";
                $valores[] = $valor;
            }
            
            $valores[] = $id_usuario;
            
            $sql = "UPDATE usuarios SET " . implode(', ', $campos) . " WHERE id_usuario = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($valores);
        } catch (PDOException $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function listarUsuariosPorRol($rol) {
        try {
            $sql = "SELECT id_usuario, nombre, correo FROM usuarios WHERE rol = ? ORDER BY nombre";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$rol]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar usuarios: " . $e->getMessage());
            return [];
        }
    }

    public function autenticarUsuario($email, $contrasena) {
        try {
            $sql = "SELECT * FROM usuarios WHERE correo = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
                return $usuario;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error en autenticación: " . $e->getMessage());
            return false;
        }
    }
}
?>