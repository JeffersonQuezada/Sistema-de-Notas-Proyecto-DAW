<?php
require_once '../../includes/conexion.php';

class EntregaModel {
    private $pdo;
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    public function entregarActividad($id_estudiante, $id_actividad, $archivo) {
        $sql = "INSERT INTO entregas (id_estudiante, id_actividad, archivo, fecha_entrega) VALUES (?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_estudiante, $id_actividad, $archivo]);
    }
    public function obtenerEntregasPorEstudiante($id_estudiante) {
        $sql = "SELECT e.*, a.nombre as actividad, a.fecha_limite, a.tipo
                FROM entregas e
                JOIN actividades a ON e.id_actividad = a.id_actividad
                WHERE e.id_estudiante = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll();
    }
    public function obtenerPromedioGeneral($id_estudiante) {
        $sql = "SELECT AVG(nota) as promedio FROM notas WHERE id_estudiante = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchColumn();
    }
}
?>