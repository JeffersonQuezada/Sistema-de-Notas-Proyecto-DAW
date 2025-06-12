<?php include __DIR__ . '/../../includes/header.php'; ?>
<div class="container mt-4">
    <h2>Insignias</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Icono</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($insignias as $insignia): ?>
            <tr>
                <td><?= htmlspecialchars($insignia['nombre']) ?></td>
                <td><?= htmlspecialchars($insignia['descripcion']) ?></td>
                <td>
                    <?php if (!empty($insignia['icono'])): ?>
                        <img src="<?= htmlspecialchars($insignia['icono']) ?>" alt="icono" width="32">
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>