?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php
$cursos = $cursos ?? [];
?>
<div class="container mt-4">
    <h2>Cursos Disponibles</h2>
    <div class="row">
        <?php foreach ($cursos as $curso): ?>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($curso['nombre_curso']) ?></h5>
                        <p><?= htmlspecialchars($curso['descripcion']) ?></p>
                        <p><strong>Profesor:</strong> <?= htmlspecialchars($curso['profesor_nombre']) ?></p>
                        <p><strong>Capacidad:</strong> <?= $curso['capacidad'] ?></p>
                        <p><strong>Grupo:</strong> <?= $curso['grupo'] ?></p>
                        <?php if ($curso['ya_inscrito']): ?>
                            <span class="badge bg-success">Inscrito</span>
                            <a href="index.php?accion=desinscribir_curso&id=<?= $curso['id_curso'] ?>" class="btn btn-danger btn-sm mt-2">Desinscribirse</a>
                            <a href="index.php?accion=actividades&id=<?= $curso['id_curso'] ?>" class="btn btn-info btn-sm mt-2">Ver actividades</a>
                        <?php else: ?>
                            <a href="index.php?accion=inscribir_curso&id=<?= $curso['id_curso'] ?>" class="btn btn-primary btn-sm mt-2">Inscribirse</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
