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
        $sql = "SELECT * FROM cursos WHERE id_curso = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
    
    public function crearCurso($datos) {
        // Encriptar la contraseña antes de guardar
        $contrasenaHash = password_hash($datos['contrasena'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO cursos (nombre_curso, id_docente, capacidad, grupo, contrasena) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $datos['nombre_curso'],
            $datos['id_docente'],
            $datos['capacidad'],
            $datos['grupo'],
            $contrasenaHash
        ]);
    }
    
    public function actualizar($id, $datos) {
        $params = [
            $datos['nombre_curso'],
            $datos['id_docente'],
            $datos['capacidad'],
            $datos['grupo']
        ];

        $sql = "UPDATE cursos SET nombre_curso = ?, id_docente = ?, capacidad = ?, grupo = ?";

        if (!empty($datos['contrasena'])) {
            $sql .= ", contrasena = ?";
            $params[] = password_hash($datos['contrasena'], PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id_curso = ?";
        $params[] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
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
    
    public function buscar($busqueda) {
        $sql = "SELECT c.*, u.nombre as nombre_docente 
                FROM cursos c
                JOIN usuarios u ON c.id_docente = u.id_usuario
                WHERE c.nombre_curso LIKE ? OR u.nombre LIKE ?
                ORDER BY c.nombre_curso";
        $stmt = $this->pdo->prepare($sql);
        $like = "%$busqueda%";
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }
}