<?php
class FormularioCursoView {
    public function mostrar($curso, $titulo, $docentes) {
        $pageTitle = $titulo;
        include __DIR__ . '/../../includes/header.php';
        ?>
        <div class="container mt-4">
            <h2><?= htmlspecialchars($titulo) ?></h2>
            <form method="POST" action="index.php?accion=<?= $curso ? 'actualizar_curso&id=' . $curso['id_curso'] : 'crear_curso' ?>">
                <div class="mb-3">
                    <label for="nombre_curso" class="form-label">Nombre del Curso</label>
                    <input type="text" class="form-control" id="nombre_curso" name="nombre_curso"
                        value="<?= htmlspecialchars($curso['nombre_curso'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= htmlspecialchars($curso['descripcion'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="id_docente" class="form-label">Docente</label>
                    <select class="form-select" id="id_docente" name="id_docente" required>
                        <option value="">Seleccione un docente</option>
                        <?php foreach ($docentes as $docente): ?>
                            <option value="<?= $docente['id_usuario'] ?>"
                                <?= isset($curso['id_docente']) && $curso['id_docente'] == $docente['id_usuario'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($docente['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="capacidad" class="form-label">Capacidad</label>
                    <input type="number" class="form-control" id="capacidad" name="capacidad"
                        value="<?= htmlspecialchars($curso['capacidad'] ?? '') ?>" min="1" required>
                </div>
                <div class="mb-3">
                    <label for="grupo" class="form-label">Grupo</label>
                    <input type="text" class="form-control" id="grupo" name="grupo"
                        value="<?= htmlspecialchars($curso['grupo'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña del curso</label>
                    <input type="password" name="contrasena" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary"><?= $curso ? 'Actualizar' : 'Crear' ?></button>
                <a href="index.php?accion=cursos_admin" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
        <?php
        include __DIR__ . '/../../includes/footer.php';
    }
}