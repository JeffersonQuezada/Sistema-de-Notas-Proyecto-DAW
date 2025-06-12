<?php
require_once __DIR__ . '/../../includes/conexion.php';

class InsigniaModel {
    private $pdo;
    public function __construct() {
        $this->pdo = $GLOBALS['pdo'];
    }

    public function obtenerTodas() {
        $stmt = $this->pdo->query("SELECT * FROM insignias");
        return $stmt->fetchAll();
    }

    public function obtenerPorUsuario($id_usuario) {
        $stmt = $this->pdo->prepare("SELECT i.* FROM insignias i
            JOIN usuarios_insignias ui ON i.id_insignia = ui.id_insignia
            WHERE ui.id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll();
    }

    public function asignarInsignia($id_usuario, $id_insignia) {
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO insignias_estudiantes (id_insignia, id_usuario) VALUES (?, ?)");
        return $stmt->execute([$id_insignia, $id_usuario]);
    }
}