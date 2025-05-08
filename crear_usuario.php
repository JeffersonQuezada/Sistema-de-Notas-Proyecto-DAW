<?php
include 'includes/conexion.php'; // Incluimos el archivo de conexión PDO

// Inicializamos variables para los datos del formulario y mensajes de error
$nombre = $correo = $contrasena = "";
$error = "";
$success = "";

// Procesamos el envío del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenemos y sanitizamos los datos del formulario
    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $contrasena = trim($_POST["contrasena"]);
    
    // Validación básica
    if (empty($nombre) || empty($correo) || empty($contrasena)) {
        $error = "Todos los campos son obligatorios";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "Formato de correo electrónico inválido";
    } else {
        try {
            // Verificamos si el correo ya existe
            $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = :correo");
            $stmt->execute(['correo' => $correo]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Este correo ya está registrado";
            } else {
                // Encriptamos la contraseña
                $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
                
                // Establecemos el rol por defecto como 'estudiante'
                $rol = 'estudiante';
                
                // Insertamos el usuario en la base de datos
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (:nombre, :correo, :contrasena, :rol)");
                $stmt->execute([
                    'nombre' => $nombre,
                    'correo' => $correo,
                    'contrasena' => $hashed_password,
                    'rol' => $rol
                ]);
                
                if ($stmt->rowCount() > 0) {
                    $success = "¡Cuenta creada exitosamente! Ahora puedes iniciar sesión.";
                    // Reseteamos los campos después de un registro exitoso
                    $nombre = $correo = $contrasena = "";
                    
                    // Con SweetAlert ya no es necesario mantener la página actual,
                    // podemos redirigir automáticamente después de mostrar el mensaje
                } else {
                    $error = "Error al crear la cuenta.";
                }
            }
        } catch(PDOException $e) {
            $error = "Error en la base de datos: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<section class="vh-100" style="background-color: #508bfc;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <h3 class="mb-5">Crear Cuenta</h3>
            
            <?php if (!empty($error) || !empty($success)): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    <?php if (!empty($error)): ?>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '<?php echo addslashes($error); ?>',
                        confirmButtonColor: '#3085d6'
                    });
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: '<?php echo addslashes($success); ?>',
                        confirmButtonColor: '#3085d6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'login.php';
                        }
                    });
                    <?php endif; ?>
                });
            </script>
            <?php endif; ?>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-outline mb-4">
                  <input type="text" id="nombre" name="nombre" class="form-control form-control-lg" value="<?php echo htmlspecialchars($nombre); ?>" required />
                  <label class="form-label" for="nombre">Nombre completo</label>
                </div>

                <div class="form-outline mb-4">
                  <input type="email" id="correo" name="correo" class="form-control form-control-lg" value="<?php echo htmlspecialchars($correo); ?>" required />
                  <label class="form-label" for="correo">Email</label>
                </div>

                <div class="form-outline mb-4">
                  <input type="password" id="contrasena" name="contrasena" class="form-control form-control-lg" required />
                  <label class="form-label" for="contrasena">Contraseña</label>
                </div>

                <button class="btn btn-primary btn-lg btn-block" type="submit">Registrarse</button>

                <hr class="my-4">
                
                <p>¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a></p>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>