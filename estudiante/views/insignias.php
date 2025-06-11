<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php
$insignias = $insignias ?? [];
$insignias_disponibles = $insignias_disponibles ?? [];
?>
<div class="container mt-4">
    <h2><i class="fas fa-award me-2"></i>Mis Insignias</h2>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Insignias Obtenidas</h4>
                </div>
                <div class="card-body">
                    <?php if (count($insignias) > 0): ?>
                    <div class="row">
                        <?php foreach ($insignias as $insignia): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-medal fa-3x text-success mb-2"></i>
                                    <h5><?= htmlspecialchars($insignia['nombre']) ?></h5>
                                    <p class="small"><?= htmlspecialchars($insignia['descripcion']) ?></p>
                                    <small class="text-muted">
                                        Obtenida el <?= date('d/m/Y', strtotime($insignia['fecha_obtencion'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <h5>Aún no tienes insignias</h5>
                        <p>Completa misiones y actividades para obtener insignias.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">Insignias Disponibles</h4>
                </div>
                <div class="card-body">
                    <?php if (count($insignias_disponibles) > 0): ?>
                    <div class="row">
                        <?php foreach ($insignias_disponibles as $insignia): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card border-secondary">
                                <div class="card-body text-center">
                                    <i class="fas fa-medal fa-3x text-secondary mb-2"></i>
                                    <h5><?= htmlspecialchars($insignia['nombre']) ?></h5>
                                    <p class="small"><?= htmlspecialchars($insignia['descripcion']) ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info text-center">
                        No hay insignias disponibles actualmente.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>