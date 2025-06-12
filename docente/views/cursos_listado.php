<?php
// ============================================
// ARCHIVO: docente/views/cursos_listado.php
// ============================================
?>
<?php 
$pageTitle = "Mis Cursos";
include __DIR__ . '/../../includes/header.php'; 
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-book-open me-2"></i>Mis Cursos</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoCurso">
                    <i class="fas fa-plus me-1"></i>Nuevo Curso
                </button>
            </div>
        </div>
    </div>

    <?php if (isset($cursos) && !empty($cursos)): ?>
        <div class="row">
            <?php foreach ($cursos as $curso): ?>
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0"><?php echo htmlspecialchars($curso['nombre_curso']); ?></h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-light" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="index.php?accion=ver_curso&id=<?php echo $curso['id_curso']; ?>">
                                        <i class="fas fa-eye me-2"></i>Ver curso
                                    </a></li>
                                    <li><a class="dropdown-item" href="index.php?accion=actividades&id_curso=<?php echo $curso['id_curso']; ?>">
                                        <i class="fas fa-tasks me-2"></i>Actividades
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="index.php?accion=editar_curso&id=<?php echo $curso['id_curso']; ?>">
                                        <i class="fas fa-edit me-2"></i>Editar
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted">
                            <?php echo htmlspecialchars($curso['descripcion'] ?? 'Sin descripción disponible'); ?>
                        </p>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="text-primary">
                                    <i class="fas fa-users fa-2x"></i>
                                    <p class="mb-0 mt-1 small">
                                        <strong><?php echo $curso['total_estudiantes'] ?? 0; ?></strong><br>
                                        <small class="text-muted">Estudiantes</small>
                                    </p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-success">
                                    <i class="fas fa-tasks fa-2x"></i>
                                    <p class="mb-0 mt-1 small">
                                        <strong><?php echo $curso['total_actividades'] ?? 0; ?></strong><br>
                                        <small class="text-muted">Actividades</small>
                                    </p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-warning">
                                    <i class="fas fa-clock fa-2x"></i>
                                    <p class="mb-0 mt-1 small">
                                        <strong><?php echo $curso['entregas_pendientes'] ?? 0; ?></strong><br>
                                        <small class="text-muted">Pendientes</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-6">
                                <a href="index.php?accion=actividades&id_curso=<?php echo $curso['id_curso']; ?>" 
                                   class="btn btn-sm btn-primary w-100">
                                    <i class="fas fa-tasks me-1"></i>Actividades
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="index.php?accion=entregas&id_curso=<?php echo $curso['id_curso']; ?>" 
                                   class="btn btn-sm btn-outline-primary w-100">
                                    <i class="fas fa-upload me-1"></i>Entregas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-book-open text-muted fa-4x mb-4"></i>
                        <h4>No tienes cursos asignados</h4>
                        <p class="text-muted">Contacta con el administrador para que te asigne cursos.</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoCurso">
                            <i class="fas fa-plus me-2"></i>Solicitar Nuevo Curso
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Nuevo Curso -->
<div class="modal fade" id="modalNuevoCurso" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Solicitar Nuevo Curso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Para crear un nuevo curso, contacta con el administrador del sistema.</p>
                <p>También puedes enviar una solicitud por correo electrónico indicando:</p>
                <ul>
                    <li>Nombre del curso</li>
                    <li>Descripción</li>
                    <li>Número aproximado de estudiantes</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>