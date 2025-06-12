<?php
class MisionesView {
    public function mostrar($misiones) {
        include __DIR__ . '/../../includes/header.php'; ?>
        <div class="container mt-4">
            <h2>Misiones</h2>
            <a href="index.php?accion=crear_mision" class="btn btn-primary mb-3">Nueva Misión</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Título</th><th>Descripción</th><th>Recompensa</th><th>Inicio</th><th>Fin</th><th>Prioridad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($misiones as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['titulo']) ?></td>
                        <td><?= htmlspecialchars($m['descripcion']) ?></td>
                        <td><?= htmlspecialchars($m['recompensa']) ?></td>
                        <td><?= htmlspecialchars($m['fecha_inicio']) ?></td>
                        <td><?= htmlspecialchars($m['fecha_fin']) ?></td>
                        <td><?= htmlspecialchars($m['prioridad']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php include __DIR__ . '/../../includes/footer.php';
    }

    public function mostrarFormularioCreacion($usuarios, $alumnos, $cursos, $profesores, $estudiantes) {
        include __DIR__ . '/../../includes/header.php'; ?>
        <div class="container mt-4">
            <h2>Crear Nueva Misión</h2>
            <form method="POST" action="index.php?accion=guardar_mision">
                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Recompensa</label>
                    <input type="text" name="recompensa" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Fecha de inicio</label>
                    <input type="datetime-local" name="fecha_inicio" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fecha de fin</label>
                    <input type="datetime-local" name="fecha_fin" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Prioridad</label>
                    <input type="number" name="prioridad" class="form-control" value="1" min="1">
                </div>
                <div class="mb-3">
                    <label>Cursos:</label>
                    <select name="cursos[]" class="form-select" multiple>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= $curso['id_curso'] ?>"><?= htmlspecialchars($curso['nombre_curso']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Profesores:</label>
                    <select name="profesores[]" class="form-select" multiple>
                        <?php foreach ($profesores as $p): ?>
                            <option value="<?= $p['id_usuario'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Estudiantes:</label>
                    <select name="estudiantes[]" class="form-select" multiple>
                        <?php foreach ($estudiantes as $e): ?>
                            <option value="<?= $e['id_usuario'] ?>"><?= htmlspecialchars($e['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Guardar Misión</button>
                <a href="index.php?accion=misiones" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
        <?php include __DIR__ . '/../../includes/footer.php';
    }
}