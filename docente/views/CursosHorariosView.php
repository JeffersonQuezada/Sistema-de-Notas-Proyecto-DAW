<?php
class CursosHorariosView {
    
    private $dias_semana = [
        1 => 'Lunes',
        2 => 'Martes', 
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo'
    ];
    
    public function mostrar($cursosConHorarios, $docentes) {
        $pageTitle = "Cursos y Horarios";
        include __DIR__ . '/../../includes/header.php';
        ?>
        
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Gestión de Cursos y Horarios</h2>
                <div>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAsignarHorario">
                        <i class="fas fa-plus"></i> Asignar Horario
                    </button>
                </div>
            </div>
            
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-success"><?= $_SESSION['mensaje'] ?></div>
                <?php unset($_SESSION['mensaje']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <!-- Filtros -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <select class="form-select" id="filtroDocente" onchange="filtrarPorDocente()">
                        <option value="">Todos los docentes</option>
                        <?php foreach ($docentes as $docente): ?>
                        <option value="<?= $docente['id_usuario'] ?>">
                            <?= htmlspecialchars($docente['nombre']) ?> (<?= $docente['total_cursos'] ?> cursos)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <!-- Tabla de cursos -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Curso</th>
                            <th>Docente</th>
                            <th>Grupo</th>
                            <th>Estudiantes</th>
                            <th>Horario</th>
                            <th>Aula</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $cursosAgrupados = [];
                        foreach ($cursosConHorarios as $curso) {
                            $cursosAgrupados[$curso['id_curso']]['info'] = $curso;
                            if ($curso['dia_semana']) {
                                $cursosAgrupados[$curso['id_curso']]['horarios'][] = $curso;
                            }
                        }
                        
                        foreach ($cursosAgrupados as $cursoData): 
                            $curso = $cursoData['info'];
                            $horarios = $cursoData['horarios'] ?? [];
                        ?>
                        <tr data-docente="<?= $curso['id_docente'] ?>">
                            <td>
                                <strong><?= htmlspecialchars($curso['nombre_curso']) ?></strong>
                                <br><small class="text-muted"><?= htmlspecialchars($curso['descripcion']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($curso['nombre_docente']) ?></td>
                            <td><?= $curso['grupo'] ?? '-' ?></td>
                            <td>
                                <span class="badge bg-info"><?= $curso['estudiantes_inscritos'] ?>/<?= $curso['capacidad'] ?></span>
                            </td>
                            <td>
                                <?php if (empty($horarios)): ?>
                                    <span class="text-muted">Sin horario asignado</span>
                                <?php else: ?>
                                    <?php foreach ($horarios as $horario): ?>
                                        <div class="mb-1">
                                            <strong><?= $this->dias_semana[$horario['dia_semana']] ?></strong><br>
                                            <?= date('H:i', strtotime($horario['hora_inicio'])) ?> - 
                                            <?= date('H:i', strtotime($horario['hora_fin'])) ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (empty($horarios)): ?>
                                    -
                                <?php else: ?>
                                    <?php foreach ($horarios as $horario): ?>
                                        <div><?= $horario['aula'] ?? 'Sin asignar' ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?accion=ver_curso_detalle&id=<?= $curso['id_curso'] ?>" 
                                   class="btn btn-sm btn-info" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-warning" title="Editar horario"
                                        onclick="editarHorario(<?= $curso['id_curso'] ?>, '<?= htmlspecialchars($curso['nombre_curso']) ?>')">
                                    <i class="fas fa-clock"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Modal para asignar horario -->
        <div class="modal fade" id="modalAsignarHorario" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Asignar Horario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="index.php?accion=asignar_horario">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Curso</label>
                                <select name="id_curso" class="form-select" required>
                                    <option value="">Seleccione un curso</option>
                                    <?php foreach ($cursosAgrupados as $cursoData): ?>
                                    <option value="<?= $cursoData['info']['id_curso'] ?>">
                                        <?= htmlspecialchars($cursoData['info']['nombre_curso']) ?> - 
                                        <?= htmlspecialchars($cursoData['info']['nombre_docente']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Día de la semana</label>
                                <select name="dia_semana" class="form-select" required>
                                    <option value="">Seleccione un día</option>
                                    <?php foreach ($this->dias_semana as $num => $dia): ?>
                                    <option value="<?= $num ?>"><?= $dia ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Hora inicio</label>
                                        <input type="time" name="hora_inicio" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Hora fin</label>
                                        <input type="time" name="hora_fin" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Aula (opcional)</label>
                                <input type="text" name="aula" class="form-control" placeholder="Ej: Aula 101">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Asignar Horario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <script>
        function filtrarPorDocente() {
            const select = document.getElementById('filtroDocente');
            const idDocente = select.value;
            const filas = document.querySelectorAll('tbody tr[data-docente]');
            
            filas.forEach(fila => {
                if (idDocente === '' || fila.getAttribute('data-docente') === idDocente) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        }
        
        function editarHorario(idCurso, nombreCurso) {
            // Aquí puedes implementar la lógica para editar horarios
            alert('Funcionalidad de editar horario para: ' + nombreCurso);
        }
        </script>
        
        <?php
        include __DIR__ . '/../../includes/footer.php';
    }
    
    public function mostrarCursosPorDocente($cursos, $docente) {
        $pageTitle = "Cursos de " . $docente['nombre'];
        include __DIR__ . '/../../includes/header.php';
        ?>
        
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Cursos de <?= htmlspecialchars($docente['nombre']) ?></h2>
                <a href="index.php?accion=cursos_horarios" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            
            <div class="row">
                <?php foreach ($cursos as $curso): ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5><?= htmlspecialchars($curso['nombre_curso']) ?></h5>
                            <?php if ($curso['grupo']): ?>
                                <span class="badge bg-secondary">Grupo <?= $curso['grupo'] ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= htmlspecialchars($curso['descripcion']) ?></p>
                            <p><strong>Estudiantes:</strong> <?= $curso['estudiantes_inscritos'] ?>/<?= $curso['capacidad'] ?></p>
                            
                            <?php if ($curso['dia_semana']): ?>
                                <div class="mt-3">
                                    <h6>Horario:</h6>
                                    <p>
                                        <i class="fas fa-calendar"></i> <?= $this->dias_semana[$curso['dia_semana']] ?><br>
                                        <i class="fas fa-clock"></i> <?= date('H:i', strtotime($curso['hora_inicio'])) ?> - 
                                        <?= date('H:i', strtotime($curso['hora_fin'])) ?>
                                        <?php if ($curso['aula']): ?>
                                            <br><i class="fas fa-door-open"></i> <?= $curso['aula'] ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Sin horario asignado
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <?php
        include __DIR__ . '/../../includes/footer.php';
    }
}