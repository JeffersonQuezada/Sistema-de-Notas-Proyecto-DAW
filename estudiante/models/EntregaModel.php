<?php
require_once __DIR__ . '/../../includes/conexion.php';

class EntregaModel {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function entregarActividad($id_actividad, $id_estudiante, $archivo, $comentario = null) {
    $sql = "INSERT INTO entregas (id_actividad, id_estudiante, archivo, comentario) VALUES (?, ?, ?, ?)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$id_actividad, $id_estudiante, $archivo, $comentario]);
}
    
  public function obtenerEntregasPorEstudiante($id_estudiante) {
    $sql = "SELECT e.*, a.nombre AS actividad, a.tipo, a.id_curso, c.nombre_curso
            FROM entregas e
            JOIN actividades a ON e.id_actividad = a.id_actividad
            JOIN cursos c ON a.id_curso = c.id_curso
            WHERE e.id_estudiante = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id_estudiante]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    
    public function obtenerPromedioGeneral($id_estudiante) {
        $sql = "SELECT AVG(n.nota) as promedio 
                FROM notas n
                JOIN entregas e ON n.id_entrega = e.id_entrega
                WHERE e.id_estudiante = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchColumn();
    }
    
    public function obtenerEntregaPorActividadYEstudiante($id_estudiante, $id_actividad) {
        $sql = "SELECT e.*, n.nota, n.observaciones as retroalimentacion
                FROM entregas e
                LEFT JOIN notas n ON e.id_entrega = n.id_entrega
                WHERE e.id_estudiante = ? AND e.id_actividad = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_estudiante, $id_actividad]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function obtenerEntregasPendientes($id_estudiante) {
        $sql = "SELECT a.* 
                FROM actividades a
                JOIN estudiantes_cursos ec ON a.id_curso = ec.id_curso
                WHERE ec.id_estudiante = ?
                AND a.id_actividad NOT IN (
                    SELECT id_actividad FROM entregas WHERE id_estudiante = ?
                )
                AND a.fecha_limite > NOW()";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_estudiante, $id_estudiante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function guardarEntrega($id_actividad, $id_estudiante, $archivo, $comentario) {
        $sql = "INSERT INTO entregas (id_actividad, id_estudiante, archivo, comentario, fecha_entrega)
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_actividad, $id_estudiante, $archivo, $comentario]);
    }
}
?>