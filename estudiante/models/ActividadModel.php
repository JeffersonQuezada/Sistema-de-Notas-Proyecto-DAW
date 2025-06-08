<?php
require_once '../../includes/conexion.php';

class ActividadModel {
    private $pdo;
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    public function listarActividadesPorCurso($id_curso) {
        $sql = "SELECT * FROM actividades WHERE id_curso = ? ORDER BY fecha_limite DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_curso]);
        return $stmt->fetchAll();
    }
    public function obtenerActividadPorId($id_actividad) {
        $sql = "SELECT * FROM actividades WHERE id_actividad = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_actividad]);
        return $stmt->fetch();
    }
}
?>