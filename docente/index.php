<?php
session_start();

// Verificar si el usuario es docente
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'docente') {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../includes/conexion.php';

$accion = $_GET['accion'] ?? 'dashboard';
$id = $_GET['id'] ?? null;

switch ($accion) {
    case 'dashboard':
        require_once __DIR__ . '/controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->mostrarDashboard();
        break;

    case 'cursos':
        require_once __DIR__ . '/controllers/CursoController.php';
        $controller = new CursoController();
        $controller->mostrarCursos();
        break;

    case 'crear_curso':
        require_once __DIR__ . '/controllers/CursoController.php';
        $controller = new CursoController();
        $controller->crearCurso();
        break;

    case 'editar_curso':
        if ($id) {
            require_once __DIR__ . '/controllers/CursoController.php';
            $controller = new CursoController();
            $controller->editarCurso($id);
        }
        break;

    case 'eliminar_curso':
        if ($id) {
            require_once __DIR__ . '/controllers/CursoController.php';
            $controller = new CursoController();
            $controller->eliminarCurso($id);
        }
        break;

    case 'actividades':
        include __DIR__ . '/views/actividades_listado.php';
        break;

    case 'nueva_actividad':
        include __DIR__ . '/views/nueva_actividad.php';
        break;

    case 'editar_actividad':
        include __DIR__ . '/views/editar_actividad.php';
        break;

    case 'eliminar_actividad':
        require_once __DIR__ . '/controllers/eliminar_actividad.php';
        break;

    case 'guardar_actividad':
        require_once __DIR__ . '/controllers/guardar_actividad.php';
        break;

    case 'guardar_edicion_actividad':
        require_once __DIR__ . '/controllers/guardar_edicion_actividad.php';
        break;

    case 'entregas':
        include __DIR__ . '/views/entregas_listado.php';
        break;

    case 'grupos':
        // Puedes crear un controlador para grupos si lo necesitas
        // require_once __DIR__ . '/controllers/GrupoController.php';
        // $controller = new GrupoController();
        // $controller->mostrarGrupos();
        break;

    case 'misiones':
        include __DIR__ . '/views/misiones_listado.php';
        break;

    case 'ver_mision':
        include __DIR__ . '/views/ver_mision.php';
        break;

    case 'ver_actividad':
        include __DIR__ . '/views/ver_actividad.php';
        break;

    case 'ver_estudiante':
        include __DIR__ . '/views/ver_estudiante.php';
        break;

    case 'reportes':
        require_once __DIR__ . '/models/CursoModel.php';
        require_once __DIR__ . '/models/ReporteModel.php';
        $cursoModel = new CursoModel();
        $reporteModel = new ReporteModel();
        $id_docente = $_SESSION['id_usuario'];
        // Este método debe existir y devolver los cursos asignados al docente
        $cursos = $cursoModel->listarCursosPorDocente($id_docente);

        // Puedes obtener estadísticas y estudiantes en riesgo aquí si tienes los métodos
        $estadisticasCumplimiento = [];
        $estudiantesEnRiesgo = [];
        include __DIR__ . '/views/reportes.php';
        break;

    case 'cursos_horarios':
        require_once __DIR__ . '/controllers/CursosHorariosController.php';
        $controller = new CursosHorariosController();
        $controller->mostrarCursosHorarios();
        break;

    case 'generar_reporte':
        require_once __DIR__ . '/controllers/generar_reporte.php';
        break;

    case 'insignias':
        require_once __DIR__ . '/models/InsigniaModel.php';
        $model = new InsigniaModel();
        // Si quieres mostrar solo las del docente logueado:
        // $insignias = $model->obtenerPorUsuario($_SESSION['id_usuario']);
        // Si quieres mostrar todas:
        $insignias = $model->obtenerTodas();
        include __DIR__ . '/views/insignias_listado.php';
        break;

    default:
        require_once __DIR__ . '/controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->mostrarDashboard();
        break;
}
?>