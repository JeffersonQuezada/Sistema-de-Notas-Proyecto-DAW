<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    
    // Detectar el directorio base del proyecto
    $scriptPath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $pathParts = explode('/', trim($scriptPath, '/'));
    
    // Buscar si estamos en un módulo (docente, estudiante, admin)
    $modulos = ['docente', 'estudiante', 'admin'];
    $currentDir = '';
    
    foreach ($pathParts as $part) {
        if (in_array($part, $modulos)) {
            $currentDir = $part;
            break;
        }
    }
    
    if ($currentDir) {
        $baseUrl = $protocol . $host . '/' . implode('/', array_slice($pathParts, 0, array_search($currentDir, $pathParts) + 1)) . '/';
    } else {
        $baseUrl = $protocol . $host . $scriptPath . '/';
    }
    
    define('BASE_URL', $baseUrl);
}

// Definir la URL raíz del proyecto (un nivel arriba del módulo actual)
if (!defined('ROOT_URL')) {
    $rootUrl = $protocol . $host . '/';
    if (strpos(BASE_URL, '/docente/') !== false) {
        $rootUrl = str_replace('/docente/', '/', BASE_URL);
        $rootUrl = rtrim($rootUrl, '/') . '/';
    } elseif (strpos(BASE_URL, '/estudiante/') !== false) {
        $rootUrl = str_replace('/estudiante/', '/', BASE_URL);
        $rootUrl = rtrim($rootUrl, '/') . '/';
    } elseif (strpos(BASE_URL, '/admin/') !== false) {
        $rootUrl = str_replace('/admin/', '/', BASE_URL);
        $rootUrl = rtrim($rootUrl, '/') . '/';
    } else {
        $rootUrl = BASE_URL;
    }
    define('ROOT_URL', $rootUrl);
}

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
            <i class="fas fa-graduation-cap me-2"></i> Edutech Academy
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
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>index.php?accion=perfil"><i class="fas fa-user me-2"></i>Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo ROOT_URL; ?>logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo ROOT_URL; ?>login.php">Iniciar sesión</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo ROOT_URL; ?>registro.php">Registrarse</a>
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
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=usuarios">
                            <i class="fas fa-users me-2"></i>Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=cursos_admin">
                            <i class="fas fa-book me-2"></i>Cursos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=misiones">
                            <i class="fas fa-flag me-2"></i>Misiones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=insignias">
                            <i class="fas fa-award me-2"></i>Insignias
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($_SESSION['rol'] == 'docente'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=dashboard">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=cursos">
                            <i class="fas fa-book-open me-2"></i>Mis Cursos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=actividades">
                            <i class="fas fa-tasks me-2"></i>Actividades
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=entregas">
                            <i class="fas fa-upload me-2"></i>Entregas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=misiones">
                            <i class="fas fa-bullseye me-2"></i>Misiones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=reportes">
                            <i class="fas fa-chart-bar me-2"></i>Reportes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=insignias">
                            <i class="fas fa-award me-2"></i>Insignias
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($_SESSION['rol'] == 'estudiante'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=dashboard">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=cursos">
                            <i class="fas fa-book-open me-2"></i>Cursos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=misiones">
                            <i class="fas fa-flag me-2"></i>Misiones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=insignias">
                            <i class="fas fa-award me-2"></i>Insignias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=mis_cursos">
                            <i class="fas fa-user-graduate me-2"></i>Mis Cursos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=mis_entregas">
                            <i class="fas fa-file-upload me-2"></i>Mis Entregas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?accion=mis_notas">
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