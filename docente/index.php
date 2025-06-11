<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'docente') {
    header("Location: ../login.php");
    exit();
}

$accion = $_GET['accion'] ?? 'dashboard';
$id = $_GET['id'] ?? null;

switch ($accion) {
    case 'dashboard':
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->mostrarDashboard();
        break;

    case 'actividades':
        require_once 'controllers/ActividadController.php';
        $controller = new ActividadController();
        // Aquí puedes listar actividades o mostrar una vista
        include __DIR__ . '/views/actividades_listado.php';
        break;

    case 'nueva_actividad':
        include __DIR__ . '/views/nueva_actividad.php';
        break;

    case 'editar_actividad':
        include __DIR__ . '/views/editar_actividad.php';
        break;

    case 'ver_actividad':
        include __DIR__ . '/views/ver_actividad.php';
        break;

    case 'entregas':
        include __DIR__ . '/views/entregas_listado.php';
        break;

    case 'misiones':
        include __DIR__ . '/views/misiones_listado.php';
        break;

    case 'ver_mision':
        include __DIR__ . '/views/ver_mision.php';
        break;

    case 'perfil':
        // Si tienes un controlador de perfil, llámalo aquí
        // require_once 'controllers/PerfilController.php';
        // $controller = new PerfilController();
        // $controller->verPerfil();
        break;

    // Agrega más casos según tus necesidades

    default:
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->mostrarDashboard();
        break;
}
?>