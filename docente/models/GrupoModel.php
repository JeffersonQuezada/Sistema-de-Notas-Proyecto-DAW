<?php
require_once '../includes/Database.php';

class GrupoModel {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function crearGrupo($nombre, $descripcion) {
        $sql = "INSERT INTO grupos (nombre, descripcion) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $descripcion]);
    }

    public function editarGrupo($id, $nombre, $descripcion) {
        $sql = "UPDATE grupos SET nombre = ?, descripcion = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $id]);
    }

    public function eliminarGrupo($id) {
        $sql = "DELETE FROM grupos WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function listarGrupos() {
        $sql = "SELECT * FROM grupos";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>