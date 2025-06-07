<?php
require_once '../includes/Database.php';

class EstudianteModel {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function asignarEstudianteAGrupo($id_estudiante, $id_grupo) {
        $sql = "INSERT INTO estudiante_grupo (id_estudiante, id_grupo) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_estudiante, $id_grupo]);
    }

    public function listarEstudiantesPorGrupo($id_grupo) {
        $sql = "SELECT u.* FROM usuarios u
                JOIN estudiante_grupo eg ON u.id = eg.id_estudiante
                WHERE eg.id_grupo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_grupo]);
        return $stmt->fetchAll();
    }
}
?>