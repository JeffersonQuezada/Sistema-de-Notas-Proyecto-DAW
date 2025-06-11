<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php
$entregas = $entregas ?? [];
?>
<div class="container mt-4">
    <h2><i class="fas fa-file-upload me-2"></i>Mis Entregas</h2>
    
    <div class="card">
        <div class="card-body">
            <?php if (count($entregas) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Actividad</th>
                            <th>Tipo</th>
                            <th>Fecha Entrega</th>
                            <th>Archivo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entregas as $entrega): ?>
                        <tr>
                            <td><?= htmlspecialchars($entrega['nombre_curso'] ?? '') ?></td>
                            <td><?= htmlspecialchars($entrega['actividad'] ?? '') ?></td>
                            <td>
                                <span class="badge bg-secondary">
                                    <?= htmlspecialchars($entrega['tipo'] ?? '') ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($entrega['fecha_entrega'])) ?></td>
                            <td>
                                <?php if (!empty($entrega['archivo'])): ?>
                                    <a href="../../uploads/<?= urlencode($entrega['archivo']) ?>" target="_blank">
                                        <i class="fas fa-file-download"></i> Descargar
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (isset($entrega['calificacion'])): ?>
                                    <span class="badge bg-success">Calificado</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Pendiente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?accion=ver_actividad&id=<?= $entrega['id_actividad'] ?>" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-2x mb-3"></i>
                <h5>No has realizado entregas aún</h5>
                <p>Revisa las actividades de tus cursos para realizar entregas.</p>
                <a href="index.php?accion=cursos" class="btn btn-primary">
                    <i class="fas fa-book-open me-2"></i>Ver Cursos
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>