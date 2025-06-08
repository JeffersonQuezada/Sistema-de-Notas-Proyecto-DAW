<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

include '../../includes/conexion.php';
include '../../includes/funciones.php';

if (!isset($pdo) || $pdo === null) {
    die("Error: No se pudo establecer la conexión a la base de datos.");
}

$id_estudiante = $_SESSION['id_usuario'];
$id_curso = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_curso <= 0) {
    header("Location: cursos_disponibles.php");
    exit();
}

// Handle enrollment/unenrollment
$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $contrasena_curso = $_POST['contrasena'] ?? '';
    
    try {
        if ($accion === 'inscribir') {
            // Verify course password
            $sql_verificar = "SELECT contrasena FROM cursos WHERE id_curso = :id_curso";
            $stmt_verificar = $pdo->prepare($sql_verificar);
            $stmt_verificar->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
            $stmt_verificar->execute();
            $curso_password = $stmt_verificar->fetch();
            
            if (!$curso_password || !password_verify($contrasena_curso, $curso_password['contrasena'])) {
                $mensaje = "Contraseña del curso incorrecta.";
                $tipo_mensaje = "danger";
            } else {
                // Check if already enrolled
                $sql_check = "SELECT id FROM estudiantes_cursos WHERE id_estudiante = :id_estudiante AND id_curso = :id_curso";
                $stmt_check = $pdo->prepare($sql_check);
                $stmt_check->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
                $stmt_check->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
                $stmt_check->execute();
                
                if ($stmt_check->fetch()) {
                    $mensaje = "Ya estás inscrito en este curso.";
                    $tipo_mensaje = "warning";
                } else {
                    // Check capacity
                    $sql_capacity = "SELECT c.capacidad, COUNT(ec.id_estudiante) as inscritos 
                                   FROM cursos c 
                                   LEFT JOIN estudiantes_cursos ec ON c.id_curso = ec.id_curso 
                                   WHERE c.id_curso = :id_curso 
                                   GROUP BY c.id_curso, c.capacidad";
                    $stmt_capacity = $pdo->prepare($sql_capacity);
                    $stmt_capacity->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
                    $stmt_capacity->execute();
                    $capacity_info = $stmt_capacity->fetch();
                    
                    if ($capacity_info && $capacity_info['inscritos'] >= $capacity_info['capacidad']) {
                        $mensaje = "El curso ha alcanzado su capacidad máxima.";
                        $tipo_mensaje = "warning";
                    } else {
                        // Enroll student
                        $sql_inscribir = "INSERT INTO estudiantes_cursos (id_estudiante, id_curso) VALUES (:id_estudiante, :id_curso)";
                        $stmt_inscribir = $pdo->prepare($sql_inscribir);
                        $stmt_inscribir->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
                        $stmt_inscribir->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
                        $stmt_inscribir->execute();
                        
                        $mensaje = "¡Te has inscrito exitosamente en el curso!";
                        $tipo_mensaje = "success";
                    }
                }
            }
        } elseif ($accion === 'desinscribir') {
            // Unenroll student
            $sql_desinscribir = "DELETE FROM estudiantes_cursos WHERE id_estudiante = :id_estudiante AND id_curso = :id_curso";
            $stmt_desinscribir = $pdo->prepare($sql_desinscribir);
            $stmt_desinscribir->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
            $stmt_desinscribir->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
            $stmt_desinscribir->execute();
            
            $mensaje = "Te has desinscrito del curso exitosamente.";
            $tipo_mensaje = "info";
        }
    } catch (PDOException $e) {
        $mensaje = "Error al procesar la solicitud: " . $e->getMessage();
        $tipo_mensaje = "danger";
    }
}

