<?php
// filepath: admin/views/cursos_horarios.php
include __DIR__ . '/../../includes/header.php';

// $horarios debe estar definido por el controlador
$id_curso = $_GET['id_curso'] ?? null;
?>

<div class="container mt-4">
    <h2>Horarios del Curso</h2>
    <a href="../index.php?accion=cursos" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver a Cursos
    </a>
    <div class="card mb-4">
        <div class="card-header">Agregar Horario</div>
        <div class="card-body">
            <form method="POST" action="../index.php?accion=horario_crear">
                <input type="hidden" name="id_curso" value="<?= htmlspecialchars($id_curso) ?>">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <select name="dia" class="form-control" required>
                            <option value="">Día</option>
                            <option>Lunes</option>
                            <option>Martes</option>
                            <option>Miércoles</option>
                            <option>Jueves</option>
                            <option>Viernes</option>
                            <option>Sábado</option>
                            <option>Domingo</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="time" name="hora_inicio" class="form-control" required placeholder="Hora inicio">
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="time" name="hora_fin" class="form-control" required placeholder="Hora fin">
                    </div>
                    <div class="col-md-3 mb-2">
                        <button class="btn btn-success w-100"><i class="fas fa-plus"></i> Agregar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <h4>Horarios Registrados</h4>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Día</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($horarios)): ?>
                <?php foreach ($horarios as $horario): ?>
                    <tr>
                        <td><?= htmlspecialchars($horario['dia']) ?></td>
                        <td><?= htmlspecialchars($horario['hora_inicio']) ?></td>
                        <td><?= htmlspecialchars($horario['hora_fin']) ?></td>
                        <td>
                            <a href="../index.php?accion=horario_eliminar&id_horario=<?= $horario['id_horario'] ?>&id_curso=<?= $id_curso ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Seguro que deseas eliminar este horario?');">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center text-muted">No hay horarios registrados para este curso.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>