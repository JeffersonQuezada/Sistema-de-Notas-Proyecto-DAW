<?php

require_once __DIR__ . '/../../includes/conexion.php';

class MisionModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function crearMision($titulo, $descripcion, $recompensa, $fecha_limite) {
        $sql = "INSERT INTO misiones (titulo, descripcion, recompensa, fecha_limite) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$titulo, $descripcion, $recompensa, $fecha_limite]);
    }

    public function aceptarMision($id_mision, $id_estudiante) {
        $sql = "INSERT INTO estudiante_mision (id_estudiante, id_mision, estado) VALUES (?, ?, 'aceptada')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_estudiante, $id_mision]);
    }

    public function finalizarMision($id_mision, $id_estudiante) {
        $sql = "UPDATE estudiante_mision SET estado = 'finalizada' WHERE id_estudiante = ? AND id_mision = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_estudiante, $id_mision]);
    }

    public function listarMisiones() {
        $sql = "SELECT * FROM misiones";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>