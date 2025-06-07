<?php
require_once '../controllers/ReporteController.php';

$reporteController = new ReporteController();

$id_curso = $_POST['id_curso'];
$tipo_reporte = $_POST['tipo_reporte'];

if ($tipo_reporte === 'pdf') {
    $reporteController->generarReportePDF($id_curso, []);
} elseif ($tipo_reporte === 'excel') {
    $reporteController->generarReporteExcel($id_curso, []);
}
?>