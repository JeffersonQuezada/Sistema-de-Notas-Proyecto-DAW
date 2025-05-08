<?php
// Incluir archivo de autenticación que también carga la conexión PDO
require_once __DIR__ . '/../includes/auth.php';

// Verificar que el usuario es admin
if ($_SESSION['rol'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Incluir funciones adicionales
require_once __DIR__ . '/../includes/funciones.php';

// Ahora $pdo debería estar disponible desde auth.php que incluyó conexion.php

// Obtener estadísticas
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'docente'");
    $stmt->execute();
    $docentes = $stmt->fetch()['total'];

    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'estudiante'");
    $stmt->execute();
    $estudiantes = $stmt->fetch()['total'];

    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM cursos");
    $stmt->execute();
    $cursos = $stmt->fetch()['total'];

    // Últimos cursos creados
    $stmt = $pdo->prepare("SELECT c.*, u.nombre as docente FROM cursos c 
                          JOIN usuarios u ON c.id_docente = u.id_usuario 
                          ORDER BY c.id_curso DESC LIMIT 5");
    $stmt->execute();
    $ultimos_cursos = $stmt->fetchAll();

} catch(PDOException $e) {
    // Manejar el error adecuadamente
    die("Error al obtener datos: " . $e->getMessage());
}
?>

<?php 
$pageTitle = "Panel de Administración";
include __DIR__ . '/../includes/header.php'; 
?>

<div class="container-fluid">
    <!-- Tu contenido del dashboard aquí -->
    <h1 class="h3 mb-4 text-gray-800">Panel de Administración</h1>
    
    <div class="row">
        <!-- Tarjeta de Docentes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Docentes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $docentes ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tarjeta de Estudiantes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Estudiantes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estudiantes ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tarjeta de Cursos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Cursos Activos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $cursos ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabla de últimos cursos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Últimos Cursos Creados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Docente</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimos_cursos as $curso): ?>
                        <tr>
                            <td><?= $curso['id_curso'] ?></td>
                            <td><?= htmlspecialchars($curso['nombre_curso']) ?></td>
                            <td><?= htmlspecialchars($curso['docente']) ?></td>
                            <td>
                                <a href="cursos/ver.php?id=<?= $curso['id_curso'] ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Ver
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

<?php include __DIR__ . '/../includes/footer.php'; ?>