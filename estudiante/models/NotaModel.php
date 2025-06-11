<?php
require_once __DIR__ . '/../../includes/conexion.php';

class NotaModel {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function obtenerNotasPorEstudiante($id_estudiante) {
        $sql = "SELECT n.*, 
                       a.nombre as actividad_nombre,
                       a.tipo as actividad_tipo,
                       c.nombre_curso,
                       c.id_curso,
                       e.fecha_entrega,
                       e.archivo,
                       e.comentario as comentario_entrega
                FROM notas n
                JOIN entregas e ON n.id_entrega = e.id_entrega
                JOIN actividades a ON e.id_actividad = a.id_actividad
                JOIN cursos c ON a.id_curso = c.id_curso
                WHERE e.id_estudiante = ?
                ORDER BY n.fecha_registro DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function calcularPromediosPorCurso($id_estudiante) {
        $sql = "SELECT c.id_curso,
                       c.nombre_curso,
                       ROUND(AVG(n.nota), 2) as promedio,
                       COUNT(n.id_nota) as total_actividades,
                       MAX(n.nota) as nota_maxima,
                       MIN(n.nota) as nota_minima
                FROM notas n
                JOIN entregas e ON n.id_entrega = e.id_entrega
                JOIN actividades a ON e.id_actividad = a.id_actividad
                JOIN cursos c ON a.id_curso = c.id_curso
                WHERE e.id_estudiante = ?
                GROUP BY c.id_curso, c.nombre_curso
                ORDER BY c.nombre_curso";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function calcularPromedioGeneral($id_estudiante) {
        $sql = "SELECT ROUND(AVG(n.nota), 2) as promedio_general
                FROM notas n
                JOIN entregas e ON n.id_entrega = e.id_entrega
                WHERE e.id_estudiante = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_estudiante]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $resultado['promedio_general'] ?? 0;
    }
    
    public function obtenerEstadisticasEstudiante($id_estudiante) {
        $sql = "SELECT 
                    COUNT(DISTINCT a.id_curso) as cursos_inscritos,
                    COUNT(DISTINCT e.id_actividad) as actividades_entregadas,
                    COUNT(n.id_nota) as actividades_calificadas,
                    ROUND(AVG(n.nota), 2) as promedio_general,
                    MAX(n.nota) as nota_maxima,
                    MIN(n.nota) as nota_minima
                FROM entregas e
                LEFT JOIN notas n ON e.id_entrega = n.id_entrega
                JOIN actividades a ON e.id_actividad = a.id_actividad
                WHERE e.id_estudiante = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function obtenerDetalleNota($id_nota, $id_estudiante) {
        $sql = "SELECT n.*, a.nombre as actividad, c.nombre_curso, 
                       e.archivo, e.fecha_entrega, e.comentario as comentario_entrega
                FROM notas n
                JOIN entregas e ON n.id_entrega = e.id_entrega
                JOIN actividades a ON e.id_actividad = a.id_actividad
                JOIN cursos c ON a.id_curso = c.id_curso
                WHERE n.id_nota = ? AND e.id_estudiante = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_nota, $id_estudiante]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>