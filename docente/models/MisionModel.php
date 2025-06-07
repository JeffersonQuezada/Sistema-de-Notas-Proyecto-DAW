<?php
require_once '../includes/Database.php';

class MisionModel {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function crearMision($titulo, $descripcion, $recompensa, $fecha_limite) {
        $sql = "INSERT INTO misiones (titulo, descripcion, recompensa, fecha_limite) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$titulo, $descripcion, $recompensa, $fecha_limite]);
    }

    public function aceptarMision($id_mision, $id_estudiante) {
        $sql = "INSERT INTO estudiante_mision (id_estudiante, id_mision, estado) VALUES (?, ?, 'aceptada')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_estudiante, $id_mision]);
    }

    public function finalizarMision($id_mision, $id_estudiante) {
        $sql = "UPDATE estudiante_mision SET estado = 'finalizada' WHERE id_estudiante = ? AND id_mision = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_estudiante, $id_mision]);
    }

    public function listarMisiones() {
        $sql = "SELECT * FROM misiones";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>