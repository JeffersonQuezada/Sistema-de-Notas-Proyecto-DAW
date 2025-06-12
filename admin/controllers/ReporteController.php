<?php
require_once __DIR__ . '/../models/ReporteModel.php';
require_once __DIR__ . '/../views/ReportesView.php';

class ReporteController {
    private $model;
    
    public function __construct() {
        $this->model = new ReporteModel();
    }
    
    public function mostrarReportes() {
        $view = new ReportesView();
        $view->mostrar();
    }
    
    public function generarReporteUsuarios() {
        $datos = $this->model->obtenerDatosUsuarios();
        $view = new ReportesView();
        $view->mostrarReporteUsuarios($datos);
    }
    
    public function generarReporteCursos() {
        $datos = $this->model->obtenerDatosCursos();
        $view = new ReportesView();
        $view->mostrarReporteCursos($datos);
    }
    
    public function generarReporteActividades() {
        $datos = $this->model->obtenerDatosActividades();
        $view = new ReportesView();
        $view->mostrarReporteActividades($datos);
    }
    
    public function exportarReportePDF($tipo) {
        require_once __DIR__ . '/../../libs/fpdf/fpdf.php';
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        if ($tipo === 'usuarios') {
            $datos = $this->model->obtenerReporteUsuarios();
            $titulo = "Reporte de Usuarios";
            $headers = ['Nombre', 'Correo', 'Rol'];
        } elseif ($tipo === 'cursos') {
            $datos = $this->model->obtenerReporteCursos();
            $titulo = "Reporte de Cursos";
            $headers = ['Curso', 'Docente', 'Capacidad', 'Grupo'];
        } elseif ($tipo === 'actividades') {
            $datos = $this->model->obtenerReporteActividades();
            $titulo = "Reporte de Actividades";
            $headers = ['Actividad', 'Tipo', 'Fecha Límite'];
        } else {
            $datos = [];
            $titulo = "Reporte";
            $headers = [];
        }

        $pdf->Cell(0, 10, $titulo, 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 12);

        // Encabezados de tabla
        foreach ($headers as $header) {
            $pdf->Cell(50, 10, $header, 1);
        }
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 11);
        foreach ($datos as $fila) {
            foreach ($fila as $col) {
                $pdf->Cell(50, 8, utf8_decode($col), 1);
            }
            $pdf->Ln();
        }

        header('Content-Type: application/pdf');
        $pdf->Output('I', 'reporte.pdf');
        exit;
    }
}