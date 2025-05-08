<?php
require_once '../../includes/auth.php';
require_once '../../includes/funciones.php';

if ($_SESSION['rol'] != 'admin') {
    header("Location: ../../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_curso = $_GET['id'];

// Obtener información del curso
$stmt = $pdo->prepare("SELECT c.*, u.nombre as docente FROM cursos c 
                      JOIN usuarios u ON c.id_docente = u.id_usuario 
                      WHERE c.id_curso = ?");
$stmt->execute([$id_curso]);
$curso = $stmt->fetch();

if (!$curso) {
    header("Location: index.php");
    exit();
}

// Obtener horarios del curso
$stmt = $pdo->prepare("SELECT * FROM horarios WHERE id_curso = ? ORDER BY dia_semana, hora_inicio");
$stmt->execute([$id_curso]);
$horarios = $stmt->fetchAll();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dia_semana = $_POST['dia_semana'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    
    // Verificar conflicto de horarios para el docente
    $stmt = $pdo->prepare("SELECT h.*, c.nombre_curso 
                          FROM horarios h
                          JOIN cursos c ON h.id_curso = c.id_curso
                          WHERE c.id_docente = ? 
                          AND h.dia_semana = ?
                          AND (
                              (? BETWEEN h.hora_inicio AND h.hora_fin) OR
                              (? BETWEEN h.hora_inicio AND h.hora_fin) OR
                              (h.hora_inicio BETWEEN ? AND ?) OR
                              (h.hora_fin BETWEEN ? AND ?)
                          )");
    $stmt->execute([
        $curso['id_docente'],
        $dia_semana,
        $hora_inicio, $hora_fin,
        $hora_inicio, $hora_fin,
        $hora_inicio, $hora_fin
    ]);
    
    if ($stmt->rowCount() > 0) {
        $conflictos = $stmt->fetchAll();
        $error = 'El docente ya tiene un curso en ese horario: ';
        foreach ($conflictos as $conflicto) {
            $error .= $conflicto['nombre_curso'] . ' (' . $conflicto['dia_semana'] . ' ' . 
                     $conflicto['hora_inicio'] . '-' . $conflicto['hora_fin'] . '), ';
        }
        $error = rtrim($error, ', ');
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO horarios (id_curso, dia_semana, hora_inicio, hora_fin) 
                                 VALUES (?, ?, ?, ?)");
            $stmt->execute([$id_curso, $dia_semana, $hora_inicio, $hora_fin]);
            
            if ($stmt->rowCount() > 0) {
                $success = 'Horario agregado exitosamente';
                header("Refresh:2; url=horarios.php?id=$id_curso");
            }
        } catch(PDOException $e) {
            $error = 'Error al agregar horario: ' . $e->getMessage();
        }
    }
}

// Procesar eliminación de horario
if (isset($_GET['eliminar'])) {
    $id_horario = $_GET['eliminar'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM horarios WHERE id_horario = ? AND id_curso = ?");
        $stmt->execute([$id_horario, $id_curso]);
        
        if ($stmt->rowCount() > 0) {
            $success = 'Horario eliminado exitosamente';
            header("Refresh:2; url=horarios.php?id=$id_curso");
        }
    } catch(PDOException $e) {
        $error = 'Error al eliminar horario: ' . $e->getMessage();
    }
}
?>

<?php include '../../includes/header.php'; ?>

<div class="container mt-4">
    <h2>Horarios del Curso: <?php echo htmlspecialchars($curso['nombre_curso']); ?></h2>
    <p>Docente: <?php echo htmlspecialchars($curso['docente']); ?></p>
    
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Agregar Nuevo Horario</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="dia_semana">Día de la Semana</label>
                            <select class="form-control" id="dia_semana" name="dia_semana" required>
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miércoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sábado">Sábado</option>
                                <option value="Domingo">Domingo</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="hora_inicio">Hora de Inicio</label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="hora_fin">Hora de Fin</label>
                            <input type="time" class="form-control" id="hora_fin" name="hora_fin" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Agregar Horario</button>
                        <a href="ver.php?id=<?php echo $id_curso; ?>" class="btn btn-secondary">Volver al Curso</a>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Horarios Actuales</h5>
                </div>
                <div class="card-body">
                    <?php if (count($horarios) > 0): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Día</th>
                                <th>Horario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($horarios as $horario): ?>
                            <tr>
                                <td><?php echo $horario['dia_semana']; ?></td>
                                <td><?php echo substr($horario['hora_inicio'], 0, 5); ?> - <?php echo substr($horario['hora_fin'], 0, 5); ?></td>
                                <td>
                                    <a href="horarios.php?id=<?php echo $id_curso; ?>&eliminar=<?php echo $horario['id_horario']; ?>" 
                                       class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este horario?')">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <p>No hay horarios asignados a este curso.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>