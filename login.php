<?php

// Mostrar mensaje de logout exitoso
if (isset($_GET['logout'])) {
  echo '<script>
  document.addEventListener("DOMContentLoaded", function() {
      Swal.fire({
          icon: "success",
          title: "Sesión cerrada",
          text: "Has cerrado sesión correctamente",
          confirmButtonColor: "#4e73df"
      });
  });
  </script>';
}
session_start(); // Iniciamos la sesión
include 'includes/conexion.php'; // Incluimos el archivo de conexión PDO

// Inicializamos variables
$error = "";

// Procesamos el formulario de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenemos y sanitizamos los datos del formulario
    $correo = trim($_POST["correo"]);
    $contrasena = trim($_POST["contrasena"]);
    
    // Validación básica
    if (empty($correo) || empty($contrasena)) {
        $error = "Por favor, completa todos los campos";
    } else {
        try {
            // Verificamos las credenciales
            $stmt = $pdo->prepare("SELECT id_usuario, nombre, correo, contrasena, rol FROM usuarios WHERE correo = :correo");
            $stmt->execute(['correo' => $correo]);
            
            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch();
                
                // Verificamos la contraseña
                if (password_verify($contrasena, $usuario['contrasena'])) {
                    // Inicio de sesión exitoso
                    $_SESSION['id_usuario'] = $usuario['id_usuario'];
                    $_SESSION['nombre'] = $usuario['nombre'];
                    $_SESSION['correo'] = $usuario['correo'];
                    $_SESSION['rol'] = $usuario['rol'];
                    
                    // Redirigimos según el rol
                    switch ($usuario['rol']) {
                        case 'admin':
                            header("Location: admin/dashboard.php");
                            exit();
                        case 'docente':
                            header("Location: docente/index.php");
                            exit();
                        case 'estudiante':
                            header("Location: estudiante/dashboard_alumnos.php");
                            exit();
                        default:
                            // Si el rol no es reconocido
                            $error = "Rol de usuario no válido";
                            break;
                    }
                } else {
                    $error = "Contraseña incorrecta";
                }
            } else {
                $error = "El correo electrónico no está registrado";
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
  <title>Login</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    .gradient-custom {
      background: #6a11cb;
      background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
    }
  </style>
</head>
<body>
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

<section class="vh-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-dark text-white" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <div class="mb-md-5 mt-md-4 pb-5">
              <h2 class="fw-bold mb-2 text-uppercase">Iniciar Sesión</h2>
              <p class="text-white-50 mb-5">¡Por favor ingresa tu correo y contraseña!</p>

              <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-outline form-white mb-4">
                  <input type="email" id="correo" name="correo" class="form-control form-control-lg" required />
                  <label class="form-label" for="correo">Correo electrónico</label>
                </div>

                <div class="form-outline form-white mb-4">
                  <input type="password" id="contrasena" name="contrasena" class="form-control form-control-lg" required />
                  <label class="form-label" for="contrasena">Contraseña</label>
                </div>

                <p class="small mb-5 pb-lg-2"><a class="text-white-50" href="#!">¿Olvidaste tu contraseña?</a></p>

                <button class="btn btn-outline-light btn-lg px-5" type="submit">Ingresar</button>
              </form>
            </div>

            <div>
              <p class="mb-0">¿No tienes una cuenta? <a href="crear_cuenta.php" class="text-white-50 fw-bold">Regístrate</a></p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>