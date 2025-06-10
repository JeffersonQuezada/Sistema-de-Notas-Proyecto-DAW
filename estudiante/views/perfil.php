<?php include __DIR__ . '/../../includes/header.php'; ?>
<div class="container mt-4">
    <h2>Mi Perfil</h2>
    <form method="POST" action="../controllers/PerfilController.php?accion=actualizar">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($perfil['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Correo</label>
            <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($perfil['correo']) ?>" required>
        </div>
        <button class="btn btn-primary">Actualizar</button>
    </form>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>