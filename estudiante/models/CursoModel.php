<?php
require_once __DIR__ . '/../../includes/conexion.php';

class CursoModel {
    private $pdo;
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    public function listarCursosDisponibles($id_estudiante) {
        $sql = "SELECT c.*, u.nombre AS profesor_nombre,
                       (SELECT COUNT(*) FROM estudiantes_cursos ec WHERE ec.id_curso = c.id_curso) AS inscritos,
                       EXISTS(SELECT 1 FROM estudiantes_cursos ec WHERE ec.id_curso = c.id_curso AND ec.id_estudiante = ?) AS ya_inscrito
                FROM cursos c
                LEFT JOIN usuarios u ON c.id_docente = u.id_usuario
                ORDER BY c.nombre_curso";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll();
    }
    public function inscribirEstudiante($id_estudiante, $id_curso) {
        $sql = "INSERT IGNORE INTO estudiantes_cursos (id_estudiante, id_curso) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_estudiante, $id_curso]);
    }
    public function desinscribirEstudiante($id_estudiante, $id_curso) {
        $sql = "DELETE FROM estudiantes_cursos WHERE id_estudiante = ? AND id_curso = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_estudiante, $id_curso]);
    }
    public function obtenerCursoPorId($id_curso) {
        $sql = "SELECT c.*, u.nombre AS profesor_nombre FROM cursos c LEFT JOIN usuarios u ON c.id_docente = u.id_usuario WHERE c.id_curso = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_curso]);
        return $stmt->fetch();
    }
    public function listarCursosInscritos($id_estudiante) {
        $sql = "SELECT c.*, u.nombre AS profesor_nombre
                FROM cursos c
                JOIN estudiantes_cursos ec ON c.id_curso = ec.id_curso
                LEFT JOIN usuarios u ON c.id_docente = u.id_usuario
                WHERE ec.id_estudiante = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll();
    }
    public function listarTodosLosCursos() {
        $sql = "SELECT c.*, u.nombre AS profesor_nombre
                FROM cursos c
                LEFT JOIN usuarios u ON c.id_docente = u.id_usuario
                ORDER BY c.nombre_curso";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function listarCursosConEstado($id_estudiante) {
        $sql = "SELECT c.id_curso, c.nombre_curso, c.descripcion, c.capacidad, c.grupo,
                   u.nombre AS profesor_nombre,
                   EXISTS(SELECT 1 FROM estudiantes_cursos ec WHERE ec.id_curso = c.id_curso AND ec.id_estudiante = ?) AS ya_inscrito
            FROM cursos c
            LEFT JOIN usuarios u ON c.id_docente = u.id_usuario
            ORDER BY c.nombre_curso";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll();
    }
}
?>