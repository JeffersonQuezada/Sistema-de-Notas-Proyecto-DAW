<?php
class DashboardView {
    public function mostrar($estadisticas, $cursos) {
        $pageTitle = "Dashboard Administrador";
        include __DIR__ . '/../../includes/header.php';
        ?>
        
        <div class="container mt-4">
            <h2>Dashboard Administrador</h2>
            
            <form method="GET" action="index.php" class="mb-3">
                <input type="hidden" name="accion" value="dashboard">
                <div class="input-group">
                    <select name="curso" class="form-select">
                        <option value="">-- Todos los cursos --</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= $curso['id_curso'] ?>" <?= (isset($_GET['curso']) && $_GET['curso'] == $curso['id_curso']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($curso['nombre_curso']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-primary" type="submit">Filtrar</button>
                </div>
            </form>
            
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Usuarios</h5>
                            <p class="card-text display-4"><?= $estadisticas['total_usuarios'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Cursos</h5>
                            <p class="card-text display-4"><?= $estadisticas['total_cursos'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Actividades</h5>
                            <p class="card-text display-4"><?= $estadisticas['total_actividades'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Entregas</h5>
                            <p class="card-text display-4"><?= $estadisticas['total_entregas'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Acciones Rápidas</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="index.php?accion=crear_usuario" class="btn btn-primary">Nuevo Usuario</a>
                                <a href="index.php?accion=crear_curso" class="btn btn-success">Nuevo Curso</a>
                                <a href="index.php?accion=reportes" class="btn btn-info">Ver Reportes</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Docentes con mayor cumplimiento de tareas -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Docentes con mayor cumplimiento de tareas</h5>
                </div>
                <div class="card-body">
                    <canvas id="graficoDocentesCumplimiento"></canvas>
                </div>
            </div>

            <!-- Mejores alumnos por curso -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Alumnos con mejores notas por curso</h5>
                </div>
                <div class="card-body">
                    <div id="graficasMejoresAlumnos">
                        <?php foreach ($estadisticas['mejoresAlumnos'] as $curso => $alumnos): ?>
                            <h6><?= htmlspecialchars($curso) ?></h6>
                            <canvas id="graficoAlumnos_<?= md5($curso) ?>" height="100"></canvas>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
            // Docentes con mayor cumplimiento
            const ctxDocentes = document.getElementById('graficoDocentesCumplimiento');
            new Chart(ctxDocentes, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_column($estadisticas['docentesCumplimiento'], 'docente')) ?>,
                    datasets: [{
                        label: '% Cumplimiento',
                        data: <?= json_encode(array_column($estadisticas['docentesCumplimiento'], 'porcentaje_cumplimiento')) ?>,
                        backgroundColor: 'rgba(37,99,235,0.7)'
                    }]
                },
                options: { responsive: true }
            });

            // Mejores alumnos por curso
            <?php foreach ($estadisticas['mejoresAlumnos'] as $curso => $alumnos): ?>
            new Chart(document.getElementById('graficoAlumnos_<?= md5($curso) ?>'), {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_column($alumnos, 'alumno')) ?>,
                    datasets: [{
                        label: 'Promedio',
                        data: <?= json_encode(array_column($alumnos, 'promedio')) ?>,
                        backgroundColor: 'rgba(16,185,129,0.7)'
                    }]
                },
                options: { responsive: true }
            });
            <?php endforeach; ?>
            </script>
        </div>
        
        <?php
        include __DIR__ . '/../../includes/footer.php';
    }
}
?>
