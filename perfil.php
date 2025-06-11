<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones.php';

$pageTitle = "Mi Perfil";
include __DIR__ . '/includes/header.php';

// Obtener datos del usuario actual
try {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$_SESSION['id_usuario']]);
    $usuario = $stmt->fetch();
} catch (PDOException $e) {
    die("Error al cargar perfil: " . $e->getMessage());
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Mi Perfil</h1>
    
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($usuario['nombre']) ?>&size=200" 
                         class="rounded-circle mb-3" width="150">
                    <h4><?= htmlspecialchars($usuario['nombre']) ?></h4>
                    <p class="text-muted"><?= ucfirst($usuario['rol']) ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información Personal</h6>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nombre Completo</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Correo Electrónico</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" value="<?= htmlspecialchars($usuario['correo']) ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Rol</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="<?= ucfirst($usuario['rol']) ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Fecha de Registro</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" 
                                       value="<?= date('d/m/Y', strtotime($usuario['fecha_registro'])) ?>" readonly>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($mensaje)) echo "<div style='background:#fff;color:#000;padding:10px;'>$mensaje</div>"; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>