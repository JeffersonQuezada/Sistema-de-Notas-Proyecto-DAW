<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
if (session_status() === PHP_SESSION_NONE) session_start();

class UsuarioController {
    private $usuarioModel;
    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }
    public function listar() {
        $usuarios = $this->usuarioModel->listarUsuarios();
        include __DIR__ . '/../views/usuarios_listado.php';
    }
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $contrasena = $_POST['contrasena'];
            $rol = $_POST['rol'];
            // Validar si el correo ya existe
            if ($this->usuarioModel->existeCorreo($correo)) {
                header("Location: ../index.php?accion=usuarios_crear&error=El correo ya está registrado");
                exit();
            }
            $this->usuarioModel->crearUsuario($nombre, $correo, $contrasena, $rol);
            header("Location: ../index.php?accion=usuarios&success=1");
            exit();
        }
        include __DIR__ . '/../views/usuarios_crear.php';
    }
    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuario = $_POST['id_usuario'];
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $rol = $_POST['rol'];
            $this->usuarioModel->actualizarUsuario($id_usuario, $nombre, $correo, $rol);
            header("Location: ../index.php?accion=usuarios&success=1");
            exit();
        }
        // Obtener datos del usuario para mostrar en el formulario
        $id_usuario = $_GET['id'] ?? null;
        if ($id_usuario) {
            $usuario = $this->usuarioModel->obtenerUsuarioPorId($id_usuario);
            include __DIR__ . '/../views/usuario_editar.php';
        } else {
            header("Location: ../index.php?accion=usuarios&error=Usuario no encontrado");
            exit();
        }
    }
}

// --- Manejo directo de la acción ---
if (isset($_GET['accion'])) {
    $controller = new UsuarioController();
    $accion = $_GET['accion'];
    if ($accion === 'listar') {
        $controller->listar();
    } elseif ($accion === 'crear') {
        $controller->crear();
    } elseif ($accion === 'editar') {
        $controller->editar();
    }
}
?>