<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/funciones.php';

if ($_SESSION['rol'] != 'admin') {
    header("Location: " . url('login.php'));
    exit();
}

// Obtener estadísticas para reportes
try {
    $stmt = $pdo->prepare("SELECT 
        (SELECT COUNT(*) FROM usuarios WHERE rol = 'docente') as docentes,
        (SELECT COUNT(*) FROM usuarios WHERE rol = 'estudiante') as estudiantes,
        (SELECT COUNT(*) FROM cursos) as cursos,
        (SELECT COUNT(*) FROM notas) as calificaciones");
    $stmt->execute();
    $estadisticas = $stmt->fetch();
} catch (PDOException $e) {
    die("Error al obtener estadísticas: " . $e->getMessage());
}

$pageTitle = "Reportes del Sistema";
include __DIR__ . '/../../includes/header.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Reportes del Sistema</h1>
    
    <div class="row">
        <!-- Tarjetas de estadísticas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Docentes Registrados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['docentes'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Repite para otras estadísticas -->
        
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Generar Reportes</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="<?= url('admin/reportes/generar.php?tipo=usuarios') ?>" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Reporte de Usuarios
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="<?= url('admin/reportes/generar.php?tipo=cursos') ?>" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Reporte de Cursos
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="<?= url('admin/reportes/generar.php?tipo=notas') ?>" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Reporte de Calificaciones
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>