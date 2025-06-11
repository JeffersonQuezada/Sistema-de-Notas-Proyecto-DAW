<?php
class DashboardView {
    public function mostrar($estadisticas) {
        $pageTitle = "Dashboard Administrador";
        include __DIR__ . '/../../includes/header.php';
        ?>
        
        <div class="container mt-4">
            <h2>Dashboard Administrador</h2>
            
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
        </div>
        
        <?php
        include __DIR__ . '/../../includes/footer.php';
    }
}