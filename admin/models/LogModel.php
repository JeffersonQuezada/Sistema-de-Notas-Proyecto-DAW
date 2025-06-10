<?php
// filepath: admin/models/LogModel.php
require_once __DIR__ . '/../../includes/conexion.php';

class LogModel {
    private $pdo;
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    public function registrarAccion($id_usuario, $accion, $detalle) {
        $sql = "INSERT INTO logs (id_usuario, accion, detalle, fecha) VALUES (?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_usuario, $accion, $detalle]);
    }
    public function listarLogs($limite = 50) {
        $sql = "SELECT l.*, u.nombre FROM logs l LEFT JOIN usuarios u ON l.id_usuario = u.id_usuario ORDER BY l.fecha DESC LIMIT ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>