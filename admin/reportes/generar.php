<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/funciones.php';

if ($_SESSION['rol'] != 'admin') {
    header("Location: " . url('login.php'));
    exit();
}

$tipo = $_GET['tipo'] ?? '';
$pageTitle = "Generar Reporte";
include __DIR__ . '/../../includes/header.php';

// Lógica para cada tipo de reporte
switch ($tipo) {
    case 'usuarios':
        $titulo = "Reporte de Usuarios";
        // Consulta para usuarios
        break;
    case 'cursos':
        $titulo = "Reporte de Cursos";
        // Consulta para cursos
        break;
    default:
        $titulo = "Reporte no válido";
        break;
}
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titulo ?></h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="exportarPdf">
            <i class="fas fa-download fa-sm text-white-50"></i> Exportar PDF
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (in_array($tipo, ['usuarios', 'cursos', 'notas'])): ?>
                <!-- Tabla de resultados -->
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <?php if ($tipo == 'usuarios'): ?>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Rol</th>
                                <?php elseif ($tipo == 'cursos'): ?>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Docente</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Datos dinámicos del reporte -->
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">Tipo de reporte no válido</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>