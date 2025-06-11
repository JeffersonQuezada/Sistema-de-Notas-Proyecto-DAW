<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php
$misiones = $misiones ?? [];
?>
<div class="container mt-4">
    <h2><i class="fas fa-bullseye me-2"></i>Misiones</h2>
    
    <div class="row">
        <?php if (count($misiones) > 0): ?>
            <?php foreach ($misiones as $mision): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 <?= $mision['aceptada'] ? 'border-success' : '' ?>">
                    <div class="card-header <?= $mision['aceptada'] ? 'bg-success text-white' : 'bg-primary text-white' ?>">
                        <h5 class="mb-0"><?= htmlspecialchars($mision['titulo']) ?></h5>
                    </div>
                    <div class="card-body">
                        <p><?= htmlspecialchars($mision['descripcion']) ?></p>
                        <div class="mb-3">
                            <span class="badge bg-info">
                                <i class="fas fa-gift me-1"></i>
                                <?= htmlspecialchars($mision['recompensa']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <?php if ($mision['aceptada']): ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i> Aceptada
                                </span>
                                <div class="progress" style="width: 60%; height: 20px;">
                                    <div class="progress-bar progress-bar-striped" 
                                         role="progressbar" 
                                         style="width: <?= $mision['progreso'] ?? 0 ?>%" 
                                         aria-valuenow="<?= $mision['progreso'] ?? 0 ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="index.php?accion=aceptar_mision&id=<?= $mision['id_mision'] ?>" class="btn btn-primary w-100">
                                <i class="fas fa-check-circle me-1"></i> Aceptar Misión
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-2x mb-3"></i>
                <h5>No hay misiones disponibles</h5>
                <p>Vuelve más tarde para ver nuevas misiones disponibles.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>