<?php
include_once '../controllers/ReporteController.php';

$reporteController = new ReporteController();

$id_curso = $_POST['id_curso'];
$formato = $_POST['formato'];

if ($formato === 'pdf') {
    $reporteController->generarReportePDF($id_curso, []);
} elseif ($formato === 'excel') {
    $reporteController->generarReporteExcel($id_curso, []);
}
?>