<?php
require_once __DIR__ . '/../../includes/conexion.php';

class MisionModel {
    private $pdo;
    public function __construct() {
        $this->pdo = $GLOBALS['pdo'];
    }

    public function obtenerTodas() {
        $stmt = $this->pdo->query("SELECT * FROM misiones ORDER BY fecha_inicio DESC");
        return $stmt->fetchAll();
    }

    public function crear($datos) {
        $sql = "INSERT INTO misiones (titulo, descripcion, recompensa, id_grupo, fecha_inicio, fecha_fin, prioridad)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $datos['titulo'],
            $datos['descripcion'],
            $datos['recompensa'],
            $datos['id_grupo'] ?: null,
            $datos['fecha_inicio'],
            $datos['fecha_fin'],
            $datos['prioridad'] ?? 1
        ]);
    }

    public function asignarMision($id_mision, $id_usuario) {
        $sql = "INSERT IGNORE INTO misiones_estudiantes (id_mision, id_usuario, completado) VALUES (?, ?, 0)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_mision, $id_usuario]);
    }

    public function asignarInsignia($id_usuario, $id_insignia) {
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO insignias_estudiantes (id_insignia, id_usuario) VALUES (?, ?)");
        return $stmt->execute([$id_insignia, $id_usuario]);
    }

    public function obtenerUsuariosPorRol($rol) {
        $stmt = $this->pdo->prepare("SELECT id_usuario, nombre FROM usuarios WHERE rol = ?");
        $stmt->execute([$rol]);
        return $stmt->fetchAll();
    }

    public function marcarCompletada($id_mision, $id_usuario) {
        $sql = "UPDATE misiones_estudiantes SET completado = 1 WHERE id_mision = ? AND id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_mision, $id_usuario]);
    }

    public function obtenerUltimoIdInsertado() {
        return $this->pdo->lastInsertId();
    }

    public function obtenerUsuariosPorCurso($id_curso) {
        // Estudiantes inscritos
        $stmt = $this->pdo->prepare("SELECT u.id_usuario, u.nombre FROM usuarios u
            JOIN estudiantes_cursos ec ON u.id_usuario = ec.id_estudiante
            WHERE ec.id_curso = ?");
        $stmt->execute([$id_curso]);
        $estudiantes = $stmt->fetchAll();

        // Docente del curso
        $stmt2 = $this->pdo->prepare("SELECT u.id_usuario, u.nombre FROM usuarios u
            JOIN cursos c ON u.id_usuario = c.id_docente
            WHERE c.id_curso = ?");
        $stmt2->execute([$id_curso]);
        $docente = $stmt2->fetchAll();

        return array_merge($estudiantes, $docente);
    }

    public function obtenerTodosLosCursos() {
        $stmt = $this->pdo->query("SELECT id_curso, nombre_curso FROM cursos ORDER BY nombre_curso");
        return $stmt->fetchAll();
    }
}