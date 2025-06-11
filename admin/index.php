<?php
// filepath: admin/index.php

session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'docente') {
    header("Location: ../login.php");
    exit();
}

// Obtén la acción desde la URL, por defecto 'dashboard'
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
        $controller->listarActividades();
        break;

    case 'nueva_actividad':
        require_once 'controllers/ActividadController.php';
        $controller = new ActividadController();
        $controller->nuevaActividad();
        break;

    case 'editar_actividad':
        require_once 'controllers/ActividadController.php';
        $controller = new ActividadController();
        $controller->editarActividad($id);
        break;

    case 'ver_actividad':
        require_once 'controllers/ActividadController.php';
        $controller = new ActividadController();
        $controller->verActividad($id);
        break;

    case 'entregas':
        require_once 'controllers/EntregaController.php';
        $controller = new EntregaController();
        $controller->listarEntregas($id);
        break;

    case 'misiones':
        require_once 'controllers/MisionController.php';
        $controller = new MisionController();
        $controller->listarMisiones();
        break;

    case 'ver_mision':
        require_once 'controllers/MisionController.php';
        $controller = new MisionController();
        $controller->verMision($id);
        break;

    case 'perfil':
        require_once 'controllers/PerfilController.php';
        $controller = new PerfilController();
        $controller->verPerfil();
        break;

    default:
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->mostrarDashboard();
        break;
}
?>
<?php
if (isset($_GET['bienvenido']) && $_GET['bienvenido'] == 1 && isset($_SESSION['nombre'])): ?>
    <div class="alert alert-success text-center mt-4">
        ¡Bienvenido, <strong><?= htmlspecialchars($_SESSION['nombre']) ?></strong>!
        <a href="index.php?accion=dashboard" class="btn btn-primary ms-3">Ir al Dashboard</a>
        <a href="index.php?accion=reportes">Reportes</a>
    </div>
<?php endif; ?>