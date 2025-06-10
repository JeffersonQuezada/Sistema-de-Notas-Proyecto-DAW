<?php
// filepath: admin/views/reportes.php
include __DIR__ . '/../../includes/header.php';
$estadisticas = $estadisticas ?? ['docentes'=>0, 'estudiantes'=>0, 'cursos'=>0, 'calificaciones'=>0];
?>

<div class="container mt-4">
    <h2>Reportes y Estadísticas</h2>
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
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Docentes</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['docentes'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Estudiantes</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['estudiantes'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Cursos</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['cursos'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Calificaciones</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $estadisticas['calificaciones'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <a href="../index.php?accion=descargarUsuariosExcel" class="btn btn-success mb-3">
        <i class="fas fa-file-excel"></i> Descargar Usuarios (Excel)
    </a>
    <canvas id="graficoUsuarios" width="400" height="150"></canvas>
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
</div>

<?php
include __DIR__ . '/../../includes/footer.php';
?>