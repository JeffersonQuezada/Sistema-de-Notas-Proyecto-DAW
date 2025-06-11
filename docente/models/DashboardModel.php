<?php
require_once __DIR__ . '/../../includes/conexion.php';

class DashboardModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function obtenerProgresoIndividual($id_estudiante) {
        try {
            $sql = "SELECT a.nombre, n.nota, n.observaciones, e.fecha_entrega
                    FROM notas n
                    JOIN actividades a ON n.id_actividad = a.id_actividad
                    LEFT JOIN entregas e ON n.id_entrega = e.id_entrega
                    WHERE n.id_estudiante = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_estudiante]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error al obtener progreso individual: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerProgresoGrupal($id_grupo) {
        try {
            $sql = "SELECT u.nombre, a.nombre as actividad, n.nota, n.observaciones, e.fecha_entrega
                    FROM notas n
                    JOIN usuarios u ON n.id_estudiante = u.id_usuario
                    JOIN actividades a ON n.id_actividad = a.id_actividad
                    LEFT JOIN entregas e ON n.id_entrega = e.id_entrega
                    JOIN estudiantes_grupos eg ON u.id_usuario = eg.id_usuario
                    WHERE eg.id_grupo = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_grupo]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error al obtener progreso grupal: " . $e->getMessage());
            return [];
        }
    }

    public function identificarEstudiantesEnRiesgo($id_curso = null) {
        try {
            $sql = "SELECT u.id_usuario, u.nombre, 
                           COUNT(a.id_actividad) as total_actividades,
                           COUNT(e.id_entrega) as entregas_realizadas
                    FROM usuarios u
                    JOIN estudiantes_cursos ec ON u.id_usuario = ec.id_estudiante
                    JOIN actividades a ON a.id_curso = ec.id_curso
                    LEFT JOIN entregas e ON u.id_usuario = e.id_estudiante AND a.id_actividad = e.id_actividad
                    WHERE u.rol = 'estudiante'";
            
            if ($id_curso) {
                $sql .= " AND ec.id_curso = ?";
            }
            
            $sql .= " GROUP BY u.id_usuario
                      HAVING total_actividades > entregas_realizadas";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($id_curso ? [$id_curso] : []);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error al identificar estudiantes en riesgo: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerEstadisticasCumplimiento($id_curso = null) {
        try {
            $sql = "SELECT a.id_actividad, a.nombre, 
                           COUNT(e.id_entrega) as entregas_recibidas,
                           (SELECT COUNT(*) FROM estudiantes_cursos WHERE id_curso = a.id_curso) as total_estudiantes
                    FROM actividades a
                    LEFT JOIN entregas e ON a.id_actividad = e.id_actividad";
            
            if ($id_curso) {
                $sql .= " WHERE a.id_curso = ?";
            }
            
            $sql .= " GROUP BY a.id_actividad";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($id_curso ? [$id_curso] : []);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error al obtener estadísticas: " . $e->getMessage());
            return [];
        }
    }
}
?>