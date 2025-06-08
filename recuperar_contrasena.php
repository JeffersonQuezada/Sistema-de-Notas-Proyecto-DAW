<?php
session_start();
include 'includes/conexion.php';

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST["correo"]);

    if (empty($correo)) {
        $error = "Por favor, ingresa tu correo electrónico.";
    } else {
        // Verifica si el correo existe en la base de datos
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = :correo");
        $stmt->execute(['correo' => $correo]);
        if ($stmt->rowCount() > 0) {
            // Aquí normalmente enviarías un correo con instrucciones de recuperación
            $mensaje = "Si el correo está registrado, recibirás instrucciones para restablecer tu contraseña.";
        } else {
            $mensaje = "Si el correo está registrado, recibirás instrucciones para restablecer tu contraseña.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .gradient-custom {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="gradient-custom">
    <?php if (!empty($mensaje)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Recuperación',
                    text: '<?php echo addslashes($mensaje); ?>',
                    confirmButtonColor: '#3085d6'
                });
            });
        </script>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo addslashes($error); ?>',
                    confirmButtonColor: '#3085d6'
                });
            });
        </script>
    <?php endif; ?>

    <section class="vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white">
                        <div class="card-body p-5 text-center">
                            <h2 class="fw-bold mb-2 text-uppercase">Recuperar Contraseña</h2>
                            <p class="text-white-50 mb-4">Ingresa tu correo electrónico y te enviaremos instrucciones para restablecer tu contraseña.</p>
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <div class="form-outline form-white mb-4">
                                    <input type="email" id="correo" name="correo" class="form-control form-control-lg" placeholder="Correo electrónico" required autofocus />
                                </div>
                                <button class="btn btn-outline-light btn-lg px-5 w-100" type="submit">
                                    <i class="fa fa-paper-plane"></i> Enviar instrucciones
                                </button>
                            </form>
                            <div class="mt-4">
                                <a href="login.php" class="text-white-50 fw-bold"><i class="fa fa-arrow-left"></i> Volver al login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>