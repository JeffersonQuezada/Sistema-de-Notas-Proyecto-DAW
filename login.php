

<?php
// Debug temporal - remover después

session_start(); // Iniciamos la sesión
include 'includes/conexion.php'; // Incluimos el archivo de conexión PDO

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

// Inicializamos variables
$error = "";
$mostrar_cambio_password = false;

// Procesamos el cambio de contraseña (primer login)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cambiar_password"])) {
  $nueva_contrasena = trim($_POST["nueva_contrasena"]);
  $confirmar_contrasena = trim($_POST["confirmar_contrasena"]);
  $id_usuario = $_POST["id_usuario"];

  if (empty($nueva_contrasena) || empty($confirmar_contrasena)) {
    $error = "Ambos campos de contraseña son obligatorios";
    $mostrar_cambio_password = true;
  } elseif (strlen($nueva_contrasena) < 6) {
    $error = "La nueva contraseña debe tener al menos 6 caracteres";
    $mostrar_cambio_password = true;
  } elseif ($nueva_contrasena !== $confirmar_contrasena) {
    $error = "Las contraseñas no coinciden";
    $mostrar_cambio_password = true;
  } else {
    try {
      // Actualizar la contraseña y marcar que ya no es primer login
      $hashed_password = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("UPDATE usuarios SET contrasena = :contrasena, primer_login = 0 WHERE id_usuario = :id_usuario");
      $stmt->execute([
        'contrasena' => $hashed_password,
        'id_usuario' => $id_usuario
      ]);

      if ($stmt->rowCount() > 0) {
        // Obtener datos del usuario para la sesión
        $stmt = $pdo->prepare("SELECT id_usuario, nombre, correo, rol FROM usuarios WHERE id_usuario = :id_usuario");
        $stmt->execute(['id_usuario' => $id_usuario]);
        $usuario = $stmt->fetch();

        // Establecer variables de sesión
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['correo'] = $usuario['correo'];
        $_SESSION['rol'] = $usuario['rol'];

        // Mostrar mensaje de éxito y redirigir
        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "success",
                        title: "¡Contraseña actualizada!",
                        text: "Tu contraseña ha sido cambiada exitosamente",
                        confirmButtonColor: "#3085d6"
                    }).then((result) => {
                        if (result.isConfirmed) {';

        // Redirigir según el rol
        switch ($usuario['rol']) {
          case 'admin':
            header("Location: admin/index.php"); // ✅ Esto sí funciona
            exit();
          case 'docente':
            header("Location: docente/views/dashboard_principal.php");
            exit();
          case 'estudiante':
            header("Location: estudiante/views/dashboard_alumnos.php");
            exit();
          default:
            // Si el rol no es reconocido
            $error = "Rol de usuario no válido";
            break;
        }

        echo '        }
                    });
                });
                </script>';
      } else {
        $error = "Error al actualizar la contraseña";
        $mostrar_cambio_password = true;
      }
    } catch (PDOException $e) {
      $error = "Error en la base de datos: " . $e->getMessage();
      $mostrar_cambio_password = true;
    }
  }
}

