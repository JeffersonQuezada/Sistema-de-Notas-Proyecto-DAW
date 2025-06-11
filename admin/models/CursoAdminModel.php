<?php
require_once __DIR__ . '/../../includes/conexion.php';
class CursoAdminModel {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function obtenerTodos() {
        $stmt = $this->pdo->query("
            SELECT c.*, u.nombre as nombre_docente 
            FROM cursos c
            JOIN usuarios u ON c.id_docente = u.id_usuario
            ORDER BY c.nombre_curso
        ");
        return $stmt->fetchAll();
    }
    
    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM cursos 
            WHERE id_curso = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function obtenerDocentes() {
        $stmt = $this->pdo->query("
            SELECT id_usuario, nombre 
            FROM usuarios 
            WHERE rol = 'docente'
            ORDER BY nombre
        ");
        return $stmt->fetchAll();
    }
    
    public function crear($datos) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO cursos 
                (nombre_curso, descripcion, id_docente, contrasena, capacidad, grupo) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            return $stmt->execute([
                $datos['nombre_curso'],
                $datos['descripcion'],
                $datos['id_docente'],
                password_hash($datos['contrasena'], PASSWORD_DEFAULT),
                $datos['capacidad'],
                $datos['grupo'] ?? null
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE cursos SET 
                nombre_curso = ?, 
                descripcion = ?, 
                id_docente = ?, 
                capacidad = ?, 
                grupo = ?";
            
            $params = [
                $datos['nombre_curso'],
                $datos['descripcion'],
                $datos['id_docente'],
                $datos['capacidad'],
                $datos['grupo'] ?? null
            ];
            
            if (!empty($datos['contrasena'])) {
                $sql .= ", contrasena = ?";
                $params[] = password_hash($datos['contrasena'], PASSWORD_DEFAULT);
            }
            
            $sql .= " WHERE id_curso = ?";
            $params[] = $id;
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function eliminar($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM cursos WHERE id_curso = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['error'] = 'No se puede eliminar el curso porque tiene registros asociados';
            }
            return false;
        }
    }
}