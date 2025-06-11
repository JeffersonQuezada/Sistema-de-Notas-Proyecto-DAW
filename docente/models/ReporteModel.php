<?php
require_once __DIR__ . '/../../includes/conexion.php';

class ReporteModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function obtenerReporteCalificaciones($id_curso) {
        try {
            $sql = "SELECT u.nombre as estudiante, a.nombre as actividad, n.nota, n.observaciones
                    FROM notas n
                    JOIN usuarios u ON n.id_estudiante = u.id_usuario
                    JOIN actividades a ON n.id_actividad = a.id_actividad
                    WHERE n.id_curso = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_curso]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener reporte de calificaciones: " . $e->getMessage());
            return [];
        }
    }

    public function generarReportePDF($id_curso) {
        try {
            $datos = $this->obtenerReporteCalificaciones($id_curso);
            $curso = $this->obtenerInfoCurso($id_curso);

            $html = '<h1>Reporte de Calificaciones - '.htmlspecialchars($curso['nombre_curso']).'</h1>';
            $html .= '<table border="1" cellpadding="5">';
            $html .= '<tr><th>Estudiante</th><th>Actividad</th><th>Calificación</th><th>Observaciones</th></tr>';

            foreach ($datos as $row) {
                $html .= '<tr>';
                $html .= '<td>'.htmlspecialchars($row['estudiante']).'</td>';
                $html .= '<td>'.htmlspecialchars($row['actividad']).'</td>';
                $html .= '<td>'.htmlspecialchars($row['nota']).'</td>';
                $html .= '<td>'.htmlspecialchars($row['observaciones'] ?? '').'</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';

            return $html;
        } catch (PDOException $e) {
            error_log("Error al generar reporte PDF: " . $e->getMessage());
            return false;
        }
    }

    public function generarReporteExcel($id_curso) {
        try {
            return $this->obtenerReporteCalificaciones($id_curso);
        } catch (PDOException $e) {
            error_log("Error al generar reporte Excel: " . $e->getMessage());
            return [];
        }
    }

    private function obtenerInfoCurso($id_curso) {
        try {
            $sql = "SELECT nombre_curso FROM cursos WHERE id_curso = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_curso]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener información del curso: " . $e->getMessage());
            return ['nombre_curso' => 'Curso Desconocido'];
        }
    }
}
?>