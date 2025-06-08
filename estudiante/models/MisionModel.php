<?php
require_once '../../includes/conexion.php';

class MisionModel {
    private $pdo;
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    public function listarMisionesDisponibles($id_estudiante) {
        $sql = "SELECT m.*, 
                       CASE WHEN me.id_mision IS NOT NULL THEN 1 ELSE 0 END AS aceptada
                FROM misiones m
                LEFT JOIN misiones_estudiantes me ON m.id_mision = me.id_mision AND me.id_estudiante = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll();
    }
    public function aceptarMision($id_estudiante, $id_mision) {
        $sql = "INSERT IGNORE INTO misiones_estudiantes (id_estudiante, id_mision, estado) VALUES (?, ?, 'aceptada')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_estudiante, $id_mision]);
    }
}
?>