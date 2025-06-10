<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php
$insignias = $insignias ?? [];
?>
<div class="container mt-4">
    <h2>Mis Insignias</h2>
    <div class="row">
        <?php foreach ($insignias as $insignia): ?>
            <div class="col-md-3">
                <div class="card mb-3 text-center">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($insignia['nombre']) ?></h5>
                        <p><?= htmlspecialchars($insignia['descripcion']) ?></p>
                        <span class="badge bg-success"><?= htmlspecialchars($insignia['tipo']) ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>