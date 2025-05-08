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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - Sistema Académico</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Estilos personalizados -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --dark-color: #5a5c69;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
            padding-top: 60px;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
        }
        
        .navbar {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            background: white;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, #224abe 100%);
            min-height: calc(100vh - 56px);
            position: fixed;
            width: 250px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0.5rem;
            border-radius: 0.25rem;
            padding: 0.75rem 1rem;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: white;
            font-weight: 600;
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .sidebar .nav-link i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 20px;
        }
        
        .page-header {
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Barra de navegación superior -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-fluid">
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <a class="navbar-brand text-primary" href="<?php echo url('index.php'); ?>">
                <i class="fas fa-graduation-cap me-2"></i>Sistema Académico
            </a>
            
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php if (isset($_SESSION['id_usuario'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nombre']); ?>&background=random" class="user-avatar me-1">
                            <?php echo htmlspecialchars($_SESSION['nombre']); ?>
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
            <!-- Sidebar - Solo mostrar si está logueado -->
            <?php if (isset($_SESSION['id_usuario'])): ?>
            <div class="col-lg-2 d-none d-lg-block sidebar collapse" id="sidebarMenu">
                <div class="pt-3 ps-4 pe-3">
                    <div class="text-center text-white mb-4">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nombre']); ?>&background=random" class="rounded-circle mb-2" width="80">
                        <h6><?php echo htmlspecialchars($_SESSION['nombre']); ?></h6>
                        <small><?php echo ucfirst($_SESSION['rol']); ?></small>
                    </div>
                    
                    <hr class="bg-light">
                    
                    <ul class="nav flex-column">
                        <?php if ($_SESSION['rol'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo activeMenu($_SERVER['SCRIPT_NAME'], 'dashboard.php') ?>" 
                               href="<?php echo url('admin/dashboard.php'); ?>">
                                <i class="fas fa-fw fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo activeMenu($_SERVER['SCRIPT_NAME'], 'usuarios') ?>" 
                               href="<?php echo url('admin/usuarios/'); ?>">
                                <i class="fas fa-fw fa-users"></i> Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo activeMenu($_SERVER['SCRIPT_NAME'], 'cursos') ?>" 
                               href="<?php echo url('admin/cursos/'); ?>">
                                <i class="fas fa-fw fa-book"></i> Cursos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo activeMenu($_SERVER['SCRIPT_NAME'], 'reportes') ?>" 
                               href="<?php echo url('admin/reportes/'); ?>">
                                <i class="fas fa-fw fa-chart-bar"></i> Reportes
                            </a>
                        </li>
                        <?php elseif ($_SESSION['rol'] == 'docente'): ?>
                        <!-- Menú para docentes -->
                        <?php elseif ($_SESSION['rol'] == 'estudiante'): ?>
                        <!-- Menú para estudiantes -->
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Contenido principal -->
            <div class="<?php echo isset($_SESSION['id_usuario']) ? 'col-lg-10 main-content' : 'col-12'; ?>">