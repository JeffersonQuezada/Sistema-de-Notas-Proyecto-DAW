<?php
require_once '../includes/Database.php';

class EntregaModel {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function listarEntregasPorActividad($id_actividad) {
        $sql = "SELECT e.*, u.nombre as estudiante FROM entregas e
                JOIN usuarios u ON e.id_estudiante = u.id
                WHERE e.id_actividad = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_actividad]);
        return $stmt->fetchAll();
    }

    public function calificarEntrega($id_entrega, $calificacion, $comentario) {
        $sql = "UPDATE entregas SET calificacion = ?, comentario = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$calificacion, $comentario, $id_entrega]);
    }
}
?>