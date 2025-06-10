<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir configuración y funciones
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/funciones.php';

// Definir el título de la página si no está definido
if (!isset($pageTitle)) {
    $pageTitle = 'Sistema Académico';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?> - Sistema Académico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid">
        <a class="navbar-brand text-primary" href="<?php echo url('index.php'); ?>">
            <i class="fas fa-graduation-cap me-2"></i> Sistema Académico
        </a>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (isset($_SESSION['id_usuario'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['nombre']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo url('perfil.php'); ?>"><i class="fas fa-user me-2"></i>Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo url('logout.php'); ?>"><i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('login.php'); ?>">Iniciar sesión</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('crear_cuenta.php'); ?>">Registrarse</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <?php if (isset($_SESSION['id_usuario'])): ?>
        <div class="col-lg-2 bg-dark text-white pt-4 min-vh-100">
            <div class="text-center mb-4">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nombre']); ?>&background=random" class="rounded-circle mb-2" width="80">
                <h6><?php echo htmlspecialchars($_SESSION['nombre']); ?></h6>
                <small><?php echo ucfirst($_SESSION['rol']); ?></small>
            </div>
            <hr class="bg-light">
            <ul class="nav flex-column">
                <?php if ($_SESSION['rol'] == 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo url('admin/index.php?accion=dashboard'); ?>"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo url('admin/index.php?accion=usuarios'); ?>"><i class="fas fa-users me-2"></i>Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo url('admin/index.php?accion=cursos'); ?>"><i class="fas fa-book me-2"></i>Cursos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo url('admin/index.php?accion=reportes'); ?>"><i class="fas fa-chart-bar me-2"></i>Reportes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo url('admin/index.php?accion=logs'); ?>"><i class="fas fa-history me-2"></i>Historial de Logs</a>
                </li>
                <?php endif; ?>
                <?php if ($_SESSION['rol'] == 'docente'): ?>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo activeMenu($_SERVER['SCRIPT_NAME'], 'dashboard_principal.php') ?>" href="<?php echo url('docente/views/dashboard_principal.php'); ?>"><i class="fas fa-home me-2"></i>Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo activeMenu($_SERVER['SCRIPT_NAME'], 'actividades_listado.php') ?>" href="<?php echo url('docente/views/actividades_listado.php'); ?>"><i class="fas fa-tasks me-2"></i>Actividades</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo activeMenu($_SERVER['SCRIPT_NAME'], 'entregas_listado.php') ?>" href="<?php echo url('docente/views/entregas_listado.php'); ?>"><i class="fas fa-upload me-2"></i>Entregas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo activeMenu($_SERVER['SCRIPT_NAME'], 'misiones_listado.php') ?>" href="<?php echo url('docente/views/misiones_listado.php'); ?>"><i class="fas fa-bullseye me-2"></i>Misiones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo activeMenu($_SERVER['SCRIPT_NAME'], 'reportes.php') ?>" href="<?php echo url('docente/views/reportes.php'); ?>"><i class="fas fa-chart-pie me-2"></i>Reportes</a>
                </li>
                <?php endif; ?>
                <?php if ($_SESSION['rol'] == 'estudiante'): ?>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo url('estudiante/index.php?accion=dashboard'); ?>"><i class="fas fa-home me-2"></i>Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo url('estudiante/index.php?accion=cursos'); ?>"><i class="fas fa-book-open me-2"></i>Cursos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo url('estudiante/index.php?accion=mis_notas'); ?>"><i class="fas fa-clipboard-list me-2"></i>Mis Notas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo url('estudiante/index.php?accion=insignias'); ?>"><i class="fas fa-award me-2"></i>Insignias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo url('estudiante/index.php?accion=misiones'); ?>"><i class="fas fa-bullseye me-2"></i>Misiones</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="<?php echo isset($_SESSION['id_usuario']) ? 'col-lg-10 p-4' : 'col-12'; ?>">
            <!-- Contenido principal -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-center"><?php echo htmlspecialchars($pageTitle); ?></h1>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <?php
                        // Aquí va el contenido específico de cada página
                        if (isset($_GET['accion'])) {
                            $accion = $_GET['accion'];
                            switch ($accion) {
                                case 'dashboard':
                                    include 'dashboard.php';
                                    break;
                                case 'cursos':
                                    include 'cursos.php';
                                    break;
                                case 'misiones':
                                    include 'misiones.php';
                                    break;
                                case 'insignias':
                                    include 'insignias.php';
                                    break;
                                case 'perfil':
                                    include 'perfil.php';
                                    break;
                                default:
                                    include 'dashboard.php';
                                    break;
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

