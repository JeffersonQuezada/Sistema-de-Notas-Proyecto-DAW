<?php
include 'includes/conexion.php'; // Incluimos el archivo de conexión PDO

// Inicializamos variables para los datos del formulario y mensajes de error
$nombre = $apellido = $contrasena = "";
$error = "";
$success = "";
$correo_generado = "";

// Función para generar correo automáticamente
function generarCorreo($nombre, $apellido, $pdo) {
    // Limpiar y convertir a minúsculas
    $nombre = strtolower(trim($nombre));
    $apellido = strtolower(trim($apellido));
    
    // Remover acentos y caracteres especiales
    $nombre = eliminarAcentos($nombre);
    $apellido = eliminarAcentos($apellido);
    
    // Tomar primera parte del nombre y apellido
    $base_correo = substr($nombre, 0, 3) . substr($apellido, 0, 3);
    
    // Obtener los dos últimos dígitos del año actual
    $año_actual = date('y'); // Obtiene los dos últimos dígitos del año
    
    // Buscar el siguiente número disponible
    $numero = 1;
    do {
        $correo = $base_correo . sprintf('%02d', $numero) . $año_actual . '@estudiante.edu';
        
        // Verificar si el correo ya existe
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = :correo");
        $stmt->execute(['correo' => $correo]);
        
        if ($stmt->rowCount() == 0) {
            return $correo; // Correo disponible
        }
        
        $numero++;
    } while ($numero <= 99); // Límite de 99 intentos
    
    return false; // No se pudo generar un correo único
}

// Función para eliminar acentos
function eliminarAcentos($cadena) {
    $acentos = array(
        'á' => 'a', 'à' => 'a', 'ä' => 'a', 'â' => 'a', 'ā' => 'a', 'ã' => 'a',
        'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e', 'ē' => 'e',
        'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i', 'ī' => 'i',
        'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ô' => 'o', 'ō' => 'o', 'õ' => 'o',
        'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u', 'ū' => 'u',
        'ñ' => 'n', 'ç' => 'c'
    );
    
    return strtr($cadena, $acentos);
}

// Procesamos el envío del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenemos y sanitizamos los datos del formulario
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $contrasena = trim($_POST["contrasena"]);
    
    // Validación básica
    if (empty($nombre) || empty($apellido) || empty($contrasena)) {
        $error = "Todos los campos son obligatorios";
    } elseif (strlen($contrasena) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } else {
        try {
            // Generar correo automáticamente
            $correo_generado = generarCorreo($nombre, $apellido, $pdo);
            
            if (!$correo_generado) {
                $error = "No se pudo generar un correo único. Intente más tarde.";
            } else {
                // Encriptamos la contraseña
                $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
                
                // Establecemos el rol por defecto como 'estudiante'
                $rol = 'estudiante';
                
                // Insertamos el usuario en la base de datos con primer_login = 1
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol, primer_login) VALUES (:nombre, :correo, :contrasena, :rol, 1)");
                $stmt->execute([
                    'nombre' => $nombre . ' ' . $apellido,
                    'correo' => $correo_generado,
                    'contrasena' => $hashed_password,
                    'rol' => $rol
                ]);
                
                if ($stmt->rowCount() > 0) {
                    $success = "¡Cuenta creada exitosamente!<br><strong>Tu correo generado es:</strong> " . $correo_generado . "<br>Guarda este correo para iniciar sesión.";
                    // Reseteamos los campos después de un registro exitoso
                    $nombre = $apellido = $contrasena = "";
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

            <h3 class="mb-4">Crear Cuenta</h3>
            <p class="text-muted mb-4">El correo electrónico se generará automáticamente</p>
            
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
                        html: '<?php echo addslashes($success); ?>',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ir a Iniciar Sesión'
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
                  <label class="form-label" for="nombre">Nombre</label>
                </div>

                <div class="form-outline mb-4">
                  <input type="text" id="apellido" name="apellido" class="form-control form-control-lg" value="<?php echo htmlspecialchars($apellido); ?>" required />
                  <label class="form-label" for="apellido">Apellido</label>
                </div>

                <div class="form-outline mb-4">
                  <input type="password" id="contrasena" name="contrasena" class="form-control form-control-lg" required />
                  <label class="form-label" for="contrasena">Contraseña (mínimo 6 caracteres)</label>
                </div>

                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i>
                    <strong>Nota:</strong> Tu correo electrónico se generará automáticamente basado en tu nombre y apellido.
                    <br><small>Formato: [3 letras nombre][3 letras apellido][número][año]@estudiante.edu</small>
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