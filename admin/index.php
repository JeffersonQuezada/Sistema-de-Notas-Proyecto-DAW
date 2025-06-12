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

// Enrutamiento para el administrador
switch ($accion) {
    case 'dashboard':
        require_once __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->mostrarDashboard();
        break;
        
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
        if ($id) {
            require_once __DIR__ . '/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->actualizarUsuario($id, $_POST);
            } else {
                $controller->mostrarFormularioEdicion($id);
            }
        }
        break;
        
    case 'eliminar_usuario':
        if ($id) {
            require_once __DIR__ . '/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->eliminarUsuario($id);
        }
        break;
        
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
        require_once __DIR__ . '/controllers/CursoAdminController.php';
        $controller = new CursoAdminController();
        $controller->mostrarFormularioEdicionCurso($id);
        break;

    case 'actualizar_curso':
        require_once __DIR__ . '/controllers/CursoAdminController.php';
        $controller = new CursoAdminController();
        $controller->actualizarCurso($id, $_POST);
        break;
        
    case 'eliminar_curso':
        if ($id) {
            require_once __DIR__ . '/controllers/CursoAdminController.php';
            $controller = new CursoAdminController();
            $controller->eliminarCurso($id);
        }
        break;
        
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
        
    case 'exportar_cursos_pdf':
        require_once __DIR__ . '/controllers/CursoAdminController.php';
        $controller = new CursoAdminController();
        $controller->exportarCursosPDF();
        break;
        
    case 'exportar_reporte_pdf':
        require_once __DIR__ . '/controllers/ReporteController.php';
        $controller = new ReporteController();
        $controller->exportarReportePDF($_GET['tipo'] ?? '');
        break;
        
    case 'misiones':
        require_once __DIR__ . '/controllers/MisionController.php';
        $controller = new MisionController();
        $controller->listarMisiones();
        break;

    case 'crear_mision':
        require_once __DIR__ . '/controllers/MisionController.php';
        $controller = new MisionController();
        $controller->mostrarFormularioCreacion();
        break;

    case 'guardar_mision':
        require_once __DIR__ . '/controllers/MisionController.php';
        $controller = new MisionController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->crearMision($_POST);
        }
        break;
        
    case 'insignias':
        require_once __DIR__ . '/models/InsigniaModel.php';
        $model = new InsigniaModel();
        $insignias = $model->obtenerTodas();
        include __DIR__ . '/views/insignias_listado.php';
        break;
        
    default:
        require_once __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->mostrarDashboard();
        break;
}