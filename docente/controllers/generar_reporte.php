<?php
require_once __DIR__ . '/ReporteController.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$reporteController = new ReporteController();

$id_curso = $_GET['id_curso'] ?? $_POST['id_curso'] ?? null;
$tipo_reporte = $_GET['tipo'] ?? $_POST['tipo_reporte'] ?? null;

if ($tipo_reporte === 'pdf') {
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="reporte.pdf"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    $reporteController->generarReportePDF($id_curso);
} elseif ($tipo_reporte === 'excel') {
    $reporteController->generarReporteExcel($id_curso);
} else {
    header("Location: ../index.php?accion=reportes&error=1&msg=Tipo de reporte no válido");
    exit();
}
?>