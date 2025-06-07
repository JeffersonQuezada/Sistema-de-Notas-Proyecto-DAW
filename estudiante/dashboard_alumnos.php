<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

// Include database connection and functions
include '../includes/conexion.php';
include '../includes/funciones.php';

// Check if PDO connection was successful
if (!isset($pdo) || $pdo === null) {
    die("Error: No se pudo establecer la conexión a la base de datos. Verifique el archivo de conexión.");
}

$id_estudiante = $_SESSION['id_usuario'];

try {
    // Get user information using PDO (removed foto_url and fecha_inscripcion as they don't exist)
    $sql_usuario = "SELECT nombre, correo, rol, fecha_registro FROM usuarios WHERE id_usuario = :id_estudiante";
    $stmt_usuario = $pdo->prepare($sql_usuario);
    $stmt_usuario->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
    $stmt_usuario->execute();
    $usuario = $stmt_usuario->fetch();

    if (!$usuario) {
        die("Error: Usuario no encontrado.");
    }

    // Get courses information using the correct table structure
    $sql_cursos = "SELECT c.id_curso, c.nombre_curso, u.nombre AS profesor_nombre,
                          (SELECT ROUND(AVG(n.nota), 2)
                           FROM notas n 
                           WHERE n.id_estudiante = :id_estudiante1 
                           AND n.id_curso = c.id_curso) AS promedio
                   FROM estudiantes_cursos ec
                   JOIN cursos c ON ec.id_curso = c.id_curso
                   LEFT JOIN usuarios u ON c.id_docente = u.id_usuario
                   WHERE ec.id_estudiante = :id_estudiante2";

    $stmt_cursos = $pdo->prepare($sql_cursos);
    $stmt_cursos->bindParam(':id_estudiante1', $id_estudiante, PDO::PARAM_INT);
    $stmt_cursos->bindParam(':id_estudiante2', $id_estudiante, PDO::PARAM_INT);
    $stmt_cursos->execute();
    $result_cursos = $stmt_cursos->fetchAll();

    $cursos = [];
    $total_promedio = 0;
    $cantidad_con_promedio = 0;

    foreach ($result_cursos as $curso) {
        if (!is_null($curso['promedio'])) {
            $total_promedio += $curso['promedio'];
            $cantidad_con_promedio++;
        }
        $cursos[] = $curso;
    }

    $cum = $cantidad_con_promedio ? round($total_promedio / $cantidad_con_promedio, 2) : 0;

} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Estudiante</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fc;
            color: #343a40;
        }
        .profile-card {
            background: #ffffff;
            border-left: 5px solid #6f42c1;
        }
        .course-card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            transition: transform 0.2s;
        }
        .course-card:hover {
            transform: scale(1.01);
        }
        .header {
            background-color: #4e73df;
            color: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #4e73df;
            color: white;
            margin-top: 40px;
        }
        .btn-primary {
            background-color:#4e73df;
            border: none;
        }
        .btn-primary:hover {
            background-color:rgb(22, 35, 155);
        }
        .rounded-circle {
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="header">
        <h2>Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?> 👋</h2>
    </div>

    <div class="row mb-4 profile-card p-3">
        <div class="col-md-2">
            <!-- Default user icon since foto_url doesn't exist in your database -->
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="#6f42c1" class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
            </svg>
        </div>

        <div class="col-md-10">
            <p><strong>Correo:</strong> <?= htmlspecialchars($usuario['correo']) ?></p>
            <p><strong>Rol:</strong> <?= htmlspecialchars($usuario['rol']) ?></p>
            <p><strong>Fecha de Registro:</strong> <?= date("d/m/Y H:i", strtotime($usuario['fecha_registro'])) ?></p>
            <p><strong>ID Usuario:</strong> <?= $id_estudiante ?></p>
        </div>
    </div>

    <div class="mb-3">
        <h4 class="alert alert-primary" role="alert">Cursos Inscritos (<?= count($cursos) ?>)</h4>
    </div>

    <?php if (empty($cursos)): ?>
        <div class="alert alert-info" role="alert">
            <h5>No tienes cursos inscritos</h5>
            <p>Contacta con tu administrador para inscribirte en cursos.</p>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($cursos as $curso): ?>
                <div class="col-md-6 mb-4">
                    <div class="course-card p-3 rounded shadow-sm">
                        <h5><?= htmlspecialchars($curso['nombre_curso']) ?></h5>
                        <p><strong>Profesor:</strong> <?= htmlspecialchars($curso['profesor_nombre'] ?? 'No asignado') ?></p>
                        <p><strong>Promedio:</strong> <?= is_null($curso['promedio']) ? 'No disponible' : $curso['promedio'] ?></p>
                        <a href="curso.php?id=<?= $curso['id_curso'] ?>" class="btn btn-primary">Ver curso</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4">
            <h5 class="text-success">Promedio General (CUM): <strong><?= $cum ?></strong></h5>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="cursos/cursos_disponibles.php" class="btn btn-primary me-2">
            <i class="fas fa-search"></i> Explorar Cursos Disponibles
        </a>
        <a href="../logout.php" class="btn btn-outline-danger">Cerrar sesión</a>
    </div>

    <div class="footer mt-5">
        Proyecto Grupal © <?= date("Y") ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>