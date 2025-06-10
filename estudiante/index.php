<?php
// filepath: estudiante/index.php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header("Location: ../login.php");
    exit();
}

$accion = $_GET['accion'] ?? 'dashboard';

switch ($accion) {
    case 'dashboard':
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->mostrarDashboard(); // Este método debe incluir la vista correcta
        break;
    case 'cursos':
        require_once 'controllers/CursoController.php';
        $controller = new CursoController();
        $controller->mostrarCursosDisponibles();
        break;
    case 'misiones':
        require_once 'controllers/MisionController.php';
        $controller = new MisionController();
        $controller->mostrarMisiones();
        break;
    case 'insignias':
        require_once 'controllers/InsigniaController.php';
        $controller = new InsigniaController();
        $controller->mostrarInsignias();
        break;
    case 'perfil':
        require_once 'controllers/PerfilController.php';
        $controller = new PerfilController();
        $controller->verPerfil();
        break;
    // Agrega más acciones según tus necesidades
    default:
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->mostrarDashboard(); // Este método debe incluir la vista correcta
        break;
}