// Procesamos el formulario de login
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["cambiar_password"])) {
  // Obtenemos y sanitizamos los datos del formulario
  $correo = trim($_POST["correo"]);
  $contrasena = trim($_POST["contrasena"]);

  // Validación básica
  if (empty($correo) || empty($contrasena)) {
    $error = "Por favor, completa todos los campos";
  } else {
    try {
      // Verificamos las credenciales
      $stmt = $pdo->prepare("SELECT id_usuario, nombre, correo, contrasena, rol, primer_login FROM usuarios WHERE correo = :correo");
      $stmt->execute(['correo' => $correo]);

      if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch();

        // Verificamos la contraseña
        if (password_verify($contrasena, $usuario['contrasena'])) {
          // Verificar si es el primer login
          if ($usuario['primer_login'] == 1) {
            // Mostrar formulario de cambio de contraseña
            $mostrar_cambio_password = true;
            $id_usuario_cambio = $usuario['id_usuario'];
            $nombre_usuario = $usuario['nombre'];
          } else {
            // Inicio de sesión exitoso
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['rol'] = $usuario['rol'];

            // Redirigimos según el rol
            switch ($usuario['rol']) {
              case 'admin':
                header("Location: admin/index.php");
                exit();
              case 'docente':
                header("Location: ../docente/views/dashboard_principal.php");
                exit();
              case 'estudiante':
                header("Location: ../estudiante/views/dashboard_alumnos.php");
                exit();
              default:
                $error = "Rol de usuario no válido";
                break;
            }
          }
        } else {
          $error = "Contraseña incorrecta";
        }
      } else {
        $error = "El correo electrónico no está registrado";
      }
    } catch (PDOException $e) {
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
  <title><?php echo $mostrar_cambio_password ? 'Cambiar Contraseña' : 'Login'; ?></title>

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

              <?php if ($mostrar_cambio_password): ?>
                <!-- Formulario de cambio de contraseña -->
                <div class="mb-md-5 mt-md-4 pb-5">
                  <h2 class="fw-bold mb-2 text-uppercase">Cambiar Contraseña</h2>
                  <p class="text-white-50 mb-4">¡Bienvenido <?php echo htmlspecialchars($nombre_usuario); ?>!</p>
                  <p class="text-warning mb-5">
                    <i class="fas fa-exclamation-triangle"></i>
                    Por seguridad, debes cambiar tu contraseña en el primer inicio de sesión
                  </p>

                  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="cambiar_password" value="1">
                    <input type="hidden" name="id_usuario" value="<?php echo $id_usuario_cambio; ?>">

                    <div class="form-outline form-white mb-4">
                      <input type="password" id="nueva_contrasena" name="nueva_contrasena" class="form-control form-control-lg" required placeholder="Nueva contraseña" />
                      <label class="form-label" for="nueva_contrasena">Nueva contraseña (mínimo 6 caracteres)</label>
                    </div>

                    <div class="form-outline form-white mb-4">
                      <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" class="form-control form-control-lg" required placeholder="Confirmar contraseña" />
                      <label class="form-label" for="confirmar_contrasena">Confirmar nueva contraseña</label>
                    </div>

                    <button class="btn btn-warning btn-lg px-5" type="submit">
                      <i class="fas fa-key"></i> Cambiar Contraseña
                    </button>
                  </form>
                </div>

              <?php else: ?>
                <!-- Formulario de login normal -->
                <div class="mb-md-5 mt-md-4 pb-5">
                  <h2 class="fw-bold mb-2 text-uppercase">Iniciar Sesión</h2>
                  <p class="text-white-50 mb-5">¡Por favor ingresa tu correo y contraseña!</p>

                  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-outline form-white mb-4">
                      <input type="email" id="correo" name="correo" class="form-control form-control-lg" required autofocus placeholder="Correo electrónico" />
                      <label class="form-label" for="correo">Correo electrónico</label>
                    </div>

                    <div class="form-outline form-white mb-4">
                      <input type="password" id="contrasena" name="contrasena" class="form-control form-control-lg" required placeholder="Contraseña" />
                      <label class="form-label" for="contrasena">Contraseña</label>
                    </div>

                    <p class="small mb-5 pb-lg-2">
                      <a class="text-white-50" href="recuperar_contrasena.php">¿Olvidaste tu contraseña?</a>
                    </p>

                    <button class="btn btn-outline-light btn-lg px-5" type="submit">Ingresar</button>
                  </form>
                </div>

                <div>
                  <p class="mb-0">¿No tienes una cuenta? <a href="crear_cuenta.php" class="text-white-50 fw-bold">Regístrate</a></p>
                </div>
                <div>
                  <p class="mb-0"> <a href="index.php" class="text-white-50 fw-bold">Regresar</a></p>
                </div>
              <?php endif; ?>

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