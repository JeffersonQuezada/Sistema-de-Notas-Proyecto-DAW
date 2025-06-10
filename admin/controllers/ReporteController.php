<?php
// filepath: admin/controllers/ReporteController.php
require_once __DIR__ . '/../models/ReporteModel.php';

if (session_status() === PHP_SESSION_NONE) session_start();

class ReporteController {
    private $reporteModel;
    public function __construct() {
        $this->reporteModel = new ReporteModel();
    }

    public function index() {
        $estadisticas = $this->reporteModel->estadisticasGenerales();
        include __DIR__ . '/../views/reportes.php';
    }

    public function descargarUsuariosExcel() {
        $usuarios = $this->reporteModel->generarReporteUsuarios();
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=usuarios.xls");
        echo "Nombre\tCorreo\tRol\tFecha Registro\n";
        foreach ($usuarios as $u) {
            echo "{$u['nombre']}\t{$u['correo']}\t{$u['rol']}\t{$u['fecha_registro']}\n";
        }
        exit();
    }
}
?>