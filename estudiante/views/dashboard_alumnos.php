?>
<?php include __DIR__ . '/../../includes/header.php'; ?>

<?php
// Inicializar variables para evitar warnings
$cursos = $cursos ?? [];
$entregas = $entregas ?? [];
$promedio = $promedio ?? 0;
$insignias = $insignias ?? [];
$misiones = $misiones ?? [];
?>

<div class="container mt-4">
    <h2>Mi Panel</h2>
    <div class="row">
        <div class="col-md-6">
            <h4>Mis Cursos</h4>
            <ul class="list-group">
                <?php if (count($cursos) > 0): ?>
                    <?php foreach ($cursos as $curso): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($curso['nombre_curso']) ?>
                            <a href="index.php?accion=actividades&id=<?= $curso['id_curso'] ?>" class="btn btn-sm btn-outline-primary">Ver actividades</a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item text-muted">No estás inscrito en ningún curso.</li>
                <?php endif; ?>
            </ul>
            <div class="mt-3">
                <a href="index.php?accion=cursos" class="btn btn-primary">Ver todos los cursos</a>
            </div>
        </div>
        <div class="col-md-6">
            <h4>Últimas Entregas</h4>
            <ul class="list-group">
                <?php if (count($entregas) > 0): ?>
                    <?php foreach (array_slice($entregas, 0, 5) as $entrega): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($entrega['actividad']) ?> - <?= htmlspecialchars($entrega['fecha_entrega']) ?>
                            <span class="badge bg-<?= isset($entrega['calificacion']) && $entrega['calificacion'] !== null ? 'success' : 'warning' ?>">
                                <?= isset($entrega['calificacion']) && $entrega['calificacion'] !== null ? 'Calificado' : 'Pendiente' ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item text-muted">No has realizado entregas aún.</li>
                <?php endif; ?>
            </ul>
            <div class="mt-3">
                <a href="index.php?accion=mis_entregas" class="btn btn-secondary">Ver todas mis entregas</a>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <h4>Promedio General</h4>
            <div class="alert alert-info">
                <strong><?= number_format($promedio, 2) ?></strong>
            </div>
        </div>
        <div class="col-md-4">
            <h4>Insignias</h4>
            <?php if (count($insignias) > 0): ?>
                <?php foreach (array_slice($insignias, 0, 3) as $insignia): ?>
                    <span class="badge bg-success mb-1"><?= htmlspecialchars($insignia['nombre']) ?></span>
                <?php endforeach; ?>
                <div class="mt-2">
                    <a href="index.php?accion=insignias" class="btn btn-sm btn-outline-success">Ver todas</a>
                </div>
            <?php else: ?>
                <div class="text-muted">Aún no tienes insignias.</div>
            <?php endif; ?>
        </div>
        <div class="col-md-4">
            <h4>Misiones</h4>
            <?php if (count($misiones) > 0): ?>
                <?php foreach (array_slice($misiones, 0, 3) as $mision): ?>
                    <span class="badge bg-info mb-1"><?= htmlspecialchars($mision['titulo']) ?></span>
                <?php endforeach; ?>
                <div class="mt-2">
                    <a href="index.php?accion=misiones" class="btn btn-sm btn-outline-info">Ver todas</a>
                </div>
            <?php else: ?>
                <div class="text-muted">Aún no tienes misiones.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

