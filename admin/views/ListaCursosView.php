<?php
class ListaCursosView {
    public function mostrar($cursos) {
        $pageTitle = "Gestión de Cursos";
        include __DIR__ . '/../../includes/header.php';
        ?>
        
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Gestión de Cursos</h2>
                <a href="index.php?accion=crear_curso" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Curso
                </a>
            </div>
            
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-success"><?= $_SESSION['mensaje'] ?></div>
                <?php unset($_SESSION['mensaje']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Docente</th>
                            <th>Capacidad</th>
                            <th>Grupo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cursos as $curso): ?>
                        <tr>
                            <td><?= $curso['id_curso'] ?></td>
                            <td><?= htmlspecialchars($curso['nombre_curso']) ?></td>
                            <td><?= htmlspecialchars($curso['nombre_docente']) ?></td>
                            <td><?= $curso['capacidad'] ?></td>
                            <td><?= $curso['grupo'] ?? '-' ?></td>
                            <td>
                                <a href="index.php?accion=editar_curso&id=<?= $curso['id_curso'] ?>" 
                                   class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?accion=eliminar_curso&id=<?= $curso['id_curso'] ?>" 
                                   class="btn btn-sm btn-danger" title="Eliminar"
                                   onclick="return confirm('¿Estás seguro de eliminar este curso?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php
        include __DIR__ . '/../../includes/footer.php';
    }
}