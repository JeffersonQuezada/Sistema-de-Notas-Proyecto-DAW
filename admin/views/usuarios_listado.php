<?php
// filepath: admin/views/usuarios_listado.php
include __DIR__ . '/../../includes/header.php';
$usuarios = $usuarios ?? [];
?>
<div class="container mt-4">
    <h2>Listado de Usuarios</h2>
    <a href="../index.php?accion=usuarios_crear" class="btn btn-primary mb-3">
        <i class="fas fa-user-plus"></i> Nuevo Usuario
    </a>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($usuarios) > 0): ?>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['id_usuario'] ?></td>
                        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                        <td><?= htmlspecialchars($usuario['correo']) ?></td>
                        <td><?= ucfirst($usuario['rol']) ?></td>
                        <td><?= htmlspecialchars($usuario['fecha_registro']) ?></td>
                        <td>
                            <a href="../index.php?accion=usuarios_editar&id=<?= $usuario['id_usuario'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">No hay usuarios registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>