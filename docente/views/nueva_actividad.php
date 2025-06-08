<?php
require_once '../models/CursoModel.php';
session_start();

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_docente = $_SESSION['id_usuario'];

$cursoModel = new CursoModel();
$cursos = $cursoModel->listarCursosPorDocente($id_docente);

// Mensajes de error o éxito
$mensaje = '';
$tipo_mensaje = '';

if (isset($_GET['error'])) {
    $tipo_mensaje = 'danger';
    switch ($_GET['error']) {
        case '1':
            $mensaje = 'Error al crear la actividad. Intenta nuevamente.';
            break;
        case '2':
            $mensaje = isset($_GET['msg']) ? $_GET['msg'] : 'Todos los campos son obligatorios.';
            break;
        case '3':
            $mensaje = isset($_GET['msg']) ? $_GET['msg'] : 'No tienes permisos para este curso.';
            break;
        default:
            $mensaje = 'Ha ocurrido un error.';
    }
}

if (isset($_GET['success'])) {
    $tipo_mensaje = 'success';
    $mensaje = isset($_GET['msg']) ? $_GET['msg'] : 'Actividad creada exitosamente.';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Actividad - Sistema Académico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #059669;
            --danger-color: #dc2626;
            --warning-color: #d97706;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .form-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            color: white;
            padding: 2rem;
            text-align: center;
            border: none;
        }

        .card-header h3 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .card-header i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .card-body {
            padding: 2.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }

        .btn {
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary-color), #94a3b8);
            border: none;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(100, 116, 139, 0.4);
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
        }

        .curso-info {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .curso-count {
            color: var(--primary-color);
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="form-card">
                        <div class="card-header">
                            <i class="fas fa-tasks"></i>
                            <h3>Nueva Actividad</h3>
                            <small>Crea una nueva actividad para tus estudiantes</small>
                        </div>
                        
                        <div class="card-body">
                            <?php if (!empty($mensaje)): ?>
                                <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show" role="alert">
                                    <i class="fas fa-<?= $tipo_mensaje === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                                    <?= htmlspecialchars($mensaje) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <!-- Información de cursos -->
                            <div class="curso-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-book me-2"></i>Mis Cursos</span>
                                    <span class="curso-count"><?= count($cursos) ?> curso(s) asignado(s)</span>
                                </div>
                            </div>

                            <form action="../controllers/guardar_actividad.php" method="POST" id="formActividad">
                                <!-- Selector de Curso -->
                                <div class="mb-4">
                                    <label for="id_curso" class="form-label">
                                        <i class="fas fa-graduation-cap me-2"></i>Curso
                                    </label>
                                    <select name="id_curso" id="id_curso" class="form-select" required>
                                        <option value="">-- Seleccione un curso --</option>
                                        <?php if (!empty($cursos)): ?>
                                            <?php foreach($cursos as $curso): ?>
                                                <option value="<?= htmlspecialchars($curso['id_curso']) ?>">
                                                    <?= htmlspecialchars($curso["nombre_curso"]) ?>
                                                    <?php if (!empty($curso['codigo_curso'])): ?>
                                                        (<?= htmlspecialchars($curso['codigo_curso']) ?>)
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="" disabled>No tienes cursos asignados</option>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <!-- Título -->
                                <div class="mb-4">
                                    <label for="titulo" class="form-label">
                                        <i class="fas fa-heading me-2"></i>Título de la Actividad
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="titulo" 
                                           name="titulo" 
                                           required 
                                           placeholder="Ej: Tarea de Matemáticas - Ecuaciones Lineales"
                                           maxlength="100">
                                </div>

                                <!-- Tipo de Actividad -->
                                <div class="mb-4">
                                    <label for="tipo" class="form-label">
                                        <i class="fas fa-tag me-2"></i>Tipo de Actividad
                                    </label>
                                    <select name="tipo" id="tipo" class="form-select" required>
                                        <option value="Tarea">Tarea</option>
                                        <option value="Proyecto">Proyecto</option>
                                        <option value="Examen">Examen</option>
                                        <option value="Laboratorio">Laboratorio</option>
                                        <option value="Investigación">Investigación</option>
                                    </select>
                                </div>

                                <!-- Descripción -->
                                <div class="mb-4">
                                    <label for="descripcion" class="form-label">
                                        <i class="fas fa-align-left me-2"></i>Descripción
                                    </label>
                                    <textarea class="form-control" 
                                              id="descripcion" 
                                              name="descripcion" 
                                              rows="4" 
                                              required 
                                              placeholder="Describe detalladamente la actividad, objetivos, requisitos y criterios de evaluación..."
                                              maxlength="1000"></textarea>
                                    <div class="form-text">
                                        <span id="contador">0</span>/1000 caracteres
                                    </div>
                                </div>

                                <!-- Fecha de Entrega -->
                                <div class="mb-4">
                                    <label for="fecha_entrega" class="form-label">
                                        <i class="fas fa-calendar-alt me-2"></i>Fecha Límite de Entrega
                                    </label>
                                    <input type="datetime-local" 
                                           class="form-control" 
                                           id="fecha_entrega" 
                                           name="fecha_entrega" 
                                           required
                                           min="<?= date('Y-m-d\TH:i') ?>">
                                </div>

                                <!-- Botones -->
                                <div class="d-grid gap-3">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>Crear Actividad
                                    </button>
                                    <a href="actividades_listado.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Contador de caracteres para descripción
        const descripcion = document.getElementById('descripcion');
        const contador = document.getElementById('contador');
        
        descripcion.addEventListener('input', function() {
            contador.textContent = this.value.length;
        });

        // Validación del formulario
        document.getElementById('formActividad').addEventListener('submit', function(e) {
            const titulo = document.getElementById('titulo').value.trim();
            const descripcion = document.getElementById('descripcion').value.trim();
            const fecha = document.getElementById('fecha_entrega').value;
            const curso = document.getElementById('id_curso').value;

            if (!titulo || !descripcion || !fecha || !curso) {
                e.preventDefault();
                alert('Por favor, completa todos los campos obligatorios.');
                return false;
            }

            // Validar que la fecha sea futura
            const fechaSeleccionada = new Date(fecha);
            const ahora = new Date();
            
            if (fechaSeleccionada <= ahora) {
                e.preventDefault();
                alert('La fecha de entrega debe ser posterior a la fecha actual.');
                return false;
            }
        });

        // Auto-dismissal de alertas
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>