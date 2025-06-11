<?php
class FormularioUsuarioView {
    public function mostrar($usuario, $titulo, $docentes = null) {
        $pageTitle = $titulo;
        include __DIR__ . '/../../includes/header.php';
        
        $usuario = $usuario ?? [
            'id_usuario' => '',
            'nombre' => '',
            'correo' => '',
            'rol' => 'estudiante',
            'contrasena' => ''
        ];
        ?>
        
        <div class="container mt-4">
            <h2><?= $titulo ?></h2>
            
            <form method="POST" action="index.php?accion=<?= $usuario['id_usuario'] ? 'editar_usuario' : 'crear_usuario' ?><?= $usuario['id_usuario'] ? '&id='.$usuario['id_usuario'] : '' ?>">
                <?php if ($usuario['id_usuario']): ?>
                    <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                <?php endif; ?>
                
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" 
                           value="<?= htmlspecialchars($usuario['correo']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select class="form-select" id="rol" name="rol" required>
                        <option value="estudiante" <?= $usuario['rol'] == 'estudiante' ? 'selected' : '' ?>>Estudiante</option>
                        <option value="docente" <?= $usuario['rol'] == 'docente' ? 'selected' : '' ?>>Docente</option>
                        <option value="admin" <?= $usuario['rol'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" 
                           <?= !$usuario['id_usuario'] ? 'required' : '' ?>>
                    <?php if ($usuario['id_usuario']): ?>
                        <small class="text-muted">Dejar en blanco para mantener la contraseña actual</small>
                    <?php endif; ?>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="index.php?accion=usuarios" class="btn btn-secondary me-md-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
        
        <?php
             include __DIR__ . '/../../includes/footer.php';
    }
}