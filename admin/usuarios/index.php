<?php
require_once '../../includes/auth.php';
require_once '../../includes/funciones.php';

if ($_SESSION['rol'] != 'admin') {
    header("Location: ../../login.php");
    exit();
}

$rol = isset($_GET['rol']) ? $_GET['rol'] : 'all';

// Construir consulta según filtro
$sql = "SELECT * FROM usuarios";
$params = [];

if ($rol != 'all') {
    $sql .= " WHERE rol = ?";
    $params[] = $rol;
}

$sql .= " ORDER BY fecha_registro DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$usuarios = $stmt->fetchAll();
?>

<?php include '../../includes/header.php'; ?>

<div class="container mt-4">
    <h2>Gestión de Usuarios</h2>
    
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="crear.php" class="btn btn-primary">Crear Nuevo Usuario</a>
        </div>
        <div class="col-md-6 text-right">
            <form method="get" class="form-inline">
                <select name="rol" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="all" <?php echo $rol == 'all' ? 'selected' : ''; ?>>Todos</option>
                    <option value="admin" <?php echo $rol == 'admin' ? 'selected' : ''; ?>>Administradores</option>
                    <option value="docente" <?php echo $rol == 'docente' ? 'selected' : ''; ?>>Docentes</option>
                    <option value="estudiante" <?php echo $rol == 'estudiante' ? 'selected' : ''; ?>>Estudiantes</option>
                </select>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
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
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id_usuario']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                        <td><?php echo ucfirst($usuario['rol']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="eliminar.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>