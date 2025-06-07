<?php
require_once '../models/ReporteModel.php';

class ReporteController {
    private $reporteModel;

    public function __construct() {
        $this->reporteModel = new ReporteModel();
    }

    public function generarReportePDF($id_curso, $filtros) {
        return $this->reporteModel->generarReportePDF($id_curso, $filtros);
    }

    public function generarReporteExcel($id_curso, $filtros) {
        return $this->reporteModel->generarReporteExcel($id_curso, $filtros);
    }
}
?>