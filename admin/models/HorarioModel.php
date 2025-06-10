<?php
require_once __DIR__ . '/../../includes/conexion.php';

class HorarioModel {
    private $pdo;
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    public function listarHorariosPorCurso($id_curso) {
        $sql = "SELECT * FROM horarios WHERE id_curso = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_curso]);
        return $stmt->fetchAll();
    }
    public function crearHorario($id_curso, $dia, $hora_inicio, $hora_fin) {
        $sql = "INSERT INTO horarios (id_curso, dia, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_curso, $dia, $hora_inicio, $hora_fin]);
    }
    public function eliminarHorario($id_horario) {
        $sql = "DELETE FROM horarios WHERE id_horario = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_horario]);
    }
}
?>