
<?php
require_once __DIR__ . '/../../includes/conexion.php';

class EntregaModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function listarEntregasPorActividad($id_actividad) {
        $sql = "SELECT e.*, u.nombre as estudiante FROM entregas e
                JOIN usuarios u ON e.id_estudiante = u.id_usuario
                WHERE e.id_actividad = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_actividad]);
        return $stmt->fetchAll();
    }

    public function calificarEntrega($id_entrega, $calificacion, $comentario) {
        $sql = "UPDATE entregas SET calificacion = ?, comentario = ? WHERE id_entrega = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$calificacion, $comentario, $id_entrega]);
    }
}
?>