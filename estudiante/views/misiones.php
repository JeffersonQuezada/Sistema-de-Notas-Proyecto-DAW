<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php
$misiones = $misiones ?? [];
?>
<div class="container mt-4">
    <h2>Misiones Disponibles</h2>
    <div class="row">
        <?php foreach ($misiones as $mision): ?>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($mision['titulo']) ?></h5>
                        <p><?= htmlspecialchars($mision['descripcion']) ?></p>
                        <span class="badge bg-info"><?= htmlspecialchars($mision['recompensa']) ?></span>
                        <?php if ($mision['aceptada']): ?>
                            <span class="badge bg-success">Aceptada</span>
                        <?php else: ?>
                            <form method="POST" action="../controllers/MisionController.php?accion=aceptar">
                                <input type="hidden" name="id_mision" value="<?= $mision['id_mision'] ?>">
                                <button class="btn btn-primary btn-sm mt-2">Aceptar Misión</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>