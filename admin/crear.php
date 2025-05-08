


<?php

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/funciones.php';

if ($_SESSION['rol'] != 'admin') {
    header("Location: ../../login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contrasena = trim($_POST['contrasena']);
    $rol = $_POST['rol'];
    
    // Validaciones
    if (empty($nombre) || empty($correo) || empty($contrasena)) {
        $error = 'Todos los campos son obligatorios';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'Formato de correo inválido';
    } else {
        try {
            // Verificar si el correo ya existe
            $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
            $stmt->execute([$correo]);
            
            if ($stmt->rowCount() > 0) {
                $error = 'Este correo ya está registrado';
            } else {
                // Crear usuario
                $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nombre, $correo, $hashed_password, $rol]);
                
                if ($stmt->rowCount() > 0) {
                    $success = 'Usuario creado exitosamente';
                    header("Refresh:2; url=index.php");
                }
            }
        } catch(PDOException $e) {
            $error = 'Error al crear el usuario: ' . $e->getMessage();
        }
    }
}
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">
    <h2>Crear Nuevo Usuario</h2>
    
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
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="correo">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                
                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>
                
                <div class="form-group">
                    <label for="rol">Rol</label>
                    <select class="form-control" id="rol" name="rol" required>
                        <option value="admin">Administrador</option>
                        <option value="docente">Docente</option>
                        <option value="estudiante">Estudiante</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>