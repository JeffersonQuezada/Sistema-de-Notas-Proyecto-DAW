<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/funciones.php';

// Verificar rol de administrador
if ($_SESSION['rol'] != 'admin') {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

// Obtener lista de cursos
try {
    $stmt = $pdo->prepare("SELECT c.*, u.nombre as docente 
                          FROM cursos c
                          JOIN usuarios u ON c.id_docente = u.id_usuario
                          ORDER BY c.nombre_curso");
    $stmt->execute();
    $cursos = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener cursos: " . $e->getMessage());
}

$pageTitle = "Gestión de Cursos";
include __DIR__ . '/../../includes/header.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Gestión de Cursos</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="<?php echo BASE_URL; ?>admin/cursos/crear.php" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nuevo Curso
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Docente</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cursos as $curso): ?>
                        <tr>
                            <td><?= $curso['id_curso'] ?></td>
                            <td><?= htmlspecialchars($curso['nombre_curso']) ?></td>
                            <td><?= htmlspecialchars($curso['docente']) ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>admin/cursos/editar.php?id=<?= $curso['id_curso'] ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>