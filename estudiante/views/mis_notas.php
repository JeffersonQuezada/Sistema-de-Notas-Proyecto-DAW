<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php
$notas = $notas ?? [];
$promedios = $promedios ?? [];
$promedio_general = $promedio_general ?? 0;
?>

<div class="container mt-4">
    <h2><i class="fas fa-clipboard-list me-2"></i>Mis Notas</h2>
    
    <!-- Resumen General -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3><?= number_format($promedio_general, 2) ?></h3>
                    <p class="mb-0">Promedio General</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3><?= count($notas) ?></h3>
                    <p class="mb-0">Actividades Calificadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3><?= count($promedios) ?></h3>
                    <p class="mb-0">Cursos con Notas</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Promedios por Curso -->
    <?php if (count($promedios) > 0): ?>
    <div class="card mb-4">
        <div class="card-header">
            <h4><i class="fas fa-chart-bar me-2"></i>Promedios por Curso</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach ($promedios as $promedio): ?>
                <div class="col-md-6 mb-3">
                    <div class="d-flex justify-content-between align-items-center p-3 border rounded">
                        <div>
                            <strong><?= htmlspecialchars($promedio['nombre_curso']) ?></strong>
                            <br>
                            <small class="text-muted"><?= $promedio['total_notas'] ?> actividades</small>
                        </div>
                        <div class="text-end">
                            <h4 class="mb-0 <?= $promedio['promedio'] >= 7 ? 'text-success' : ($promedio['promedio'] >= 6 ? 'text-warning' : 'text-danger') ?>">
                                <?= number_format($promedio['promedio'], 2) ?>
                            </h4>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Detalle de Notas -->
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-list me-2"></i>Detalle de Notas</h4>
        </div>
        <div class="card-body">
            <?php if (count($notas) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Actividad</th>
                            <th>Tipo</th>
                            <th>Fecha Entrega</th>
                            <th>Nota</th>
                            <th>Comentario</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notas as $nota): ?>
                        <tr>
                            <td><?= htmlspecialchars($nota['nombre_curso']) ?></td>
                            <td><?= htmlspecialchars($nota['actividad_nombre']) ?></td>
                            <td>
                                <span class="badge bg-secondary">
                                    <?= htmlspecialchars($nota['actividad_tipo']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($nota['fecha_entrega'])) ?></td>
                            <td>
                                <span class="badge bg-<?= $nota['nota'] >= 7 ? 'success' : ($nota['nota'] >= 6 ? 'warning' : 'danger') ?> fs-6">
                                    <?= number_format($nota['nota'], 2) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($nota['comentario'])): ?>
                                    <small><?= htmlspecialchars($nota['comentario']) ?></small>
                                <?php else: ?>
                                    <small class="text-muted">Sin comentarios</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($nota['nota'] >= 7): ?>
                                    <i class="fas fa-check-circle text-success" title="Aprobado"></i>
                                <?php elseif ($nota['nota'] >= 6): ?>
                                    <i class="fas fa-exclamation-triangle text-warning" title="Regular"></i>
                                <?php else: ?>
                                    <i class="fas fa-times-circle text-danger" title="Reprobado"></i>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-2x mb-3"></i>
                <h5>No tienes notas aún</h5>
                <p>Una vez que tus profesores califiquen tus entregas, aparecerán aquí.</p>
                <a href="index.php?accion=cursos" class="btn btn-primary">
                    <i class="fas fa-book-open me-2"></i>Ver Cursos Disponibles
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>