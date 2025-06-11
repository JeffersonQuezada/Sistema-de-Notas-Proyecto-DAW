<?php
// ==================================================
// 4. CREAR estudiante/views/actividades_listado.php
// ==================================================
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php
$actividades = $actividades ?? [];
$curso = $curso ?? null;
?>
<div class="container mt-4">
    <?php if ($curso): ?>
        <h2>Actividades de <?= htmlspecialchars($curso['nombre_curso']) ?></h2>
        <p class="text-muted"><?= htmlspecialchars($curso['descripcion']) ?></p>
    <?php else: ?>
        <h2>Actividades</h2>
    <?php endif; ?>
    
    <div class="row">
        <?php if (count($actividades) > 0): ?>
            <?php foreach ($actividades as $actividad): ?>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5><?= htmlspecialchars($actividad['nombre']) ?></h5>
                            <p><?= htmlspecialchars(substr($actividad['descripcion'], 0, 100)) ?>...</p>
                            <p><strong>Tipo:</strong> <?= htmlspecialchars($actividad['tipo']) ?></p>
                            <p><strong>Fecha límite:</strong> <?= date('d/m/Y H:i', strtotime($actividad['fecha_limite'])) ?></p>
                            
                            <?php
                            $fecha_limite = new DateTime($actividad['fecha_limite']);
                            $ahora = new DateTime();
                            if ($fecha_limite < $ahora) {
                                echo '<span class="badge bg-danger">Vencida</span>';
                            } elseif ($fecha_limite->diff($ahora)->days <= 3) {
                                echo '<span class="badge bg-warning text-dark">Próxima a vencer</span>';
                            } else {
                                echo '<span class="badge bg-success">Activa</span>';
                            }
                            ?>
                            
                            <div class="mt-2">
                                <a href="index.php?accion=ver_actividad&id=<?= $actividad['id_actividad'] ?>" class="btn btn-primary btn-sm">Ver detalles</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    No hay actividades disponibles para este curso.
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="mt-3">
        <a href="index.php?accion=cursos" class="btn btn-secondary">Volver a cursos</a>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
