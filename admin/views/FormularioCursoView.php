<?php
class FormularioCursoView {
    public function mostrar($curso, $titulo, $docentes) {
        $pageTitle = $titulo;
        include __DIR__ . '/../../includes/header.php';
        
        $curso = $curso ?? [
            'id_curso' => '',
            'nombre_curso' => '',
            'descripcion' => '',
            'id_docente' => '',
            'contrasena' => '',
            'capacidad' => 30,
            'grupo' => ''
        ];
        ?>
        
        <div class="container mt-4">
            <h2><?= $titulo ?></h2>
            
            <form method="POST" action="index.php?accion=<?= $curso['id_curso'] ? 'editar_curso' : 'crear_curso' ?><?= $curso['id_curso'] ? '&id='.$curso['id_curso'] : '' ?>">
                <?php if ($curso['id_curso']): ?>
                    <input type="hidden" name="id_curso" value="<?= $curso['id_curso'] ?>">
                <?php endif; ?>
                
                <div class="mb-3">
                    <label for="nombre_curso" class="form-label">Nombre del Curso</label>
                    <input type="text" class="form-control" id="nombre_curso" name="nombre_curso" 
                           value="<?= htmlspecialchars($curso['nombre_curso']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= htmlspecialchars($curso['descripcion']) ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="id_docente" class="form-label">Docente</label>
                    <select class="form-select" id="id_docente" name="id_docente" required>
                        <option value="">Seleccione un docente</option>
                        <?php foreach ($docentes as $docente): ?>
                        <option value="<?= $docente['id_usuario'] ?>" <?= $curso['id_docente'] == $docente['id_usuario'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($docente['nombre']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña del Curso</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" 
                           <?= !$curso['id_curso'] ? 'required' : '' ?>>
                    <?php if ($curso['id_curso']): ?>
                        <small class="text-muted">Dejar en blanco para mantener la contraseña actual</small>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label for="capacidad" class="form-label">Capacidad</label>
                    <input type="number" class="form-control" id="capacidad" name="capacidad" 
                           value="<?= $curso['capacidad'] ?>" min="1" required>
                </div>
                
                <div class="mb-3">
                    <label for="grupo" class="form-label">Grupo (opcional)</label>
                    <select class="form-select" id="grupo" name="grupo">
                        <option value="">Sin grupo</option>
                        <option value="A" <?= $curso['grupo'] == 'A' ? 'selected' : '' ?>>Grupo A</option>
                        <option value="B" <?= $curso['grupo'] == 'B' ? 'selected' : '' ?>>Grupo B</option>
                    </select>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="index.php?accion=cursos_admin" class="btn btn-secondary me-md-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
        
        <?php
        include __DIR__ . '/../../includes/footer.php';
    }
}