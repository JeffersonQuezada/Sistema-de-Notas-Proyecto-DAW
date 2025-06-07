<?php
require_once '../includes/Database.php';
require_once '../controllers/DashboardController.php';

class DashboardModel {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function obtenerProgresoIndividual($id_estudiante) {
        $sql = "SELECT a.titulo, e.calificacion, e.comentario, e.fecha_entrega
                FROM entregas e
                JOIN actividades a ON e.id_actividad = a.id
                WHERE e.id_estudiante = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll();
    }

    public function obtenerProgresoGrupal($id_grupo) {
        $sql = "SELECT u.nombre, a.titulo, e.calificacion, e.comentario, e.fecha_entrega
                FROM entregas e
                JOIN usuarios u ON e.id_estudiante = u.id
                JOIN actividades a ON e.id_actividad = a.id
                JOIN estudiante_grupo eg ON u.id = eg.id_estudiante
                WHERE eg.id_grupo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_grupo]);
        return $stmt->fetchAll();
    }

    public function identificarEstudiantesEnRiesgo() {
        $sql = "SELECT u.nombre, COUNT(e.id) as entregas_pendientes
                FROM usuarios u
                LEFT JOIN entregas e ON u.id = e.id_estudiante
                WHERE e.calificacion IS NULL
                GROUP BY u.id
                HAVING COUNT(e.id) > 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerEstadisticasCumplimiento() {
        $sql = "SELECT a.titulo, COUNT(e.id) as entregas_recibidas, COUNT(DISTINCT e.id_estudiante) as estudiantes_entregaron
                FROM actividades a
                LEFT JOIN entregas e ON a.id = e.id_actividad
                GROUP BY a.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>