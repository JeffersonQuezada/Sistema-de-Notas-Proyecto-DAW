<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container mt-4">
    <h2>Mi Perfil</h2>
    <div class="card">
        <div class="card-body">
            <p><strong>Nombre:</strong> <?= htmlspecialchars($perfil['nombre']) ?></p>
            <p><strong>Correo:</strong> <?= htmlspecialchars($perfil['correo']) ?></p>
            <!-- Puedes agregar más campos si lo deseas -->
            <a href="index.php?accion=cambiar_contrasena" class="btn btn-warning">Cambiar contraseña</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>