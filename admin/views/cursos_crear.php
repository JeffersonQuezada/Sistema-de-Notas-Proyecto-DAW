<?php
// filepath: admin/views/cursos_crear.php
include __DIR__ . '/../../includes/header.php';
// $docentes debe ser un array de usuarios con rol 'docente', pasado por el controlador
?>

<div class="container mt-4">
    <h2>Crear Nuevo Curso</h2>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <form method="POST" action="../index.php?accion=cursos_crear">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Curso</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
        </div>
        <div class="mb-3">
            <label for="id_docente" class="form-label">Docente</label>
            <select class="form-control" id="id_docente" name="id_docente" required>
                <option value="">Seleccione un docente</option>
                <?php if (!empty($docentes)): ?>
                    <?php foreach ($docentes as $docente): ?>
                        <option value="<?= $docente['id_usuario'] ?>">
                            <?= htmlspecialchars($docente['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña del Curso (opcional)</label>
            <input type="text" class="form-control" id="contrasena" name="contrasena">
        </div>
        <div class="mb-3">
            <label for="capacidad" class="form-label">Capacidad</label>
            <input type="number" class="form-control" id="capacidad" name="capacidad" min="1" required>
        </div>
        <button type="submit" class="btn btn-success">Crear Curso</button>
        <a href="../index.php?accion=cursos" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>