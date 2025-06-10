<?php
require_once __DIR__ . '/../../includes/conexion.php';

class InsigniaModel {
    private $pdo;
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    public function listarInsigniasPorEstudiante($id_estudiante) {
        $sql = "SELECT i.* FROM insignias i
                JOIN insignias_estudiantes ie ON i.id_insignia = ie.id_insignia
                WHERE ie.id_estudiante = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll();
    }
}
?>