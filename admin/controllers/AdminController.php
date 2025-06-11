<?php
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../views/DashboardView.php';

class AdminController {
    private $model;
    
    public function __construct() {
        $this->model = new AdminModel();
    }
    
    public function mostrarDashboard() {
        $estadisticas = $this->model->obtenerEstadisticas();
        $view = new DashboardView();
        $view->mostrar($estadisticas);
    }
}