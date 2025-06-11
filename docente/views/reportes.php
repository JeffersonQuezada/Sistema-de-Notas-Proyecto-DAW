<?php
// ============================================
// ARCHIVO: docente/views/reportes.php
// ============================================
?>
<?php 
$pageTitle = "Reportes Académicos";
include __DIR__ . '/../../includes/header.php'; 
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-chart-bar me-2"></i>Reportes Académicos</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportarReporte('pdf')">
                    <i class="fas fa-file-pdf me-1"></i>Exportar PDF
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportarReporte('excel')">
                    <i class="fas fa-file-excel me-1"></i>Exportar Excel
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Estudiantes en Riesgo -->
        <div class="col-xl-6 col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Estudiantes en Riesgo
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($estudiantesEnRiesgo) && !empty($estudiantesEnRiesgo)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Estudiante</th>
                                        <th class="text-center">Entregas Pendientes</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($estudiantesEnRiesgo as $estudiante): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($estudiante['nombre']); ?>&background=dc3545&color=fff" 
                                                     class="rounded-circle me-2" width="30" height="30">
                                                <?php echo htmlspecialchars($estudiante['nombre']); ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger"><?php echo $estudiante['entregas_pendientes']; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    onclick="verDetalleEstudiante(<?php echo $estudiante['id_usuario'] ?? 0; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                            <h5>¡Excelente!</h5>
                            <p class="text-muted">No hay estudiantes en riesgo en este momento.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Estadísticas de Cumplimiento -->
        <div class="col-xl-6 col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie text-info me-2"></i>
                        Estadísticas de Cumplimiento
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($estadisticasCumplimiento) && !empty($estadisticasCumplimiento)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Actividad</th>
                                        <th class="text-center">Entregas</th>
                                        <th class="text-center">Estudiantes</th>
                                        <th class="text-center">% Cumplimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($estadisticasCumplimiento as $stats): ?>
                                    <?php 
                                        $porcentaje = $stats['estudiantes_entregaron'] > 0 ? 
                                                     round(($stats['entregas_recibidas'] / $stats['estudiantes_entregaron']) * 100, 1) : 0;
                                        $badgeClass = $porcentaje >= 80 ? 'bg-success' : ($porcentaje >= 60 ? 'bg-warning' : 'bg-danger');
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($stats['nombre']); ?></td>
                                        <td class="text-center"><?php echo $stats['entregas_recibidas']; ?></td>
                                        <td class="text-center"><?php echo $stats['estudiantes_entregaron']; ?></td>
                                        <td class="text-center">
                                            <span class="badge <?php echo $badgeClass; ?>"><?php echo $porcentaje; ?>%</span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-chart-bar text-muted fa-3x mb-3"></i>
                            <h5>Sin datos</h5>
                            <p class="text-muted">No hay estadísticas disponibles en este momento.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos adicionales -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Tendencia de Entregas
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="graficoTendencias" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportarReporte(tipo) {
    const url = `index.php?accion=generar_reporte&tipo=${tipo}`;
    window.open(url, '_blank');
}

function verDetalleEstudiante(idEstudiante) {
    const url = `index.php?accion=dashboard&id_estudiante=${idEstudiante}`;
    window.location.href = url;
}

// Inicializar gráfico (requiere Chart.js)
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('graficoTendencias');
    if (ctx) {
        // Aquí puedes agregar un gráfico con Chart.js
        ctx.getContext('2d').fillText('Gráfico en desarrollo...', 50, 50);
    }
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

