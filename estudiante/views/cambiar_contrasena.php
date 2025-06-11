<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container mt-4">
    <h2>Cambiar Contraseña</h2>
    <form method="POST" action="index.php?accion=guardar_cambio_contrasena">
        <div class="mb-3">
            <label for="actual">Contraseña actual</label>
            <input type="password" name="actual" id="actual" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="nueva">Nueva contraseña</label>
            <input type="password" name="nueva" id="nueva" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
    <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-danger mt-2">Contraseña actual incorrecta.</div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>