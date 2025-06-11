<?php

// AHORA iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar funciones si existe
if (file_exists($funcionesPath)) {
    require_once $funcionesPath;
}

// Definir BASE_URL de forma más robusta
if (!defined('BASE_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $baseUrl = rtrim($scriptDir, '/') . '/';
    define('BASE_URL', $protocol . $host . $baseUrl);
}

// Definir el título de la página si no está definido
if (!isset($pageTitle)) {
    $pageTitle = 'Sistema Académico';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - Sistema Académico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
            <i class="fas fa-graduation-cap me-2"></i> Sistema Académico
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['id_usuario'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['nombre']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php if ($_SESSION['rol'] == 'estudiante'): ?>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>estudiante/index.php?accion=perfil"><i class="fas fa-user me-2"></i>Perfil</a></li>
                        <?php elseif ($_SESSION['rol'] == 'docente'): ?>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>docente/index.php?accion=perfil"><i class="fas fa-user me-2"></i>Perfil</a></li>
                        <?php elseif ($_SESSION['rol'] == 'admin'): ?>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin/index.php?accion=perfil"><i class="fas fa-user me-2"></i>Perfil</a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>login.php">Iniciar sesión</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>registro.php">Registrarse</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<?php if (isset($_SESSION['id_usuario'])): ?>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <div class="text-center mb-4">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nombre']); ?>&background=007bff&color=fff" class="rounded-circle mb-2" width="60">
                    <h6><?php echo htmlspecialchars($_SESSION['nombre']); ?></h6>
                    <small class="text-muted"><?php echo ucfirst($_SESSION['rol']); ?></small>
                </div>
                <hr>
                <ul class="nav flex-column">
                    <?php if ($_SESSION['rol'] == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>admin/index.php?accion=dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>admin/index.php?accion=usuarios">
                            <i class="fas fa-users me-2"></i>Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>admin/index.php?accion=cursos">
                            <i class="fas fa-book me-2"></i>Cursos
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($_SESSION['rol'] == 'docente'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>docente/index.php?accion=dashboard">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>docente/index.php?accion=cursos">
                            <i class="fas fa-book-open me-2"></i>Mis Cursos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>docente/index.php?accion=actividades">
                            <i class="fas fa-tasks me-2"></i>Actividades
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>docente/index.php?accion=entregas">
                            <i class="fas fa-upload me-2"></i>Entregas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>docente/index.php?accion=misiones">
                            <i class="fas fa-bullseye me-2"></i>Misiones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>docente/index.php?accion=reportes">
                            <i class="fas fa-chart-bar me-2"></i>Reportes
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($_SESSION['rol'] == 'estudiante'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>estudiante/index.php?accion=dashboard">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>estudiante/index.php?accion=cursos">
                            <i class="fas fa-book-open me-2"></i>Cursos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>estudiante/index.php?accion=misiones">
                            <i class="fas fa-bullseye me-2"></i>Misiones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>estudiante/index.php?accion=insignias">
                            <i class="fas fa-award me-2"></i>Insignias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>estudiante/index.php?accion=mis_cursos">
                            <i class="fas fa-user-graduate me-2"></i>Mis Cursos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>estudiante/index.php?accion=mis_entregas">
                            <i class="fas fa-file-upload me-2"></i>Mis Entregas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>estudiante/index.php?accion=mis_notas">
                            <i class="fas fa-clipboard-list me-2"></i>Mis Notas
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
<?php else: ?>
<div class="container-fluid">
<?php endif; ?>