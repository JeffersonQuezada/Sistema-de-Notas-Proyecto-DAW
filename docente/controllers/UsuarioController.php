<?php
require_once __DIR__ . '/../models/UsuarioModel.php';

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    public function registrarUsuario($nombre, $email, $contrasena, $rol) {
        try {
            // Validaciones básicas
            if (empty($nombre) || empty($email) || empty($contrasena) || empty($rol)) {
                throw new Exception("Todos los campos son obligatorios");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("El email no tiene un formato válido");
            }

            if (!in_array($rol, ['docente', 'estudiante', 'admin'])) {
                throw new Exception("Rol no válido");
            }

            // Verificar si el email ya existe
            if ($this->usuarioModel->verificarEmailExiste($email)) {
                throw new Exception("El email ya está registrado");
            }

            // Hash de la contraseña
            $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

            // Crear usuario
            $resultado = $this->usuarioModel->crearUsuario($nombre, $email, $contrasenaHash, $rol);

            if ($resultado) {
                return $resultado;
            } else {
                throw new Exception("Error al registrar el usuario");
            }
            
        } catch (Exception $e) {
            error_log("Error en registrarUsuario: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarPerfil($id_usuario, $nombre, $email, $contrasena_actual = null, $nueva_contrasena = null) {
        try {
            // Validar que el usuario existe
            $usuario = $this->usuarioModel->obtenerUsuarioPorId($id_usuario);
            if (!$usuario) {
                throw new Exception("Usuario no encontrado");
            }

            // Validar campos obligatorios
            if (empty($nombre) || empty($email)) {
                throw new Exception("Nombre y email son obligatorios");
            }

            // Verificar email único (excepto para el usuario actual)
            if ($this->usuarioModel->verificarEmailExiste($email, $id_usuario)) {
                throw new Exception("El email ya está registrado por otro usuario");
            }

            $datosActualizar = [
                'nombre' => $nombre,
                'email' => $email
            ];

            // Si se proporciona nueva contraseña, validar y actualizar
            if (!empty($nueva_contrasena)) {
                if (empty($contrasena_actual)) {
                    throw new Exception("Debes proporcionar la contraseña actual para cambiarla");
                }

                if (!password_verify($contrasena_actual, $usuario['contrasena'])) {
                    throw new Exception("La contraseña actual es incorrecta");
                }

                $datosActualizar['contrasena'] = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
            }

            $resultado = $this->usuarioModel->actualizarUsuario($id_usuario, $datosActualizar);

            if ($resultado) {
                return true;
            } else {
                throw new Exception("Error al actualizar el perfil");
            }
            
        } catch (Exception $e) {
            error_log("Error en actualizarPerfil: " . $e->getMessage());
            return false;
        }
    }

    public function listarUsuariosPorRol($rol) {
        try {
            if (!in_array($rol, ['docente', 'estudiante', 'admin'])) {
                throw new Exception("Rol no válido");
            }

            return $this->usuarioModel->listarUsuariosPorRol($rol);
            
        } catch (Exception $e) {
            error_log("Error en listarUsuariosPorRol: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerUsuarioPorId($id_usuario) {
        try {
            return $this->usuarioModel->obtenerUsuarioPorId($id_usuario);
        } catch (Exception $e) {
            error_log("Error en obtenerUsuarioPorId: " . $e->getMessage());
            return false;
        }
    }
}
?>