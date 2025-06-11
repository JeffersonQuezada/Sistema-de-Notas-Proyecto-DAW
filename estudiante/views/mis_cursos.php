<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container mt-4">
    <h2>Mis Cursos Inscritos</h2>
    <?php if (!empty($cursos)): ?>
        <div class="row">
            <?php foreach ($cursos as $curso): ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5><?= htmlspecialchars($curso['nombre_curso']) ?></h5>
                            <p><?= htmlspecialchars($curso['descripcion']) ?></p>
                            <p><strong>Profesor:</strong> <?= htmlspecialchars($curso['profesor_nombre']) ?></p>
                            <p><strong>Grupo:</strong> <?= htmlspecialchars($curso['grupo']) ?></p>
                            <a href="index.php?accion=actividades&id=<?= $curso['id_curso'] ?>" class="btn btn-info btn-sm mt-2">Ver actividades</a>
                            <a href="index.php?accion=desinscribir_curso&id=<?= $curso['id_curso'] ?>" class="btn btn-danger btn-sm mt-2">Desinscribirse</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No estás inscrito en ningún curso.</div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>