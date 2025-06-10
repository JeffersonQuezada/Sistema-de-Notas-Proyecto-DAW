<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php
$notas = $notas ?? [];
?>
<div class="container mt-4">
    <h2>Mis Notas</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Curso</th>
                <th>Actividad</th>
                <th>Nota</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($notas as $nota): ?>
                <tr>
                    <td><?= htmlspecialchars($nota['nombre_curso']) ?></td>
                    <td><?= htmlspecialchars($nota['actividad']) ?></td>
                    <td><?= htmlspecialchars($nota['nota']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>