<?php include __DIR__ . '/../../includes/header.php'; ?>
<div class="container mt-4">
    <h3>Inscribirse en el curso: <?= htmlspecialchars($curso['nombre_curso']) ?></h3>
    <form method="POST" action="index.php?accion=inscribir_curso&id=<?= $curso['id_curso'] ?>">
        <div class="mb-3">
            <label for="contrasena">Contraseña del curso</label>
            <input type="password" name="contrasena" id="contrasena" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Inscribirse</button>
    </form>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger mt-2"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>