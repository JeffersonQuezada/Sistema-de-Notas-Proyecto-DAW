<?php
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../includes/conexion.php';

$accion = $_GET['accion'] ?? 'dashboard';
$id = $_GET['id'] ?? null;

// Función para validar ID cuando sea requerido
function validarId($id) {
    return $id && is_numeric($id) && $id > 0;
}

// Función para manejar errores 404
function mostrar404() {
    http_response_code(404);
    $_SESSION['error'] = 'Página no encontrada';
    header("Location: index.php?accion=dashboard");
    exit();
}

try {
    // Enrutamiento para el administrador
    switch ($accion) {
        case 'dashboard':
            require_once __DIR__ . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->mostrarDashboard();
            break;
            
        // === GESTIÓN DE USUARIOS ===
        case 'usuarios':
            require_once __DIR__ . '/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->listarUsuarios();
            break;
            
        case 'crear_usuario':
            require_once __DIR__ . '/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->crearUsuario($_POST);
            } else {
                $controller->mostrarFormularioCreacion();
            }
            break;
            
        case 'editar_usuario':
            if (!validarId($id)) {
                $_SESSION['error'] = 'ID de usuario inválido';
                header('Location: index.php?accion=usuarios');
                exit();
            }
            
            require_once __DIR__ . '/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->actualizarUsuario($id, $_POST);
            } else {
                $controller->mostrarFormularioEdicion($id);
            }
            break;
            
        case 'eliminar_usuario':
            if (!validarId($id)) {
                $_SESSION['error'] = 'ID de usuario inválido';
                header('Location: index.php?accion=usuarios');
                exit();
            }
            
            // Solo permitir eliminación via POST por seguridad
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                require_once __DIR__ . '/controllers/UsuarioController.php';
                $controller = new UsuarioController();
                $controller->eliminarUsuario($id);
            } else {
                $_SESSION['error'] = 'Método no permitido';
                header('Location: index.php?accion=usuarios');
                exit();
            }
            break;
            
        // === GESTIÓN DE CURSOS ===
        case 'cursos_admin':
            require_once __DIR__ . '/controllers/CursoAdminController.php';
            $controller = new CursoAdminController();
            $controller->listarCursos();
            break;
            
        case 'crear_curso':
            require_once __DIR__ . '/controllers/CursoAdminController.php';
            $controller = new CursoAdminController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->crearCurso($_POST);
            } else {
                $controller->mostrarFormularioCreacionCurso();
            }
            break;
            
        case 'editar_curso':
            if (!validarId($id)) {
                $_SESSION['error'] = 'ID de curso inválido';
                header('Location: index.php?accion=cursos_admin');
                exit();
            }
            
            require_once __DIR__ . '/controllers/CursoAdminController.php';
            $controller = new CursoAdminController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->actualizarCurso($id, $_POST);
            } else {
                $controller->mostrarFormularioEdicionCurso($id);
            }
            break;
            
        case 'eliminar_curso':
            if (!validarId($id)) {
                $_SESSION['error'] = 'ID de curso inválido';
                header('Location: index.php?accion=cursos_admin');
                exit();
            }
            
            // Solo permitir eliminación via POST por seguridad
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                require_once __DIR__ . '/controllers/CursoAdminController.php';
                $controller = new CursoAdminController();
                $controller->eliminarCurso($id);
            } else {
                $_SESSION['error'] = 'Método no permitido';
                header('Location: index.php?accion=cursos_admin');
                exit();
            }
            break;
            
        // === REPORTES ===
        case 'reportes':
            require_once __DIR__ . '/controllers/ReporteController.php';
            $controller = new ReporteController();
            $controller->mostrarReportes();
            break;
            
        case 'reporte_usuarios':
            require_once __DIR__ . '/controllers/ReporteController.php';
            $controller = new ReporteController();
            $controller->generarReporteUsuarios();
            break;
            
        case 'reporte_cursos':
            require_once __DIR__ . '/controllers/ReporteController.php';
            $controller = new ReporteController();
            $controller->generarReporteCursos();
            break;
            
        case 'reporte_actividades':
            require_once __DIR__ . '/controllers/ReporteController.php';
            $controller = new ReporteController();
            $controller->generarReporteActividades();
            break;
            
        // === FUNCIONALIDADES ADICIONALES SUGERIDAS ===
        case 'configuracion':
            // Para futuras configuraciones del sistema
            $_SESSION['info'] = 'Funcionalidad en desarrollo';
            header('Location: index.php?accion=dashboard');
            break;
            
        case 'backup':
            // Para respaldos del sistema
            $_SESSION['info'] = 'Funcionalidad en desarrollo';
            header('Location: index.php?accion=dashboard');
            break;
            
        case 'logs':
            // Para ver logs del sistema
            $_SESSION['info'] = 'Funcionalidad en desarrollo';
            header('Location: index.php?accion=dashboard');
            break;
            
        default:
            // Si la acción no existe, mostrar dashboard con mensaje de advertencia
            $_SESSION['warning'] = 'La acción solicitada no existe';
            require_once __DIR__ . '/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->mostrarDashboard();
            break;
    }
    
} catch (Exception $e) {
    // Manejo de errores generales
    error_log("Error en admin/index.php: " . $e->getMessage());
    $_SESSION['error'] = 'Ha ocurrido un error interno. Por favor, intenta nuevamente.';
    
    // Redirigir al dashboard en caso de error
    header('Location: index.php?accion=dashboard');
    exit();
}
?>