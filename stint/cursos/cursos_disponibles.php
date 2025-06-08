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

try {
    // Get user information
    $sql_usuario = "SELECT nombre, rol FROM usuarios WHERE id_usuario = :id_estudiante";
    $stmt_usuario = $pdo->prepare($sql_usuario);
    $stmt_usuario->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
    $stmt_usuario->execute();
    $usuario = $stmt_usuario->fetch();

    if (!$usuario) {
        die("Error: Usuario no encontrado.");
    }

    // Get all available courses with enrollment status
    $sql_cursos = "SELECT c.id_curso, c.nombre_curso, c.descripcion, c.capacidad, c.grupo,
                          u.nombre AS profesor_nombre,
                          COUNT(ec.id_estudiante) AS estudiantes_inscritos,
                          CASE WHEN ec_user.id_estudiante IS NOT NULL THEN 1 ELSE 0 END AS ya_inscrito
                   FROM cursos c
                   LEFT JOIN usuarios u ON c.id_docente = u.id_usuario
                   LEFT JOIN estudiantes_cursos ec ON c.id_curso = ec.id_curso
                   LEFT JOIN estudiantes_cursos ec_user ON c.id_curso = ec_user.id_curso AND ec_user.id_estudiante = :id_estudiante
                   GROUP BY c.id_curso, c.nombre_curso, c.descripcion, c.capacidad, c.grupo, u.nombre, ec_user.id_estudiante
                   ORDER BY c.nombre_curso";

    $stmt_cursos = $pdo->prepare($sql_cursos);
    $stmt_cursos->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
    $stmt_cursos->execute();
    $cursos = $stmt_cursos->fetchAll();

} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cursos Disponibles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fc;
            color: #343a40;
        }
        .course-card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #4e73df;
            color: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color:#4e73df;
            border: none;
        }
        .btn-primary:hover {
            background-color:rgb(22, 35, 155);
        }
        .badge-enrolled {
            background-color: #28a745;
        }
        .badge-full {
            background-color: #dc3545;
        }
        .badge-available {
            background-color: #17a2b8;
        }
        .course-stats {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="header d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-book"></i> Cursos Disponibles</h2>
            <p class="mb-0">Explora y inscríbete en los cursos disponibles</p>
        </div>
        <a href="../dashboard_alumnos.php" class="btn btn-light">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
        </a>
    </div>

    <?php if (empty($cursos)): ?>
        <div class="alert alert-info" role="alert">
            <h5><i class="fas fa-info-circle"></i> No hay cursos disponibles</h5>
            <p>Actualmente no hay cursos disponibles para inscribirse.</p>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($cursos as $curso): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="course-card p-3 rounded shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title"><?= htmlspecialchars($curso['nombre_curso']) ?></h5>
                            <?php if ($curso['ya_inscrito']): ?>
                                <span class="badge badge-enrolled">
                                    <i class="fas fa-check-circle"></i> Inscrito
                                </span>
                            <?php elseif ($curso['estudiantes_inscritos'] >= $curso['capacidad']): ?>
                                <span class="badge badge-full">
                                    <i class="fas fa-times-circle"></i> Lleno
                                </span>
                            <?php else: ?>
                                <span class="badge badge-available">
                                    <i class="fas fa-plus-circle"></i> Disponible
                                </span>
                            <?php endif; ?>
                        </div>

                        <p class="text-muted small mb-2">
                            <?= htmlspecialchars(substr($curso['descripcion'] ?? 'Sin descripción', 0, 100)) ?>
                            <?= strlen($curso['descripcion'] ?? '') > 100 ? '...' : '' ?>
                        </p>

                        <div class="course-stats">
                            <div class="row text-center">
                                <div class="col-6">
                                    <small class="text-muted">Profesor</small><br>
                                    <strong><?= htmlspecialchars($curso['profesor_nombre'] ?? 'No asignado') ?></strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Grupo</small><br>
                                    <strong><?= htmlspecialchars($curso['grupo'] ?? 'N/A') ?></strong>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-users"></i> 
                                <?= $curso['estudiantes_inscritos'] ?>/<?= $curso['capacidad'] ?> estudiantes
                            </small>
                            <a href="ver_curso.php?id=<?= $curso['id_curso'] ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="mt-4 text-center">
        <a href="../dashboard_alumnos.php" class="btn btn-outline-secondary">
            <i class="fas fa-home"></i> Volver al Dashboard
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>