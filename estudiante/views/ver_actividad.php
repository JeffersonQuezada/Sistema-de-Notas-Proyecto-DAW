<?php include '../../includes/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><?= htmlspecialchars($actividad['nombre']) ?></h3>
        </div>
        <div class="card-body">
            <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($actividad['descripcion'])) ?></p>
            <p><strong>Tipo:</strong> <?= htmlspecialchars($actividad['tipo']) ?></p>
            <p>
                <strong>Fecha límite:</strong>
                <?= date('d/m/Y H:i', strtotime($actividad['fecha_limite'])) ?>
                <?php
                $fecha_limite = new DateTime($actividad['fecha_limite']);
                $ahora = new DateTime();
                if ($fecha_limite < $ahora) {
                    echo '<span class="badge bg-danger ms-2">Vencida</span>';
                } elseif ($fecha_limite->diff($ahora)->days <= 3) {
                    echo '<span class="badge bg-warning text-dark ms-2">Próxima a vencer</span>';
                }
                ?>
            </p>
            <hr>
            <?php if ($entrega): ?>
                <div class="alert alert-success">
                    <strong>¡Ya entregaste esta actividad!</strong><br>
                    <b>Fecha de entrega:</b> <?= date('d/m/Y H:i', strtotime($entrega['fecha_entrega'])) ?><br>
                    <?php if (!empty($entrega['archivo'])): ?>
                        <b>Archivo:</b> <a href="../../uploads/<?= urlencode($entrega['archivo']) ?>" target="_blank"><?= htmlspecialchars($entrega['archivo']) ?></a><br>
                    <?php endif; ?>
                    <?php if (isset($entrega['calificacion'])): ?>
                        <b>Calificación:</b> <?= htmlspecialchars($entrega['calificacion']) ?><br>
                        <b>Comentario:</b> <?= htmlspecialchars($entrega['comentario']) ?>
                    <?php endif; ?>
                </div>
            <?php elseif ($fecha_limite >= $ahora): ?>
                <form method="POST" action="../controllers/EntregaController.php?accion=entregar" enctype="multipart/form-data" class="mt-3">
                    <input type="hidden" name="id_actividad" value="<?= $actividad['id_actividad'] ?>">
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Subir archivo de entrega</label>
                        <input type="file" name="archivo" id="archivo" class="form-control" required>
                    </div>
                    <button class="btn btn-success">Entregar</button>
                </form>
            <?php else: ?>
                <div class="alert alert-danger">
                    <strong>La fecha límite ha pasado y no puedes entregar esta actividad.</strong>
                </div>
            <?php endif; ?>
            <a href="actividades_listado.php?id_curso=<?= $actividad['id_curso'] ?>" class="btn btn-secondary mt-3">Volver a actividades</a>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>