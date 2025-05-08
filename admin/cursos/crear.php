<?php
require_once '../../includes/auth.php';
require_once '../../includes/funciones.php';

if ($_SESSION['rol'] != 'admin') {
    header("Location: ../../login.php");
    exit();
}

$error = '';
$success = '';

// Obtener docentes para el select
$stmt = $pdo->prepare("SELECT id_usuario, nombre FROM usuarios WHERE rol = 'docente' ORDER BY nombre");
$stmt->execute();
$docentes = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $id_docente = $_POST['id_docente'];
    $contrasena = trim($_POST['contrasena']);
    $capacidad = $_POST['capacidad'];
    
    // Validaciones
    if (empty($nombre) || empty($contrasena) || empty($id_docente)) {
        $error = 'Nombre, docente y contraseña son obligatorios';
    } else {
        try {
            $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO cursos (nombre_curso, descripcion, id_docente, contrasena, capacidad) 
                                 VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $descripcion, $id_docente, $hashed_password, $capacidad]);
            
            if ($stmt->rowCount() > 0) {
                $success = 'Curso creado exitosamente';
                header("Refresh:2; url=index.php");
            }
        } catch(PDOException $e) {
            $error = 'Error al crear el curso: ' . $e->getMessage();
        }
    }
}
?>

<?php include '../../includes/header.php'; ?>

<div class="container mt-4">
    <h2>Crear Nuevo Curso</h2>
    
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre del Curso</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="id_docente">Docente</label>
                    <select class="form-control" id="id_docente" name="id_docente" required>
                        <option value="">Seleccione un docente</option>
                        <?php foreach ($docentes as $docente): ?>
                        <option value="<?php echo $docente['id_usuario']; ?>">
                            <?php echo htmlspecialchars($docente['nombre']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="contrasena">Contraseña del Curso</label>
                    <input type="text" class="form-control" id="contrasena" name="contrasena" required>
                    <small class="form-text text-muted">Esta contraseña será usada por los estudiantes para inscribirse</small>
                </div>
                
                <div class="form-group">
                    <label for="capacidad">Capacidad Máxima</label>
                    <input type="number" class="form-control" id="capacidad" name="capacidad" value="50" min="1" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Crear Curso</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>