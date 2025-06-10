<?php
// filepath: admin/views/cursos_listado.php
include __DIR__ . '/../../includes/header.php';
$cursos = $cursos ?? [];
?>
<div class="container mt-4">
    <h2>Listado de Cursos</h2>
    <a href="../index.php?accion=cursos_crear" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Nuevo Curso
    </a>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Docente</th>
                <th>Capacidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($cursos) > 0): ?>
                <?php foreach ($cursos as $curso): ?>
                    <tr>
                        <td><?= $curso['id_curso'] ?></td>
                        <td><?= htmlspecialchars($curso['nombre_curso']) ?></td>
                        <td><?= htmlspecialchars($curso['descripcion']) ?></td>
                        <td><?= htmlspecialchars($curso['docente_nombre']) ?></td>
                        <td><?= htmlspecialchars($curso['capacidad']) ?></td>
                        <td>
                            <!-- Acciones aquí -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">No hay cursos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>