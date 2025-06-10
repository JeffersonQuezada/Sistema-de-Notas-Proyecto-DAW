<?php
require_once __DIR__ . '/../../includes/conexion.php';

class CursoModel {
    private $pdo;
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    public function listarCursos() {
        $sql = "SELECT c.*, u.nombre as docente FROM cursos c JOIN usuarios u ON c.id_docente = u.id_usuario ORDER BY c.nombre_curso";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function crearCurso($nombre, $descripcion, $id_docente, $contrasena, $capacidad) {
        $sql = "INSERT INTO cursos (nombre_curso, descripcion, id_docente, contrasena, capacidad) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $hashed = password_hash($contrasena, PASSWORD_DEFAULT);
        return $stmt->execute([$nombre, $descripcion, $id_docente, $hashed, $capacidad]);
    }
    public function obtenerCursoPorId($id_curso) {
        $sql = "SELECT c.*, u.nombre as docente FROM cursos c JOIN usuarios u ON c.id_docente = u.id_usuario WHERE c.id_curso = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_curso]);
        return $stmt->fetch();
    }
    public function actualizarCurso($id_curso, $nombre, $descripcion, $id_docente, $capacidad) {
        $sql = "UPDATE cursos SET nombre_curso = ?, descripcion = ?, id_docente = ?, capacidad = ? WHERE id_curso = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $id_docente, $capacidad, $id_curso]);
    }
    public function eliminarCurso($id_curso) {
        $sql = "DELETE FROM cursos WHERE id_curso = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_curso]);
    }
}
?>