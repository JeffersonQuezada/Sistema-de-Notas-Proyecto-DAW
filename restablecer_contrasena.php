<?php
include 'includes/conexion.php';
$token = $_GET['token'] ?? '';
$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $nueva = $_POST['nueva'];
    $stmt = $pdo->prepare("SELECT id_usuario, token_expira FROM usuarios WHERE token_recuperacion = ?");
    $stmt->execute([$token]);
    $usuario = $stmt->fetch();
    if ($usuario && strtotime($usuario['token_expira']) > time()) {
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE usuarios SET contrasena = ?, token_recuperacion = NULL, token_expira = NULL WHERE id_usuario = ?");
        $update->execute([$hash, $usuario['id_usuario']]);
        $exito = "Contraseña restablecida correctamente.";
    } else {
        $error = "El enlace es inválido o ha expirado.";
    }
}
?>

<!-- Formulario HTML -->
<form method="POST">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <label>Nueva contraseña:</label>
    <input type="password" name="nueva" required>
    <button type="submit">Restablecer</button>
    <?php if ($error): ?><div><?= $error ?></div><?php endif; ?>
    <?php if ($exito): ?><div><?= $exito ?></div><?php endif; ?>
</form>