<?php
require_once 'controllers/DashboardController.php';
require_once 'models/DashboardModel.php';

$dashboardController = new DashboardController();
$dashboardController->mostrarDashboard();
?>