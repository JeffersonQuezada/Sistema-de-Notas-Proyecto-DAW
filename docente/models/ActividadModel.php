<?php
require_once '../includes/Database.php';

class ActividadModel {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function crearActividad($titulo, $descripcion, $fecha_entrega, $id_grupo) {
        $sql = "INSERT INTO actividades (titulo, descripcion, fecha_entrega, id_grupo) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$titulo, $descripcion, $fecha_entrega, $id_grupo]);
    }

    public function editarActividad($id, $titulo, $descripcion, $fecha_entrega, $id_grupo) {
        $sql = "UPDATE actividades SET titulo = ?, descripcion = ?, fecha_entrega = ?, id_grupo = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$titulo, $descripcion, $fecha_entrega, $id_grupo, $id]);
    }

    public function eliminarActividad($id) {
        $sql = "DELETE FROM actividades WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function listarActividades() {
        $sql = "SELECT * FROM actividades";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>