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
}