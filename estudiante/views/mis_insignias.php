<?php include __DIR__ . '/../../includes/header.php'; ?>
<div class="container mt-4">
    <h2>Mis Insignias</h2>
    <div class="row">
        <?php foreach ($insignias as $insignia): ?>
        <div class="col-md-3 text-center mb-4">
            <?php if (!empty($insignia['icono'])): ?>
                <img src="<?= htmlspecialchars($insignia['icono']) ?>" alt="icono" width="64">
            <?php endif; ?>
            <h5><?= htmlspecialchars($insignia['nombre']) ?></h5>
            <p><?= htmlspecialchars($insignia['descripcion']) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>