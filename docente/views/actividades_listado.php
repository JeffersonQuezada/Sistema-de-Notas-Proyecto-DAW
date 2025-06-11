<?php
require_once '../models/ActividadModel.php';
require_once '../models/CursoModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_docente = $_SESSION['id_usuario'];

$cursoModel = new CursoModel();
$actividadModel = new ActividadModel();

// Obtener cursos del docente
$cursos = $cursoModel->listarCursosPorDocente($id_docente);

// Obtener actividades por curso
$actividades_por_curso = [];
foreach ($cursos as $curso) {
    $actividades_por_curso[$curso['id_curso']] = [
        'curso' => $curso,
        'actividades' => $actividadModel->listarActividadesPorCurso($curso['id_curso'])
    ];
}

// Mensajes
$mensaje = '';
$tipo_mensaje = '';

if (isset($_GET['success'])) {
    $tipo_mensaje = 'success';
    $mensaje = isset($_GET['msg']) ? $_GET['msg'] : 'Operación realizada exitosamente.';
}

if (isset($_GET['error'])) {
    $tipo_mensaje = 'danger';
    $mensaje = isset($_GET['msg']) ? $_GET['msg'] : 'Ha ocurrido un error.';
}
?>

<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-tasks me-2"></i>Mis Actividades</h2>
        <a href="nueva_actividad.php" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> Nueva Actividad
        </a>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show" role="alert">
            <i class="fas fa-<?= $tipo_mensaje === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
            <?= htmlspecialchars($mensaje) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($actividades_por_curso)): ?>
        <?php foreach ($actividades_por_curso as $curso_id => $data): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#curso<?= $curso_id ?>" style="cursor:pointer;">
                    <div>
                        <strong><?= htmlspecialchars($data['curso']["nombre_curso"]) ?></strong>
                        <?php if (!empty($data['curso']['codigo_curso'])): ?>
                            <span class="badge bg-light text-primary ms-2"><?= htmlspecialchars($data['curso']['codigo_curso']) ?></span>
                        <?php endif; ?>
                    </div>
                    <span class="badge bg-light text-primary"><?= count($data['actividades']) ?> actividad(es)</span>
                </div>
                <div class="collapse show" id="curso<?= $curso_id ?>">
                    <div class="card-body">
                        <?php if (!empty($data['actividades'])): ?>
                            <div class="list-group">
                                <?php foreach ($data['actividades'] as $actividad): ?>
                                    <?php
                                    $fecha_limite = new DateTime($actividad['fecha_limite']);
                                    $hoy = new DateTime();
                                    $diferencia = $hoy->diff($fecha_limite);
                                    $dias_restantes = (int)$diferencia->format('%r%a');
                                    $clase = '';
                                    $texto_fecha = '';
                                    if ($fecha_limite < $hoy) {
                                        $clase = 'list-group-item-danger';
                                        $texto_fecha = 'Vencida hace ' . abs($dias_restantes) . ' día(s)';
                                    } elseif ($dias_restantes <= 3) {
                                        $clase = 'list-group-item-warning';
                                        $texto_fecha = $dias_restantes == 0 ? 'Vence hoy' : 'Vence en ' . $dias_restantes . ' día(s)';
                                    } else {
                                        $clase = 'list-group-item-success';
                                        $texto_fecha = 'Vence en ' . $dias_restantes . ' día(s)';
                                    }
                                    ?>
                                    <div class="list-group-item <?= $clase ?> mb-2 rounded shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-1"><?= htmlspecialchars($actividad['nombre']) ?></h5>
                                                <small class="text-muted"><?= htmlspecialchars($actividad['tipo']) ?> | <?= $texto_fecha ?></small>
                                                <p class="mb-1"><?= htmlspecialchars(substr($actividad['descripcion'], 0, 120)) ?><?= strlen($actividad['descripcion']) > 120 ? '...' : '' ?></p>
                                                <span class="badge bg-secondary"><i class="fas fa-calendar-alt me-1"></i> <?= date('d/m/Y H:i', strtotime($actividad['fecha_limite'])) ?></span>
                                            </div>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="editar_actividad.php?id=<?= $actividad['id_actividad'] ?>" class="btn btn-outline-primary" title="Editar"><i class="fas fa-edit"></i></a>
                                                <a href="ver_actividad.php?id=<?= $actividad['id_actividad'] ?>" class="btn btn-outline-info" title="Ver"><i class="fas fa-eye"></i></a>
                                                <form action="../controllers/eliminar_actividad.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar esta actividad?');">
                                                    <input type="hidden" name="id_actividad" value="<?= $actividad['id_actividad'] ?>">
                                                    <button type="submit" class="btn btn-outline-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                No hay actividades para este curso.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            No tienes cursos asignados. Contacta al administrador.
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>