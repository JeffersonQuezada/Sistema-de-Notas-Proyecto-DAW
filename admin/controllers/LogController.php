<?php
// filepath: admin/controllers/LogController.php
require_once __DIR__ . '/../models/LogModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

class LogController {
    private $logModel;
    public function __construct() {
        $this->logModel = new LogModel();
    }
    public function index() {
        $logs = $this->logModel->listarLogs(100); // Puedes cambiar el límite
        include '../views/logs_listado.php';
    }
}
?>