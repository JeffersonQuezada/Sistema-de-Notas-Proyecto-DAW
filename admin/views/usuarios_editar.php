<?php
// filepath: admin/views/usuario_editar.php
include __DIR__ . '/../includes/header.php';
?>

<div class="container mt-4">
    <h2>Editar Usuario</h2>
    <form method="POST" action="../index.php?accion=usuarios_editar">
        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Correo</label>
            <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="rol" class="form-control">
                <option value="admin" <?= $usuario['rol']=='admin'?'selected':'' ?>>Admin</option>
                <option value="docente" <?= $usuario['rol']=='docente'?'selected':'' ?>>Docente</option>
                <option value="estudiante" <?= $usuario['rol']=='estudiante'?'selected':'' ?>>Estudiante</option>
            </select>
        </div>
        <button class="btn btn-primary">Actualizar</button>
        <a href="../index.php?accion=usuarios" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>