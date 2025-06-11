<?php
class ReportesView {
    public function mostrar() {
        $pageTitle = "Reportes";
        include __DIR__ . '/../../includes/header.php';
        ?>
        
        <div class="container mt-4">
            <h2>Reportes del Sistema</h2>
            
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Reporte de Usuarios</h5>
                            <p class="card-text">Estadísticas y listado de usuarios registrados</p>
                            <a href="index.php?accion=reporte_usuarios" class="btn btn-primary">Ver Reporte</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Reporte de Cursos</h5>
                            <p class="card-text">Información sobre cursos y asignación de docentes</p>
                            <a href="index.php?accion=reporte_cursos" class="btn btn-primary">Ver Reporte</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Reporte de Actividades</h5>
                            <p class="card-text">Actividades programadas y próximas a vencer</p>
                            <a href="index.php?accion=reporte_actividades" class="btn btn-primary">Ver Reporte</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
        include __DIR__ . '/../../includes/footer.php';
    }
    
    public function mostrarReporteUsuarios($datos) {
        $pageTitle = "Reporte de Usuarios";
        include __DIR__ . '/../../includes/header.php';
        ?>
        
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Reporte de Usuarios</h2>
                <a href="index.php?accion=reportes" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <h4>Usuarios por Rol</h4>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Rol</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['usuariosPorRol'] as $rol): ?>
                            <tr>
                                <td><?= ucfirst($rol['rol']) ?></td>
                                <td><?= $rol['total'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h4>Últimos Usuarios Registrados</h4>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Fecha Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['ultimosUsuarios'] as $usuario): ?>
                            <tr>
                                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                                <td><?= htmlspecialchars($usuario['correo']) ?></td>
                                <td><?= date('d/m/Y', strtotime($usuario['fecha_registro'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <?php
        include __DIR__ . '/../../includes/footer.php';
    }
    
    public function mostrarReporteCursos($datos) {
        $pageTitle = "Reporte de Cursos";
        include __DIR__ . '/../../includes/header.php';
        ?>
        
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Reporte de Cursos</h2>
                <a href="index.php?accion=reportes" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <h4>Cursos por Docente</h4>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Docente</th>
                                <th>Cursos Asignados</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['cursosPorDocente'] as $docente): ?>
                            <tr>
                                <td><?= htmlspecialchars($docente['docente']) ?></td>
                                <td><?= $docente['total'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h4>Cursos con más Estudiantes</h4>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Curso</th>
                                <th>Estudiantes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['cursosMasPopulares'] as $curso): ?>
                            <tr>
                                <td><?= htmlspecialchars($curso['nombre_curso']) ?></td>
                                <td><?= $curso['total_estudiantes'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <?php
        include __DIR__ . '/../../includes/footer.php';
    }
    
    public function mostrarReporteActividades($datos) {
        $pageTitle = "Reporte de Actividades";
        include __DIR__ . '/../../includes/header.php';
        ?>
        
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Reporte de Actividades</h2>
                <a href="index.php?accion=reportes" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <h4>Actividades por Tipo</h4>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Tipo</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['actividadesPorTipo'] as $tipo): ?>
                            <tr>
                                <td><?= $tipo['tipo'] ?></td>
                                <td><?= $tipo['total'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h4>Actividades Próximas</h4>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Actividad</th>
                                <th>Curso</th>
                                <th>Fecha Límite</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['actividadesProximas'] as $actividad): ?>
                            <tr>
                                <td><?= htmlspecialchars($actividad['nombre']) ?></td>
                                <td><?= htmlspecialchars($actividad['nombre_curso']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($actividad['fecha_limite'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <?php
        include __DIR__ . '/../../includes/footer.php';
    }
}