<?php
require_once __DIR__ . '/../../includes/conexion.php';

class ActividadModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function crearActividad($nombre, $descripcion, $fecha_limite, $id_curso, $tipo = 'Tarea') {
        try {
            $sql = "INSERT INTO actividades (nombre, descripcion, fecha_limite, id_curso, tipo) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nombre, $descripcion, $fecha_limite, $id_curso, $tipo]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear actividad: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerActividadPorId($id_actividad) {
        try {
            $sql = "SELECT a.*, c.nombre_curso as nombre_curso, c.id_docente
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

    public function editarActividad($id_actividad, $nombre, $descripcion, $fecha_limite, $tipo) {
        try {
            $sql = "UPDATE actividades 
                    SET nombre = ?, descripcion = ?, fecha_limite = ?, tipo = ?
                    WHERE id_actividad = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nombre, $descripcion, $fecha_limite, $tipo, $id_actividad]);
        } catch (PDOException $e) {
            error_log("Error al editar actividad: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarActividad($id_actividad) {
        try {
            $sql = "DELETE FROM actividades WHERE id_actividad = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_actividad]);
        } catch (PDOException $e) {
            error_log("Error al eliminar actividad: " . $e->getMessage());
            return false;
        }
    }

    public function listarActividadesPorCurso($id_curso) {
        try {
            $sql = "SELECT * FROM actividades 
                    WHERE id_curso = ? 
                    ORDER BY fecha_limite DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_curso]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar actividades: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerActividadesPendientes($id_estudiante, $id_curso = null) {
        try {
            $sql = "SELECT a.*, c.nombre_curso 
                    FROM actividades a
                    JOIN cursos c ON a.id_curso = c.id_curso
                    JOIN estudiantes_cursos ec ON c.id_curso = ec.id_curso
                    LEFT JOIN entregas e ON a.id_actividad = e.id_actividad AND e.id_estudiante = ?
                    WHERE ec.id_estudiante = ? AND e.id_entrega IS NULL";
            
            $params = [$id_estudiante, $id_estudiante];
            
            if ($id_curso) {
                $sql .= " AND a.id_curso = ?";
                $params[] = $id_curso;
            }
            
            $sql .= " ORDER BY a.fecha_limite";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener actividades pendientes: " . $e->getMessage());
            return [];
        }
    }
}
?>