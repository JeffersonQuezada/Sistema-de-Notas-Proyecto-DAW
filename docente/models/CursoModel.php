<?php
require_once __DIR__ . '/../../includes/conexion.php';

class CursoModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function crearCurso($nombre_curso, $descripcion, $id_docente, $contrasena, $capacidad = 50, $grupo = null) {
    $sql = "INSERT INTO cursos (nombre_curso, descripcion, id_docente, contrasena, capacidad, grupo) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$nombre_curso, $descripcion, $id_docente, $contrasena, $capacidad, $grupo]);
}
    public function listarCursosPorDocente($id_docente) {
        $sql = "SELECT * FROM cursos WHERE id_docente = :id_docente";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_docente' => $id_docente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCursoPorId($id_curso) {
        $sql = "SELECT * FROM cursos WHERE id_curso = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_curso]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verificarDocenteCurso($id_docente, $id_curso) {
        // Cambiado a la tabla correcta
        $sql = "SELECT COUNT(*) FROM cursos WHERE id_docente = ? AND id_curso = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_docente, $id_curso]);
        return $stmt->fetchColumn() > 0;
    }
}
?>