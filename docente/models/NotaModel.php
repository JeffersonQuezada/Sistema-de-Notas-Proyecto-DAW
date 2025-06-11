<?php
require_once __DIR__ . '/../../includes/conexion.php';

class NotaModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function obtenerNotasPorEstudiante($id_estudiante, $id_curso = null) {
        try {
            $sql = "SELECT n.*, a.nombre as actividad, a.tipo, c.nombre_curso
                    FROM notas n
                    JOIN actividades a ON n.id_actividad = a.id_actividad
                    JOIN cursos c ON a.id_curso = c.id_curso
                    WHERE n.id_estudiante = ?";
            
            $params = [$id_estudiante];
            
            if ($id_curso) {
                $sql .= " AND a.id_curso = ?";
                $params[] = $id_curso;
            }
            
            $sql .= " ORDER BY a.fecha_limite DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener notas: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPromedioEstudiante($id_estudiante, $id_curso = null) {
        try {
            $sql = "SELECT AVG(n.nota) as promedio
                    FROM notas n
                    JOIN actividades a ON n.id_actividad = a.id_actividad";
            
            $where = " WHERE n.id_estudiante = ?";
            $params = [$id_estudiante];
            
            if ($id_curso) {
                $where .= " AND a.id_curso = ?";
                $params[] = $id_curso;
            }
            
            $sql .= $where;
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error al obtener promedio: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerEstadisticasCurso($id_curso) {
        try {
            $sql = "SELECT 
                    COUNT(DISTINCT n.id_estudiante) as estudiantes_calificados,
                    COUNT(n.id_nota) as total_calificaciones,
                    AVG(n.nota) as promedio_curso,
                    MIN(n.nota) as minima,
                    MAX(n.nota) as maxima
                    FROM notas n
                    JOIN actividades a ON n.id_actividad = a.id_actividad
                    WHERE a.id_curso = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_curso]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener estadísticas: " . $e->getMessage());
            return false;
        }
    }
}
?>