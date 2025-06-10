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
                        <?php else: ?>
                            <form method="POST" action="../controllers/CursoController.php?accion=inscribir">
                                <input type="hidden" name="id_curso" value="<?= $curso['id_curso'] ?>">
                                <button class="btn btn-primary btn-sm mt-2">Inscribirse</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>