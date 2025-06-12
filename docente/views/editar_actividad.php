<?php
require_once __DIR__ . '/../models/ActividadModel.php';
require_once __DIR__ . '/../models/CursoModel.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../login.php");
    exit();
}

$id_docente = $_SESSION['id_usuario'];
$actividadModel = new ActividadModel();
$cursoModel = new CursoModel();

if (!isset($_GET['id'])) {
    header("Location: index.php?accion=actividades&error=1&msg=Actividad no especificada");
    exit();
}

$id_actividad = $_GET['id'];
// Obtener datos de la actividad
$actividad = $actividadModel->obtenerActividadPorId($id_actividad);
if (!$actividad) {
    header("Location: index.php?accion=actividades&error=1&msg=Actividad no encontrada");
    exit();
}

// Verificar que el curso pertenezca al docente
if (!$cursoModel->verificarDocenteCurso($id_docente, $actividad['id_curso'])) {
    header("Location: index.php?accion=actividades&error=1&msg=No tienes permiso para editar esta actividad");
    exit();
}

// Obtener cursos del docente para el select
$cursos = $cursoModel->listarCursosPorDocente($id_docente);

include __DIR__ . '/../../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0"><i class="fa fa-edit"></i> Editar Actividad</h3>
                </div>
                <div class="card-body">
                    <form action="index.php?accion=guardar_edicion_actividad" method="POST">
                        <input type="hidden" name="id_actividad" value="<?= $actividad['id_actividad'] ?>">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required value="<?= htmlspecialchars($actividad['nombre']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= htmlspecialchars($actividad['descripcion']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_limite" class="form-label">Fecha Límite</label>
                            <input type="datetime-local" class="form-control" id="fecha_limite" name="fecha_limite" required value="<?= date('Y-m-d\TH:i', strtotime($actividad['fecha_limite'])) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="id_curso" class="form-label">Curso</label>
                            <select name="id_curso" id="id_curso" class="form-control" required>
                                <?php foreach($cursos as $curso): ?>
                                    <option value="<?= $curso['id_curso'] ?>" <?= $curso['id_curso'] == $actividad['id_curso'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($curso['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo</label>
                            <select name="tipo" id="tipo" class="form-control">
                                <option value="Tarea" <?= $actividad['tipo'] == 'Tarea' ? 'selected' : '' ?>>Tarea</option>
                                <option value="Examen" <?= $actividad['tipo'] == 'Examen' ? 'selected' : '' ?>>Examen</option>
                                <option value="Proyecto" <?= $actividad['tipo'] == 'Proyecto' ? 'selected' : '' ?>>Proyecto</option>
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fa fa-save"></i> Guardar Cambios
                            </button>
                            <a href="index.php?accion=actividades" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>