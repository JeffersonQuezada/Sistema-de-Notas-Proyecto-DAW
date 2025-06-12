<?php
require_once __DIR__ . '/../../includes/conexion.php';

class EntregaModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function listarEntregasPorActividad($id_actividad) {
        try {
            $sql = "SELECT e.*, u.nombre as nombre_estudiante
                    FROM entregas e
                    JOIN usuarios u ON e.id_estudiante = u.id_usuario
                    WHERE e.id_actividad = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_actividad]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar entregas: " . $e->getMessage());
            return [];
        }
    }

    public function calificarEntrega($id_entrega, $calificacion, $comentario) {
        try {
            // Obtener información de la entrega
            $entrega = $this->obtenerEntregaPorId($id_entrega);
            if (!$entrega) {
                throw new Exception("Entrega no encontrada");
            }

            // Verificar si ya existe una nota para esta entrega
            $sql_check = "SELECT id_nota FROM notas WHERE id_entrega = ?";
            $stmt_check = $this->pdo->prepare($sql_check);
            $stmt_check->execute([$id_entrega]);
            $nota_existente = $stmt_check->fetch();

            if ($nota_existente) {
                // Actualizar nota existente
                $sql = "UPDATE notas SET nota = ?, observaciones = ? 
                        WHERE id_nota = ?";
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([$calificacion, $comentario, $nota_existente['id_nota']]);
            } else {
                // Crear nueva nota
                $sql = "INSERT INTO notas (id_estudiante, id_curso, id_actividad, id_entrega, nota, observaciones)
                        SELECT id_estudiante, a.id_curso, e.id_actividad, e.id_entrega, ?, ?
                        FROM entregas e
                        JOIN actividades a ON e.id_actividad = a.id_actividad
                        WHERE e.id_entrega = ?";
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([$calificacion, $comentario, $id_entrega]);
            }
        } catch (PDOException $e) {
            error_log("Error al calificar entrega: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerEntregaPorId($id_entrega) {
        try {
            $sql = "SELECT * FROM entregas WHERE id_entrega = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_entrega]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener entrega: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerActividadPorId($id_actividad) {
        try {
            $sql = "SELECT a.*, c.id_curso, c.id_docente 
                    FROM actividades a
                    JOIN cursos c ON a.id_curso = c.id_curso
                    WHERE a.id_actividad = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_actividad]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener actividad: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerEntregasPorCurso($id_curso) {
        try {
            $sql = "SELECT e.*, u.nombre AS estudiante, a.nombre AS actividad
                    FROM entregas e
                    JOIN usuarios u ON e.id_estudiante = u.id_usuario
                    JOIN actividades a ON e.id_actividad = a.id_actividad
                    WHERE a.id_curso = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_curso]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener entregas por curso: " . $e->getMessage());
            return [];
        }
    }
}
?>