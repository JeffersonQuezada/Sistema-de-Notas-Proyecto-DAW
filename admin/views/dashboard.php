<?php
// filepath: admin/views/dashboard.php
include __DIR__ . '/../../includes/header.php';

// Inicializar variables para evitar warnings si la vista se abre directamente
$estadisticas = $estadisticas ?? ['docentes'=>0, 'estudiantes'=>0, 'cursos'=>0, 'calificaciones'=>0];
$ultimos_cursos = $ultimos_cursos ?? [];
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Panel de Administración</h1>
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= htmlspecialchars($_GET['success']) === '1' ? '¡Operación realizada con éxito!' : htmlspecialchars($_GET['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($_GET['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Docentes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['docentes'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Estudiantes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['estudiantes'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Cursos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['cursos'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Calificaciones</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['calificaciones'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
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
                <table class="table table-bordered">
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
                                <a href="../index.php?accion=cursos_editar&id=<?= $curso['id_curso'] ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Gráfico de docentes y estudiantes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Estadísticas de Usuarios</h6>
        </div>
        <div class="card-body">
            <canvas id="graficoUsuarios" width="400" height="150"></canvas>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('graficoUsuarios').getContext('2d');
const chart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Docentes', 'Estudiantes'],
        datasets: [{
            data: [<?= $estadisticas['docentes'] ?>, <?= $estadisticas['estudiantes'] ?>],
            backgroundColor: ['#4e73df', '#1cc88a']
        }]
    }
});
</script>
<?php
include __DIR__ . '/../../includes/footer.php';