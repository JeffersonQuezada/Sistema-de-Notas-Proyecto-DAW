<?php

session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'estudiante') {
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
        
    case 'cursos':
        require_once 'controllers/CursoController.php';
        $controller = new CursoController();
        $controller->mostrarCursosDisponibles();
        break;
        
    case 'mis_cursos':
        require_once 'controllers/CursoController.php';
        $controller = new CursoController();
        $controller->mostrarCursosInscritos();
        break;
        
    case 'inscribir_curso':
        if ($id) {
            require_once 'controllers/CursoController.php';
            $controller = new CursoController();
            $controller->inscribir($id);
        }
        break;
        
    case 'desinscribir_curso':
        if ($id) {
            require_once 'controllers/CursoController.php';
            $controller = new CursoController();
            $controller->desinscribir($id);
        }
        break;
        
    case 'actividades':
        if ($id) {
            require_once 'controllers/ActividadController.php';
            $controller = new ActividadController();
            $controller->listarPorCurso($id);
        }
        break;
        
    case 'ver_actividad':
        if ($id) {
            require_once 'controllers/ActividadController.php';
            $controller = new ActividadController();
            $controller->ver($id);
        }
        break;
        
    case 'mis_entregas':
        require_once 'controllers/EntregaController.php';
        $controller = new EntregaController();
        $controller->misEntregas();
        break;
        
    case 'entregar':
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'controllers/EntregaController.php';
            $controller = new EntregaController();
            $controller->entregar($id);
        }
        break;
        
    case 'entregar_actividad':
        require_once 'controllers/EntregaController.php';
        $controller = new EntregaController();
        $controller->entregar($_GET['id']);
        break;
        
    case 'mis_notas':
        require_once 'controllers/NotaController.php';
        $controller = new NotaController();
        $controller->misNotas();
        break;
        
    case 'misiones':
        require_once 'controllers/MisionController.php';
        $controller = new MisionController();
        $controller->mostrarMisiones();
        break;
        
    case 'aceptar_mision':
        if ($id) {
            require_once 'controllers/MisionController.php';
            $controller = new MisionController();
            $controller->aceptar($id);
        }
        break;
        
    case 'insignias':
        require_once __DIR__ . '/models/InsigniaModel.php';
        $model = new InsigniaModel();
        $insignias = $model->obtenerPorUsuario($_SESSION['id_usuario']);
        include __DIR__ . '/views/mis_insignias.php';
        break;
        
    case 'perfil':
        require_once 'controllers/PerfilController.php';
        $controller = new PerfilController();
        $controller->verPerfil();
        break;
        

        
    case 'cambiar_contrasena':
        require_once 'controllers/PerfilController.php';
        $controller = new PerfilController();
        $controller->mostrarFormularioCambioContrasena();
        break;
        
    case 'guardar_cambio_contrasena':
        require_once 'controllers/PerfilController.php';
        $controller = new PerfilController();
        $controller->cambiarContrasena($_POST['actual'], $_POST['nueva']);
        break;
        
    default:
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->mostrarDashboard();
        break;
}