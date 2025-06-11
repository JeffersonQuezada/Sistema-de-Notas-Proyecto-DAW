<?php
// filepath: admin/index.php

session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Obtén la acción desde la URL, por defecto 'dashboard'
$accion = $_GET['accion'] ?? 'dashboard';

switch ($accion) {
    case 'usuarios':
        require_once 'controllers/UsuarioController.php';
        $controller = new UsuarioController();
        $controller->listar();
        break;
    case 'usuarios_crear':
        require_once 'controllers/UsuarioController.php';
        $controller = new UsuarioController();
        $controller->crear();
        break;
    case 'usuarios_editar':
        require_once 'controllers/UsuarioController.php';
        $controller = new UsuarioController();
        $controller->editar();
        break;
    case 'cursos':
        require_once 'controllers/CursoController.php';
        $controller = new CursoController();
        $controller->listar();
        break;
    case 'cursos_crear':
        require_once 'controllers/CursoController.php';
        $controller = new CursoController();
        $controller->crear();
        break;
    case 'reportes':
        require_once 'controllers/ReporteController.php';
        $controller = new ReporteController();
        $controller->index();
        break;
    case 'logs':
        require_once 'controllers/LogController.php';
        $controller = new LogController();
        $controller->index();
        break;
    case 'horarios':
        require_once 'controllers/HorarioController.php';
        $controller = new HorarioController();
        $controller->listar();
        break;
    case 'horario_crear':
        require_once 'controllers/HorarioController.php';
        $controller = new HorarioController();
        $controller->crear();
        break;
    case 'horario_eliminar':
        require_once 'controllers/HorarioController.php';
        $controller = new HorarioController();
        $controller->eliminar();
        break;
    case 'dashboard':
    default:
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;
    case 'perfil':
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->perfil();
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