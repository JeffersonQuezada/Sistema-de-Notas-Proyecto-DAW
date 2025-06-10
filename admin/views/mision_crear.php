<?php
// filepath: admin/views/misiones_crear.php
include '../../includes/header.php';
// Supón que $grupos contiene los grupos disponibles
?>

<div class="container mt-4">
    <h2>Crear Nueva Misión</h2>
    <form method="POST" action="../index.php?accion=misiones_crear">
        <div class="mb-3">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Recompensa</label>
            <input type="text" name="recompensa" class="form-control">
        </div>
        <div class="mb-3">
            <label>Grupo</label>
            <select name="id_grupo" class="form-control" required>
                <?php foreach ($grupos as $grupo): ?>
                    <option value="<?= $grupo['id_grupo'] ?>"><?= htmlspecialchars($grupo['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="btn btn-success">Crear Misión</button>
        <a href="../index.php?accion=misiones" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>