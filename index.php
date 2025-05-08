<?php
session_start();

// Si el usuario ya está logueado, redirigir según su rol
if (isset($_SESSION['id_usuario'])) {
    switch ($_SESSION['rol']) {
        case 'admin':
            header("Location: admin/dashboard.php");
            exit();
        case 'docente':
            header("Location: docente/dashboard.php");
            exit();
        case 'estudiante':
            header("Location: estudiante/dashboard.php");
            exit();
    }
}

// Si no está logueado, mostrar página de inicio
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Cursos y Notas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #2575fc;
        }
        .btn-primary {
            background-color: #6a11cb;
            border-color: #6a11cb;
        }
        .btn-primary:hover {
            background-color: #5a0db3;
            border-color: #5a0db3;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Sistema Académico</a>
            <div class="ms-auto">
                <a href="login.php" class="btn btn-outline-light me-2">Iniciar Sesión</a>
                <a href="crear_cuenta.php" class="btn btn-primary">Registrarse</a>
            </div>
        </div>
    </nav>

    <!-- Sección Hero -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Plataforma de Gestión Académica</h1>
            <p class="lead mb-5">Sistema integral para administración de cursos, calificaciones y asistencia</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="login.php" class="btn btn-light btn-lg px-4">Acceder al Sistema</a>
                <a href="#features" class="btn btn-outline-light btn-lg px-4">Conoce más</a>
            </div>
        </div>
    </section>

    <!-- Características -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col">
                    <h2 class="fw-bold">Funcionalidades Principales</h2>
                    <p class="lead text-muted">Todo lo que necesitas para la gestión académica</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <h4 class="card-title">Gestión de Cursos</h4>
                            <p class="card-text">Crea y administra cursos, asigna docentes y gestiona horarios sin conflictos.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <h4 class="card-title">Control Estudiantil</h4>
                            <p class="card-text">Inscribe estudiantes, registra asistencias y gestiona grupos.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h4 class="card-title">Sistema de Calificaciones</h4>
                            <p class="card-text">Registro detallado de notas y generación de reportes académicos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Llamado a la acción -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">¿Listo para comenzar?</h2>
            <p class="lead mb-4">Regístrate ahora y descubre cómo podemos mejorar tu gestión académica</p>
            <a href="crear_cuenta.php" class="btn btn-primary btn-lg px-4">Crear una cuenta</a>
        </div>
    </section>

    <!-- Pie de página -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">© <?php echo date('Y'); ?> Sistema de Gestión Académica. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>