try {
    // Get course details
    $sql_curso = "SELECT c.id_curso, c.nombre_curso, c.descripcion, c.capacidad, c.grupo,
                         u.nombre AS profesor_nombre, u.correo AS profesor_correo,
                         COUNT(ec.id_estudiante) AS estudiantes_inscritos,
                         CASE WHEN ec_user.id_estudiante IS NOT NULL THEN 1 ELSE 0 END AS ya_inscrito
                  FROM cursos c
                  LEFT JOIN usuarios u ON c.id_docente = u.id_usuario
                  LEFT JOIN estudiantes_cursos ec ON c.id_curso = ec.id_curso
                  LEFT JOIN estudiantes_cursos ec_user ON c.id_curso = ec_user.id_curso AND ec_user.id_estudiante = :id_estudiante
                  WHERE c.id_curso = :id_curso
                  GROUP BY c.id_curso, c.nombre_curso, c.descripcion, c.capacidad, c.grupo, u.nombre, u.correo, ec_user.id_estudiante";

    $stmt_curso = $pdo->prepare($sql_curso);
    $stmt_curso->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
    $stmt_curso->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
    $stmt_curso->execute();
    $curso = $stmt_curso->fetch();

    if (!$curso) {
        header("Location: cursos_disponibles.php");
        exit();
    }

    // Get course schedule
    $sql_horarios = "SELECT dia_semana, hora_inicio, hora_fin FROM horarios WHERE id_curso = :id_curso ORDER BY 
                     CASE dia_semana 
                         WHEN 'Lunes' THEN 1
                         WHEN 'Martes' THEN 2
                         WHEN 'Miércoles' THEN 3
                         WHEN 'Jueves' THEN 4
                         WHEN 'Viernes' THEN 5
                         WHEN 'Sábado' THEN 6
                         WHEN 'Domingo' THEN 7
                     END";
    $stmt_horarios = $pdo->prepare($sql_horarios);
    $stmt_horarios->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
    $stmt_horarios->execute();
    $horarios = $stmt_horarios->fetchAll();

    // Get recent activities
    $sql_actividades = "SELECT nombre, tipo, fecha_limite FROM actividades 
                        WHERE id_curso = :id_curso 
                        ORDER BY fecha_limite DESC LIMIT 5";
    $stmt_actividades = $pdo->prepare($sql_actividades);
    $stmt_actividades->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
    $stmt_actividades->execute();
    $actividades = $stmt_actividades->fetchAll();

} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($curso['nombre_curso']) ?> - Detalles del Curso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fc;
            color: #343a40;
        }
        .header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .info-card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color:#4e73df;
            border: none;
        }
        .btn-primary:hover {
            background-color:rgb(22, 35, 155);
        }
        .badge-status {
            font-size: 0.9em;
            padding: 8px 12px;
        }
        .progress-custom {
            height: 25px;
            background-color: #e9ecef;
            border-radius: 15px;
        }
        .schedule-item {
            background-color: #f8f9fa;
            border-left: 4px solid #4e73df;
            padding: 10px 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-book-open"></i> <?= htmlspecialchars($curso['nombre_curso']) ?></h1>
                <p class="mb-0 fs-5">Grupo: <?= htmlspecialchars($curso['grupo'] ?? 'N/A') ?></p>
            </div>
            <div class="text-end">
                <?php if ($curso['ya_inscrito']): ?>
                    <span class="badge bg-success badge-status">
                        <i class="fas fa-check-circle"></i> Inscrito
                    </span>
                <?php elseif ($curso['estudiantes_inscritos'] >= $curso['capacidad']): ?>
                    <span class="badge bg-danger badge-status">
                        <i class="fas fa-times-circle"></i> Curso Lleno
                    </span>
                <?php else: ?>
                    <span class="badge bg-info badge-status">
                        <i class="fas fa-plus-circle"></i> Disponible
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($mensaje) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <!-- Course Description -->
            <div class="info-card">
                <h4><i class="fas fa-info-circle text-primary"></i> Descripción del Curso</h4>
                <p class="lead"><?= htmlspecialchars($curso['descripcion'] ?? 'Sin descripción disponible.') ?></p>
            </div>

            <!-- Schedule -->
            <?php if (!empty($horarios)): ?>
            <div class="info-card">
                <h4><i class="fas fa-calendar-alt text-primary"></i> Horarios</h4>
                <?php foreach ($horarios as $horario): ?>
                    <div class="schedule-item">
                        <strong><?= htmlspecialchars($horario['dia_semana']) ?></strong>: 
                        <?= date('H:i', strtotime($horario['hora_inicio'])) ?> - 
                        <?= date('H:i', strtotime($horario['hora_fin'])) ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Recent Activities -->
            <?php if (!empty($actividades)): ?>
            <div class="info-card">
                <h4><i class="fas fa-tasks text-primary"></i> Actividades Recientes</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Actividad</th>
                                <th>Tipo</th>
                                <th>Fecha Límite</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($actividades as $actividad): ?>
                            <tr>
                                <td><?= htmlspecialchars($actividad['nombre']) ?></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($actividad['tipo']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($actividad['fecha_limite'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <!-- Course Info -->
            <div class="info-card">
                <h4><i class="fas fa-user-tie text-primary"></i> Información del Curso</h4>
                <div class="mb-3">
                    <strong>Profesor:</strong><br>
                    <?= htmlspecialchars($curso['profesor_nombre'] ?? 'No asignado') ?>
                    <?php if ($curso['profesor_correo']): ?>
                        <br><small class="text-muted"><?= htmlspecialchars($curso['profesor_correo']) ?></small>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <strong>Capacidad:</strong><br>
                    <div class="progress progress-custom">
                        <?php 
                        $porcentaje = ($curso['estudiantes_inscritos'] / $curso['capacidad']) * 100;
                        $color_progress = $porcentaje >= 100 ? 'bg-danger' : ($porcentaje >= 80 ? 'bg-warning' : 'bg-success');
                        ?>
                        <div class="progress-bar <?= $color_progress ?>" style="width: <?= $porcentaje ?>%">
                            <?= $curso['estudiantes_inscritos'] ?>/<?= $curso['capacidad'] ?>
                        </div>
                    </div>
                    <small class="text-muted"><?= round($porcentaje, 1) ?>% ocupado</small>
                </div>
            </div>

            <!-- Enrollment Actions -->
            <div class="info-card">
                <h4><i class="fas fa-graduation-cap text-primary"></i> Inscripción</h4>
                
                <?php if ($curso['ya_inscrito']): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Ya estás inscrito en este curso
                    </div>
                    <form method="POST" onsubmit="return confirm('¿Estás seguro de que quieres desinscribirte de este curso?')">
                        <input type="hidden" name="accion" value="desinscribir">
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-sign-out-alt"></i> Desinscribirse
                        </button>
                    </form>
                <?php elseif ($curso['estudiantes_inscritos'] >= $curso['capacidad']): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Este curso ha alcanzado su capacidad máxima
                    </div>
                <?php else: ?>
                    <form method="POST">
                        <input type="hidden" name="accion" value="inscribir">
                        <div class="mb-3">
                            <label for="contrasena" class="form-label">
                                <i class="fas fa-lock"></i> Contraseña del Curso
                            </label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                            <small class="form-text text-muted">Solicita la contraseña al profesor del curso</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus-circle"></i> Inscribirse en el Curso
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="mt-4 text-center">
        <a href="cursos_disponibles.php" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left"></i> Volver a Cursos
        </a>
        <a href="dashboard_alumnos.php" class="btn btn-outline-primary">
            <i class="fas fa-home"></i> Dashboard
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>